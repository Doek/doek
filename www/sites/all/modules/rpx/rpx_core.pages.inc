<?php
// $Id: rpx_core.pages.inc,v 1.7 2011/02/07 11:03:35 geokat Exp $

/**
 * @file
 * User page callbacks for the rpx_core module.
 */

/**
 * The Engage Sign-in transaction end point. Engage redirects the user to this
 * URL upon successful authentication by a 3rd party.
 *
 * See @link https://rpxnow.com/docs#flow Janrain Engage Flow @endlink for a
 * detailed decription of this process.
 */
function rpx_token_handler() {
  global $user;

  // are we completing an Engage sign-in transaction?
  $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
  $add_to_account = isset($_REQUEST['add_to_account']) ? TRUE : FALSE;

  if ($token) {
    $_SESSION['rpx'] = RPX::auth_info($token, variable_get('rpx_apikey', ''), !$add_to_account && variable_get('rpx_extended_authinfo', FALSE));
    if (!$_SESSION['rpx']) {
      drupal_goto();
    }
    // auth_info() stat should be ok
    if ($_SESSION['rpx']['stat'] != 'ok') {
      drupal_set_message(t('Failed to obtain your 3rd party identifier.'), 'error');
      watchdog('rpx_core', 'Failed to obtain a 3rd party identifier for user ID %id: auth_info() returned error: %err', array('%id' => $user->uid, '%err' => $_SESSION['rpx']['err']['msg']), WATCHDOG_ERROR);
      drupal_goto();
    }

    $rpxid = $_SESSION['rpx']['profile']['identifier'];

    // Are we adding a new identity to an existing account?
    if ($add_to_account) {
      if (user_is_logged_in()) {
        // the identifier returned by auth_info() should not be already
        // mapped to an existing account
        if (user_get_authmaps($rpxid)) {
          $message = array(
            'text' => t('That identifier is already mapped to an account.'),
            'type' => 'error',
          );
        }
        else {
          // Can't use user_set_authmaps() here, since it doesn't support
          // multiple authnames per user via same module
          db_insert('authmap')
            ->fields(array(
              'uid' => $user->uid,
              'authname' => $rpxid,
              'module' => 'rpx_core',
            ))
            ->execute();

          $provider = _rpx_get_identity_provider($rpxid);
          $message = array(
            'text' => t('We have successfully linked your %provider account.', array('%provider' => $provider['title'])),
            'type' => 'status',
          );

          // Let other modules (e.g. rpx_rules) know that a linked account has been
          // added.
          $account = array(
            'user' => $user,
            'id' => $rpxid,
          );
          module_invoke_all('rpx_linked_account', 'added', $account);
        }
        // Redirect the user back to original overlay if possible.
        // @see rpx_user_identities().
        if (isset($_SESSION['rpx_overlay_uri'])) {
          $_GET['destination'] = $_SESSION['rpx_overlay_uri'];
          // Store the message for rpx_user_identities() so that it
          // can be shown in overlay instead of the parent window.
          $_SESSION['rpx_overlay_message'] = $message;
          drupal_goto();
        }
        else {
          unset($_SESSION['rpx_overlay_message']);
          drupal_set_message($message['text'], $message['type']);
          drupal_goto('user/' . $user->uid . '/rpx');
        }
      }
    }

    $account = user_external_load($rpxid);

    // Is this a registered user?
    if (isset($account->uid)) {
      if (!variable_get('user_email_verification', TRUE) ||
          $account->login ||
          !empty($account->data['rpx_data']['profile']['verifiedEmail']) &&
          strtolower($account->data['rpx_data']['profile']['verifiedEmail']) == strtolower($account->mail)) {
        // IF settings do not require email verification
        // OR
        // it's not the first login for the user (which means the email has
        // already been verified)
        // OR
        // they are using an email the ID provider has already verified
        //
        // then we can skip the email verification process

        // Check that the user has not been blocked.
        $state['values']['name'] = $account->name;
        user_login_name_validate(array(), $state);
        if (!form_get_errors()) {
          // Load global $user and perform final login tasks.
          $form_state['uid'] = $account->uid;
          user_login_submit(array(), $form_state);
        }
      }
      else {
        drupal_set_message(t('You must validate your email address for this account before logging in with it.'), 'error');
      }
      drupal_goto();
    }
    // New user.
    else {
      // Check that users are allowed to register on their own.
      if (variable_get('user_register', 1)) {
        if (!variable_get('rpx_force_registration_form', FALSE)) {
          $form_state['values'] = array();
          $form_state['values']['op'] = t('Create new account');
          drupal_form_submit('user_register_form', $form_state);
          // See if the user was successfully registered.
          if (!empty($form_state['user'])) {
            // Let other modules (e.g. rpx_rules) know that a linked account has been
            // added.
            $account = array(
              'user' => $user,
              'id' => $rpxid,
            );
            module_invoke_all('rpx_linked_account', 'added', $account);
            // Nothing more to do.
            drupal_goto();
          }
          // get the error messages and clear the messages queue
          $messages = drupal_get_messages('error');

          if (empty($form_state['values']['mail'])) {
            // If the idenitity provider did not provide an email address, ask
            // the user to complete (and submit) the form manually instead of
            // showing the error messages about the missing values generated by
            // FAPI.
            drupal_set_message(t('Your login provider did not provide an email address. Please enter one below to complete your registration. If you already have an account, you can <a href="@login">log in</a> now and add your 3rd party identity under "My account". ', array('@login' => url('user/login'))), 'warning');
          }
          else {
            drupal_set_message(t('Account registration using the information provided by your identity provider failed due to the reasons listed below. Complete the registration by filling out the form below. If you already have an account, you can <a href="@login">log in</a> now and add your 3rd party identity under "My account". ', array('@login' => url('user/login'))), 'warning');
            // Append form validation errors below the above warning.
            foreach ($messages['error'] as $message) {
              drupal_set_message($message, 'error');
            }
          }
        }
        else {
          drupal_set_message(t('Complete the registration by filling out the form below. If you already have an account, you can <a href="@login">log in</a> now and add your 3rd party identity under "My account". ', array('@login' => url('user/login'))), 'warning');
        }

        // Redirect to the normal registration page and prefill with the values
        // we received from Engage.
        $destination = drupal_get_destination();
        unset($_GET['destination']);
        drupal_goto('user/register', array('query' => $destination));
      }
      else {
        drupal_set_message(t('Only site administrators can create new user accounts.'), 'error');
        drupal_goto();
      }
    }
  }
  else {
    drupal_set_message(t('You need a token to be here!'), 'error');
    return MENU_ACCESS_DENIED;
  }
}

/**
 * Menu callback; confirm email for Engage registrations that require it.
 *
 * @see rpx_core_mail()
 * @see _rpx_mail_text()
 */
function rpx_email_confirm($uid, $timestamp, $hashed_pass) {
  global $user;

  // Make sure that a user isn't already logged in.
  if ($user->uid) {
    // The user is already logged in
    if ($user->uid == $uid) {
      drupal_set_message(t('You have already used this email confirmation link and you are already logged in.'));
      drupal_goto();
    }
    // A different user is already logged in on the computer.
    else {
      $reset_link_account = user_load($uid);
      if (!empty($reset_link_account)) {
        drupal_set_message(t('Another user (%other_user) is already logged into the site on this computer, but you tried to use a one-time link for user %resetting_user. Please <a href="!logout">logout</a> and try using the link again.',
          array('%other_user' => $user->name, '%resetting_user' => $reset_link_account->name, '!logout' => url('user/logout'))));
      } else {
        // Invalid one-time link specifies an unknown user.
        drupal_set_message(t('The one-time login link you clicked is invalid.'));
      }
    }
    drupal_goto();
  }
  else {
    $user = user_load_multiple(array($uid), array('status' => 1));
    if ($account = array_shift($user)) {
      if ($account->login) {
        drupal_set_message(t('Your email address has already been confirmed and you may login at any time.'));
        drupal_goto('user');
      }
      elseif ($hashed_pass == user_pass_rehash($account->pass, $timestamp, $account->login)) {
        // Load global $user and perform final login tasks.
        $form_state['uid'] = $account->uid;
        user_login_submit(array(), $form_state);
        drupal_set_message(t('Thank you for confirming your email address.'));
        drupal_goto('user/' . $user->uid . '/edit');
      }
    }
  }
  // If all else fails, deny access.
  drupal_access_denied();
}

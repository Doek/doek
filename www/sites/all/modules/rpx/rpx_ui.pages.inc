<?php
// $Id: rpx_ui.pages.inc,v 1.13 2011/02/07 11:03:35 geokat Exp $

/**
 * @file
 * User page callback file for the rpx_ui module.
 */

/**
 * Menu callback; manage Engage 3rd party identities for the specified user.
 */
function rpx_user_identities($account) {
  drupal_set_title(format_username($account));

  $header = array(t('Account type'), t('Account ID'), t('Operations'));
  $rows = array();

  $result = db_query("SELECT * FROM {authmap} WHERE module = :module AND uid = :uid", array(':module' => 'rpx_core', ':uid' => $account->uid));

  foreach ($result as $identity) {
    $provider = _rpx_get_identity_provider($identity->authname);
    $rows[] = array(
      theme('rpx_icon', array('provider' => $provider['name'], 'style' => 'rpx-icon-inline')) . $provider['title'],
      check_plain($identity->authname),
      l(t('Delete'), 'user/' . $account->uid . '/rpx/delete/' . $identity->aid),
    );
  }

  rpx_js();
  $realm = variable_get('rpx_realm', '');
  $realm_scheme = variable_get('rpx_realm_scheme', 'http');
  $sign_in_url = "$realm_scheme://$realm/openid/v2/signin";

  // If we are in overlay, we want the user redirected back to it after the
  // account is linked. We have to rely on HTTP_REFERER and do it manually
  // since the sign-in widget bypasses the overlay code when POSTing to
  // token_url, causing a complete page reload.
  //
  // Only clobber $_SESSION['rpx_overlay_uri'] if we haven't visited an account
  // deletetion confirm_form. Otherwise we will get a "Page not found" error
  // (@see http://drupal.org/node/1029458#comment-4055748), because HTTP_REFERER
  // has been changed to the confirmation form's URI.
  if (isset($_SESSION['rpx_linked_account_deleted'])) {
    unset($_SESSION['rpx_linked_account_deleted']);
  }
  else {
    unset($_SESSION['rpx_overlay_uri']);
    if (function_exists('overlay_get_mode') && overlay_get_mode() == 'child' && isset($_SERVER['HTTP_REFERER'])) {
      // Construct referer URI to be passed to token_url.
      $referer = $_SERVER['HTTP_REFERER'];
      if (variable_get('clean_url', FALSE)) {
        // Clean URLs are enabled.
        global $base_url;
        $referer = substr($referer, strlen($base_url)+1);
      }
      else {
        $referer = substr(parse_url($referer, PHP_URL_QUERY), 2);
      }
      global $user;
      $dest = urlencode('user/' . $user->uid . '/rpx');
      if (!variable_get('clean_url', FALSE)) {
        $dest = urlencode('?q=') . $dest;
      }
      $_SESSION['rpx_overlay_uri'] = $referer . '#overlay=' . $dest;
    }
  }

  $token_url = _rpx_token_url(array('add_to_account' => 'true'));

  $links['add_id'] = array(
    'title' => t('Add linked account'),
    'href' => $sign_in_url,
    'query' => array('token_url' => $token_url),
    'attributes' => array('class' => 'rpxnow', 'onclick' => 'return false;'),
  );

  $build['add_link'] = array(
    '#theme' => 'links',
    '#links' => $links,
    '#attributes' => array('class' => array('action-links')),
  );

  $build['rpx_table'] = array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => $rows,
  );

  if (isset($_SESSION['rpx_overlay_message'])) {
    drupal_set_message($_SESSION['rpx_overlay_message']['text'], $_SESSION['rpx_overlay_message']['type']);
    unset($_SESSION['rpx_overlay_message']);
  }

  return $build;
}

/**
 * Menu callback; Delete the specified Engage identity from the system.
 */
function rpx_user_delete_form($form, $form_state, $account, $aid = 0) {
  $authname = db_query("SELECT authname FROM {authmap} WHERE uid = :uid AND aid = :aid AND module = 'rpx_core'", array(
    ':uid' => $account->uid,
    ':aid' => $aid,
  ))
  ->fetchField();

  if (!$authname) {
    drupal_not_found();
    drupal_exit();
  }

  $provider = _rpx_get_identity_provider($authname);
  $username = check_plain(format_username($account));

  return confirm_form(array(), t('Are you sure you want to remove this linked %provider account for %user?', array('%provider' => $provider['title'], '%user' => $username)), 'user/' . $account->uid . '/rpx', '');
}

/**
 * Handle identity deletion by removing {authmap} entry.
 */
function rpx_user_delete_form_submit($form, &$form_state) {
  $uid = $form_state['build_info']['args'][0]->uid;
  $aid = $form_state['build_info']['args'][1];

  $rpxid = db_query('SELECT authname FROM {authmap} WHERE uid = :uid AND aid = :aid AND module = :module', array(
    ':uid' => $uid,
    ':aid' => $aid,
    ':module' => 'rpx_core',
  ))->fetchField();

  $query = db_delete('authmap')
    ->condition('uid', $uid)
    ->condition('aid', $form_state['build_info']['args'][1])
    ->condition('module', 'rpx_core')
    ->execute();

  if ($query) {
    $provider = _rpx_get_identity_provider($rpxid);
    drupal_set_message(t('The linked %provider account has been successfully removed.', array('%provider' => $provider['title'])));

    // Let other modules (e.g. rpx_rules) know that a linked account has been
    // removed.
    $user = user_load($uid);
    $account = array(
      'user' => $user,
      'id' => $rpxid,
    );
    module_invoke_all('rpx_linked_account', 'deleted', $account);
  }

  // Let rpx_user_identities() know that the delete confirm_form has been
  // used (so that it can work around http://drupal.org/node/1029458#comment-4055748).
  $_SESSION['rpx_linked_account_deleted'] = TRUE;

  $form_state['redirect'] = 'user/' . $uid . '/rpx';
}

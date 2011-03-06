<?php
// $Id: rpx_ui.admin.inc,v 1.16 2011/02/07 08:54:54 geokat Exp $

/**
 * @file
 * Admin page callback file for the rpx_ui module.
 */

/**
 * Menu callback: Generate a form to configure Engage settings.
 *
 * @ingroup forms
 * @see rpx_admin_settings_validate()
 * @see rpx_admin_settings_submit()
 */
function rpx_admin_settings($form, &$form_state) {
  $form['settings'] = array(
    '#type' => 'vertical_tabs',
  );
  $group = 'setup';
  $get_engage = '';
  if (!variable_get('rpx_apikey', FALSE)) {
    $get_engage = t('To create a Janrain Engage account, visit <a href="@get_engage" target="_blank">Get Janrain Engage</a>.  ', array('@get_engage' => 'http://www.janrain.com/products/engage/get-janrain-engage'));
  }
  $form[$group] = array(
    '#type' => 'fieldset',
    '#title' => t('SETUP'),
    '#group' => 'settings',
    '#description' => $get_engage . t('To make changes to your existing Janrain Engage account and integration with this site visit <a href="@account_link" target="_blank">@account_link</a>.',  array('@account_link' => 'https://rpxnow.com/account')),
  );
  $form[$group]['rpx_apikey'] = array(
    '#type' => 'textfield',
    '#title' => t('API Key'),
    '#default_value' => variable_get('rpx_apikey', ''),
    '#description' => t('If you don\'t have a key, please visit <a href="@get_engage" target="_blank">Get Janrain Engage</a> to get one.', array('@get_engage' => 'http://www.janrain.com/products/engage/get-janrain-engage')),
  );
  $form[$group]['rpx_realm'] = array(
    '#type' => 'item',
    '#title' => t('Engage Realm'),
    '#markup' => variable_get('rpx_realm', ''),
    '#description' => t('The Engage realm for this site (set automatically based on your API Key).'),
  );
  $form[$group]['rpx_admin_url_hidden'] = array(
    '#type' => 'item',
    '#title' => t('Engage Admin URL'),
    '#markup' => t('<a href="@account_stats" target="_blank">@account_stats</a>', array('@account_stats' => variable_get('rpx_admin_url', ''))),
    '#description' => t('The Engage admin URL (Set automatically based on your API Key).'),
    '#attributes' => array('disabled' => 'disabled')
  );
  $form[$group]['rpx_signin_string'] = array(
    '#type' => 'textfield',
    '#title' => t('Engage Signin Label'),
    '#default_value' => variable_get('rpx_signin_string', RPX_SIGNIN_STRING),
    '#description' => t('The text that will appear above the icons on the login page.'),
  );
  $form[$group]['rpx_accounts_string'] = array(
    '#type' => 'textfield',
    '#title' => t('Engage Identities tab name'),
    '#default_value' => variable_get('rpx_accounts_string', RPX_ACCOUNTS_STRING),
    '#description' => t('The name of the tab in the user profile where users can manage their 3rd party identities.'),
  );
  $form[$group]['rpx_user_help_text'] = array(
    '#type' => 'textarea',
    '#title' => t('Engage Identities tab help text'),
      '#default_value' => _rpx_user_help_text(),
    '#description' => t('The text in the Engage Identities tab that explains Janrain Engage to users.'),
    '#rows' => 10,
  );

  $providers = rpx_get_enabled_provider_array();
  if (count($providers)) {
    $group = 'providers';
    $setup_url = variable_get('rpx_admin_url', '');
    $setup_url = $setup_url ? $setup_url . '/setup_providers' : 'https://rpxnow.com/account';
    $form[$group] = array(
      '#type' => 'fieldset',
      '#title' => t('IDENTITY PROVIDERS'),
      '#group' => 'settings',
      '#description' => t('<a href="@setup_url" target="_blank">Configure/add providers here</a>. Don\'t forget to save this settings page afterwards to update your list of enabled providers from the Engage servers.', array('@setup_url' => $setup_url)),
    );
    $form[$group]['list'] = array(
      '#type' => 'item',
      '#markup' => theme('item_list', array('items' => $providers))
    );
  }

  $group = 'authentication';
  $form[$group] = array(
    '#type' => 'fieldset',
    '#title' => t('AUTHENTICATION'),
    '#group' => 'settings',
  );
  $form[$group]['rpx_attach_login_form'] = array(
    '#type' => 'checkbox',
    '#title' => t('Attach Engage Sign-In link to login forms'),
    '#default_value' => variable_get('rpx_attach_login_form', 0) ? 1 : 0,
    '#description' => t('Attach the Engage Sign-In link to regular Drupal login block and the login page.'),
  );

  $form[$group]['rpx_force_registration_form'] = array(
    '#type' => 'checkbox',
    '#title' => t('Force registration form'),
    '#default_value' => variable_get('rpx_force_registration_form', 0) ? 1 : 0,
    '#description' => t('Always show registration form on Engage login, in case there are additional required fields to be captured'),
  );

  $form[$group]['rpx_extended_authinfo'] = array(
    '#type' => 'checkbox',
    '#title' => t('Request extended profile data.'),
    '#default_value' => variable_get('rpx_extended_authinfo', 0) ? 1 : 0,
    '#description' => t('If checked, extended profile data will be requested from Engage at each user signin. This feature is available to <a href="@get_engage" target="_blank">Plus</a> and <a href="@get_engage" target="_blank">Pro</a> Janrain customers, and ignored for Basic accounts.', array('@get_engage' => url('http://www.janrain.com/products/engage/get-janrain-engage'))),
  );

  $form[$group]['rpx_import_profile_photo'] = array(
    '#type' => 'checkbox',
    '#title' => t('Import Profile Picture'),
    '#default_value' => variable_get('rpx_import_profile_photo', 0) ? 1 : 0,
    '#description' => t('If checked, the user\'s profile picture will be automatically imported as their Drupal profile picture.'),
  );

  // is this needed in Drupal 7?
  $form[$group]['rpx_javascript_global'] = array(
    '#type' => 'checkbox',
    '#title' => t('Force Engage javascript on every page'),
    '#default_value' => variable_get('rpx_javascript_global', 0) ? 1 : 0,
    '#description' => t('Certain caching setups may cause some Engage login links not to work. If that is the case, try checking this option to resolve the issue. You may also need to clear the cache on the <a href="!performance_link">Performance</a> page.', array('!performance_link' => url('admin/config/development/performance'))),
  );
  if (module_exists('openid')) {
    $form[$group]['rpx_openid_override'] = array(
      '#type' => 'checkbox',
      '#title' => t('Allow Engage to override OpenID'),
      '#default_value' => variable_get('rpx_openid_override', 0) ? 1 : 0,
      '#description' => t('Allow the Engage login link to replace the core OpenID module login link.'),
    );
  }

  $group = 'social';
  $rpx_admin_url = variable_get('rpx_admin_url', '');
  $form[$group] = array(
    '#type' => 'fieldset',
    '#title' => t('SOCIAL SHARING'),
    '#group' => 'settings',
    '#description' => t('To configure social sharing providers visit <a href="@social_link" target="_blank">Janrain\'s rpxnow site</a>.', array('@social_link' => $rpx_admin_url ? $rpx_admin_url . '/social_publishing_2' : 'https://rpxnow.com')),
  );
  $form[$group]['rpx_comment_popup_social_at_once'] = array(
    '#type' => 'checkbox',
    '#title' => t('Pop up the social sharing widget.'),
    '#default_value' => variable_get('rpx_comment_popup_social_at_once', 0) ? 1 : 0,
    '#description' => t('Pop up the social sharing widget when a comment is added.'),
  );
  $form[$group]['rpx_attach_share_link_to_comments'] = array(
    '#type' => 'checkbox',
    '#title' => t('Attach "share" link to comments'),
    '#default_value' => variable_get('rpx_attach_share_link_to_comments', 0) ? 1 : 0,
    '#description' => t('Display Engage social sharing link for comments.'),
  );
  $form[$group]['rpx_attach_share_link_node_types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Node types'),
    '#description' => t('Display "share" button for these node types.'),
    '#default_value' => variable_get('rpx_attach_share_link_node_types', array('page', 'article')),
    '#options' => node_type_get_names(),
  );
  $form[$group]['rpx_attach_share_link_to_teasers'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display in node TEASERS'),
    '#default_value' => variable_get('rpx_attach_share_link_to_teasers', 1),
    '#description' => t('Display "share" button in node teasers.'),
  );
  $form[$group]['rpx_attach_share_link_to_nodelink'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display in LINK section'),
    '#default_value' => variable_get('rpx_attach_share_link_to_nodelink', 1),
    '#description' => t('Display "share" button in LINK section of node pages.'),
  );
  $form[$group]['rpx_attach_share_link_to_nodecont'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display in CONTENT section'),
    '#default_value' => variable_get('rpx_attach_share_link_to_nodecont', 0),
    '#description' => t('Display "share" button in CONTENT section of node pages.'),
  );
  $form[$group]['rpx_attach_share_link_to_nodecont_weight'] = array(
    '#type' => 'weight',
    '#title' => t('Weight'),
    '#default_value' => variable_get('rpx_attach_share_link_to_nodecont_weight', 40),
    '#delta' => 50,
    '#description' => t('Weight value for the "share" button displayed in CONTENT section'),
  );

  $group = 'email';
  $form[$group] = array(
    '#type' => 'fieldset',
    '#title' => t('VERIFICATION E-MAIL'),
    '#group' => 'settings',
  );
  $email_token_help = t('Available variables are: [site:name], [site:url], [user:name], [user:mail], [site:login-url], [site:url-brief], [user:edit-url], [user:one-time-login-url], [user:cancel-url].');
  $form[$group]['verify_rpx'] = array(
    '#type' => 'fieldset',
    '#title' => t('Verification Email'),
    '#description' => '<p>' . t('Some Engage providers do not provide verified email addresses. If your <a href="@account_settings">account settings</a> require emails be verified, an email will be dispatched to these users in order to verify their email address before they can login.', array('@account_settings' => url('admin/config/people/accounts'))) . ' ' . $email_token_help . '</p>',
  );
  $form[$group]['verify_rpx']['rpx_bypass_email_verification'] = array(
    '#type' => 'checkbox',
    '#title' => t('Bypass email verification for Engage registration'),
    '#default_value' => variable_get('rpx_bypass_email_verification', 0) ? 1 : 0,
    '#description' => t('If checked, users who register using Engage will not be required verify their email address even if their provider does not provide a verified email.'),
  );
  $form[$group]['verify_rpx']['rpx_mail_rpx_confirm_email_subject'] = array(
    '#type' => 'textfield',
    '#title' => t('Subject'),
    '#default_value' => _rpx_mail_text('rpx_confirm_email_subject', NULL, array(), FALSE),
    '#maxlength' => 180,
  );
  $form[$group]['verify_rpx']['rpx_mail_rpx_confirm_email_body'] = array(
    '#type' => 'textarea',
    '#title' => t('Body'),
    '#default_value' => _rpx_mail_text('rpx_confirm_email_body', NULL, array(), FALSE),
    '#rows' => 15,
  );

  $form['#submit'][] = 'rpx_admin_settings_submit';

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save configuration'),
  );

  $form['actions']['reset'] = array(
    '#type' => 'link',
    '#title' => t('Reset to defaults'),
    '#href' => 'admin/config/people/rpx/settings/reset',
  );

  return $form;
}

/**
 * Validate rpx_admin_settings submissions.
 */
function rpx_admin_settings_validate($form, &$form_state) {
  $api_key = $form_state['values']['rpx_apikey'];

  $lookup = RPX::lookup($api_key, 'apiKey');

  if (!$lookup) {
    form_set_error('', t('Error contacting Engage.  Please verify your internet connection and try again.'));
    return;
  }
  if ($lookup == 'No RP found') {
    form_set_error('rpx_apikey', t('Looks like your API key was incorrect.  Please verify the key and try again.'));
    return;
  }
  // pass the lookup results to rpx_admin_settings_submit()
  variable_set('rpx_lookup_results', $lookup);
}

/**
 * Submit handler to update Engage settings.
 *
 * @see rpx_admin_settings_form()
 * @see rpx_admin_settings_validate()
 */
function rpx_admin_settings_submit($form, &$form_state) {
  // Delete all lookup sourced variables.
  variable_del('rpx_realm');
  variable_del('rpx_realm_scheme');
  variable_del('rpx_enabled_providers');
  variable_del('rpx_app_id');
  variable_del('rpx_social_pub');
  variable_del('rpx_admin_url');

  $lookup = variable_get('rpx_lookup_results');
  variable_del('rpx_lookup_results');

  variable_set('rpx_apikey', $lookup['apiKey']);
  variable_set('rpx_realm', $lookup['realm']);
  variable_set('rpx_realm_scheme', $lookup['realmScheme']);
  variable_set('rpx_app_id', $lookup['appId']);
  variable_set('rpx_social_pub', $lookup['socialPub']);
  variable_set('rpx_admin_url', $lookup['adminUrl']);

  $providers = RPX::get_enabled_providers($lookup['realm'], $lookup['realmScheme']);
  variable_set('rpx_enabled_providers', $providers);

  form_state_values_clean($form_state);

  foreach ($form_state['values'] as $key => $value) {
    if (is_array($value) && isset($form_state['values']['array_filter'])) {
      $value = array_keys(array_filter($value));
    }
    variable_set($key, $value);
  }

  drupal_set_message(t('The configuration options have been saved.'));

  // Clear the cached pages and blocks.
  cache_clear_all();
  // Rebuild menu to show/hide the "Janrain Engage Identities" depending on the
  // API key settings.
  menu_rebuild();
}

/**
 * Menu callback; reset Engage module settings.
 */
function rpx_admin_reset_confirm ($form, &$form_state) {
  return confirm_form($form, t('Are you sure you want to reset the Janrain Engage configuration options to their default values?'), 'admin/config/people/rpx/settings/', t('Any customizations will be lost. This action cannot be undone.'), t('Reset'));
}

/**
 * Process settings reset form submissions.
 */
function rpx_admin_reset_confirm_submit($form, &$form_state) {
  variable_del('rpx_attach_login_form');
  variable_del('rpx_attach_share_link_to_comments');
  variable_del('rpx_attach_share_link_node_types');
  variable_del('rpx_attach_share_link_to_teasers');
  variable_del('rpx_attach_share_link_to_nodelink');
  variable_del('rpx_attach_share_link_to_nodecont');
  variable_del('rpx_attach_share_link_to_nodecont_weight');
  variable_del('rpx_comment_popup_social_at_once');
  variable_del('rpx_bypass_email_verification');
  variable_del('rpx_extended_authinfo');
  variable_del('rpx_force_registration_form');
  variable_del('rpx_import_profile_photo');
  variable_del('rpx_javascript_global');
  variable_del('rpx_openid_override');
  variable_del('rpx_signin_string');
  variable_del('rpx_accounts_string');
  variable_del('rpx_user_help_text');
  variable_del('rpx_mail_rpx_confirm_email_body');
  variable_del('rpx_mail_rpx_confirm_email_subject');

  drupal_set_message(t('The configuration options have been reset to their default values.'));
  $form_state['redirect'] = 'admin/config/people/rpx/settings/';
}

/**
 * Menu callback: Generate a form to configure Drupal profile to Engage mappings
 * used to pre-fill User, Profile and Profile2 fields with Engage data at signup.
 *
 * @ingroup forms
 * @see rpx_profile_settings_submit()
 */
function rpx_profile_settings($form, &$form_state) {
  $catalog = _rpx_drupal_field_catalog();

  $map = variable_get('rpx_profile_fields_map', array());
  $result = db_query("SELECT fid, title, path FROM {rpx_profile_field}", array());

  foreach ($result as $field) {
    $form[$field->fid] = array(
      'title' => array(
        '#type' => 'item',
        '#markup' => check_plain($field->title),
        '#description' => check_plain($field->path),
      ),
      'separator' => array(
        '#markup' => '=>'
      ),
      'field_set' => array(
        '#type' => 'select',
        '#title' => t('Fieldset'),
        '#title_display' => 'invisible',
        '#options' => _rpx_drupal_field_options($catalog, 'set'),
        '#empty_option' => t('- Select a fieldset -'),
        '#description' => t('Module or entity.'),
        '#attributes' => array('class' => array('field-set-select', 'fid-' . $field->fid)),
      ),
      'field_bundle' => array(
        '#type' => 'select',
        '#title' => t('Fieldset type'),
        '#title_display' => 'invisible',
        '#options' => _rpx_drupal_field_options($catalog, 'bundle'),
        '#empty_option' => t('- Select a type -'),
        '#description' => t('Fieldset type.'),
        '#attributes' => array('class' => array('field-bundle-select', 'fid-' . $field->fid)),
      ),
      'field' => array(
        '#type' => 'select',
        '#title' => t('Field'),
        '#title_display' => 'invisible',
        '#options' => $catalog,
        '#empty_option' => t('- Select a field -'),
        '#description' => t('Field.'),
        '#attributes' => array('class' => array('field-select', 'fid-' . $field->fid)),
      ),
      'edit' => array(
        '#type' => 'link',
        '#title' => t('edit'),
        '#href' => "admin/config/people/rpx/profile/edit/$field->fid",
      ),
      'delete' => array(
        '#type' => 'link',
        '#title' => t('delete'),
        '#href' => "admin/config/people/rpx/profile/delete/$field->fid",
      ),
    );
  }

  if (count($form)) {
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array('#type' => 'submit', '#value' => t('Save configuration'));
  }

  $form['#attached']['js'][] = drupal_get_path('module', 'rpx_ui') . '/rpx_ui.js';
  $form['#attached']['js'][] = array(
    'type' => 'setting',
    'data' => array('catalog' => $catalog, 'map' => $map),
  );
  $form['#tree'] = TRUE;

  return $form;
}

/**
 * Submit handler to update changed Drupal to Engage data mapping.
 *
 * @see rpx_profile_settings()
 */
function rpx_profile_settings_submit($form, &$form_state) {
  $values = $form_state['values'];
  $map = array();

  foreach (element_children($values) as $fid) {
    // All field mappings should include a bundle, except for legacy
    // (non-fieldable) entity fields.
    if (is_numeric($fid) && !empty($values[$fid]['field_set']) && (!empty($values[$fid]['field_bundle']) || $values[$fid]['field_set'] == 'profile') && !empty($values[$fid]['field'])) {
      $map[$fid] = array(
        'set' => $values[$fid]['field_set'],
        'bundle' => $values[$fid]['field_bundle'],
        'field' =>  $values[$fid]['field'],
      );
    }
  }
  variable_set('rpx_profile_fields_map', $map);
  drupal_set_message(t('Profile to Engage data mapping has been updated.'));
}

/**
 * Menu callback; deletes a Engage field from the Drupal to Engage mapping
 * configuration.
 */
function rpx_profile_field_delete($form, &$form_state, $fid = NULL) {
  $field = db_query("SELECT title FROM {rpx_profile_field} WHERE fid = :fid", array(':fid' => $fid))->fetchObject();
  if (!$field) {
    drupal_not_found();
    drupal_exit();
  }
  $form['fid'] = array('#type' => 'value', '#value' => $fid);
  $form['title'] = array('#type' => 'value', '#value' => $field->title);

  return confirm_form($form,
    t('Are you sure you want to delete the Engage field %field?', array('%field' => $field->title)), 'admin/config/people/rpx/profile',
    t('This action cannot be undone. The Drupal to Engage data mapping using this field will be deleted as well.'),
    t('Delete'), t('Cancel'));
}

/**
 * Process an Engage field delete form submission.
 */
function rpx_profile_field_delete_submit($form, &$form_state) {
  db_delete('rpx_profile_field')
    ->condition('fid', $form_state['values']['fid'])
    ->execute();

  // Remove the mapping (if any) that uses the field.
  $map = variable_get('rpx_profile_fields_map', array());
  unset($map[$form_state['values']['fid']]);
  variable_set('rpx_profile_fields_map', $map);

  cache_clear_all();

  drupal_set_message(t('The Engage field %field has been deleted.', array('%field' => $form_state['values']['title'])));
  watchdog('rpx_ui', 'Engage field %field deleted.', array('%field' => $form_state['values']['title']), WATCHDOG_NOTICE, l(t('view'), 'admin/config/people/rpx/profile'));

  $form_state['redirect'] = 'admin/config/people/rpx/profile';
  return;
}

/**
 * Menu callback: Generate a form to add/edit an Engage profile field.
 *
 * @ingroup forms
 * @see rpx_profile_field_form_validate()
 * @see rpx_profile_field_form_submit()
 */
function rpx_profile_field_form($form, &$form_state, $arg = NULL) {
  if (arg(5) == 'edit') {
    if (is_numeric($arg)) {
      $fid = $arg;

      $edit = db_query('SELECT * FROM {rpx_profile_field} WHERE fid = :fid', array('fid' => $fid))->fetchAssoc();

      if (!$edit) {
        drupal_not_found();
        return;
      }
      drupal_set_title(t('Edit %title Engage field', array('%title' => $edit['title'])), PASS_THROUGH);
      $form['fid'] = array('#type' => 'value',
        '#value' => $fid,
      );
    }
    else {
      drupal_not_found();
      return;
    }
  }
  else {
    drupal_set_title(t('Add new Engage field'), PASS_THROUGH);
    $edit = array();
  }
  $edit += array(
    'title' => '',
    'path' => '',
  );
  $form['title'] = array('#type' => 'textfield',
    '#title' => t('Title'),
    '#default_value' => $edit['title'],
    '#attributes' => array('class' => array('rpx-field-title-input')),
    '#description' => t('The title of the new field. The title will be shown in the mapping form next to the data path. An example title is "Verified email". '),
    '#required' => TRUE,
  );

  $form['path'] = array('#type' => 'textfield',
    '#title' => t('Engage data path'),
    '#default_value' => $edit['path'],
    '#attributes' => array('class' => array('rpx-path-input')),
    '#description' => t("The path to the data within the Engage authentication dataset, in a PHP-like array referencing notation.
An example path is <code>['profile']['verifiedEmail']</code> or <code>['merged_poco']['emails'][0]['value']</code>. You can use the <a href=\"@provider-configuration\" target=\"_blank\">provider configuration</a> and <a href=\"@signin-test\" target=\"_blank\">sign-in test</a> tools to configure the data to be returned by providers, and to see it in action.", array('@provider-configuration' => variable_get('rpx_admin_url', '') . '/providers', '@signin-test' => variable_get('rpx_admin_url', '') . '/test')),
    '#required' => TRUE,
  );

  $path_help = '<dl>';
  $path_help .= '<dt>' . t('You can choose the path here. Please note that not all providers return all of the data below, and some of the data is only available to <a href="@get_engage" target="_blank">Plus</a> and <a href="@get_engage" target="_blank">Pro</a> Janrain customers. You must enable extended profile data in the <a href="@settings" target="_blank">settings</a> if you want that data to be requested from Engage at user sign-in. For complete information about the user data returned by Engage see <a href="@auth_info_help" target="_blank">this</a>.', array('@get_engage' => url('http://www.janrain.com/products/engage/get-janrain-engage'), '@settings' => url('/admin/config/people/rpx'), '@auth_info_help' => 'https://rpxnow.com/docs#api_auth_info')) . '</dt>';
  $path_help .= '<dd>' . theme('rpx_path_tree') . '</dd>';
  $path_help .= '</dl>';
  $form['path_help'] = array(
    '#markup' => $path_help,
  );

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save field'),
  );

  return $form;
}

function _rpx_profile_path_validate_error() {
  form_set_error('path', t('The specified path is not valid. Please check that it does not contain spaces or any other special characters except brackets ([]) and apostrophe (\'), and it would make a valid PHP array reference if appended to an array variable.'));
}

/**
 * Validate rpx_profile_field_form submissions.
 */
function rpx_profile_field_form_validate($form, &$form_state) {
  // Validate the data path by first checking the characters and then by
  // constructing a (bogus) PHP array reference and evaluating it.
  if (preg_match("/[^\[\]a-zA-Z0-9_']/", $form_state['values']['path'])) {
    _rpx_profile_path_validate_error();
  }
  // Use isset() to avoid the "undefined index" notice.
$test = <<<EOT
  isset(\$test_array{$form_state['values']['path']});
  return TRUE;
EOT;
  if (!eval($test)) {
    _rpx_profile_path_validate_error();
  }

  $query = db_select('rpx_profile_field');
  $query->fields('rpx_profile_field', array('fid'));

  if (isset($form_state['values']['fid'])) {
    $query->condition('fid', $form_state['values']['fid'], '<>');
  }
  $query_path = clone $query;

  $title = $query
    ->condition('title', $form_state['values']['title'])
    ->execute()
    ->fetchField();
  if ($title) {
    form_set_error('title', t('The specified title is already in use.'));
  }
  $path = $query_path
    ->condition('path', $form_state['values']['path'])
    ->execute()
    ->fetchField();
  if ($path) {
    form_set_error('path', t('The specified path is already in use.'));
  }
}

/**
 * Process rpx_profile_field_form submissions.
 */
function rpx_profile_field_form_submit($form, &$form_state) {
  // Remove all elements that are not rpx_profile_field columns.
  $values = array_intersect_key($form_state['values'], array_flip(array('title', 'path')));
  if (!isset($form_state['values']['fid'])) {
    db_insert('rpx_profile_field')
      ->fields($values)
      ->execute();
    drupal_set_message(t('The field has been created.'));
    watchdog('rpx_ui', 'Engage profile field %field added with path %path.', array('%field' => $form_state['values']['title'], '%path' => $form_state['values']['path']), WATCHDOG_NOTICE, l(t('view'), 'admin/config/people/rpx/profile'));
  }
  else {
    db_update('rpx_profile_field')
      ->fields($values)
      ->condition('fid', $form_state['values']['fid'])
      ->execute();
    drupal_set_message(t('The field has been updated.'));
  }
  cache_clear_all();
  menu_rebuild();

  $form_state['redirect'] = 'admin/config/people/rpx/profile';
  return;
}

/**
 * Theme Engage field mapping form.
 *
 * @ingroup themeable
 */
function theme_rpx_profile_settings($variables) {
  $form = $variables['form'];

  $rows = array();
  foreach (element_children($form) as $key) {
    // Skip form control elements.
    if (array_key_exists('separator', $form[$key])) {
      $field = &$form[$key];

      // Add the row
      $row = array();
      $row[] = drupal_render($field['title']);
      $row[] = drupal_render($field['separator']);
      $row[] = drupal_render($field['field_set']);
      $row[] = drupal_render($field['field_bundle']);
      $row[] = drupal_render($field['field']);
      $row[] = drupal_render($field['edit']);
      $row[] = drupal_render($field['delete']);
      $rows[] = array('data' => $row);
    }
  }
  $header = array(t('Engage Data Field'), '');
  $header[] = array('data' => t('Drupal Profile Field'), 'colspan' => 3);
  $header[] = array('data' => t('Operations'), 'colspan' => 2);

  $output = theme('table', array('header' => $header, 'rows' => $rows));
  $output .= drupal_render_children($form);

  return $output;
}

/**
 * Theme a tree table.
 * Based on code from the Token module.
 *
 * @ingroup themeable
 */
function theme_rpx_path_tree_table($variables) {
  foreach ($variables['rows'] as &$row) {
    $row += array('class' => array());
    if (!empty($row['parent'])) {
      $row['class'][] = 'child-of-' . drupal_clean_css_identifier($row['parent']);
      unset($row['parent']);
    }
  }

  if (count($variables['rows'])) {
    drupal_add_library('rpx_ui', 'treeTable');
  }
  return theme('table', $variables);
}

/**
 * Provide a 'tree' display of RPX data paths.
 * Based on code from the Token module.
 *
 * @ingroup themeable
 */
function theme_rpx_path_tree($variables) {
  module_load_include('inc', 'rpx_ui', 'rpx_ui.paths');

  $header = array(
    t('Name'),
    t('Path'),
    t('Description'),
  );

  $rows = _rpx_paths();

  drupal_add_js(drupal_get_path('module', 'rpx_ui') . '/rpx_ui.js');
  drupal_add_css(drupal_get_path('module', 'rpx_ui') . '/rpx_ui.css');

  $table_options = array(
    'header' => $header,
    'rows' => $rows,
    'attributes' => array('class' => array('rpx-path-tree')),
    'empty' => t('No paths available.'),
  );
  $table_options['caption'] = t('Navigate to the path you want and click on it to insert it into the path field.');
  $table_options['attributes']['class'][] = 'rpx-path-click-insert';

  return theme('rpx_path_tree_table', $table_options);
}

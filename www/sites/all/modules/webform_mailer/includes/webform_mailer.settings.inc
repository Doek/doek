<?php

/**
 * @file
 * Provides interface and database handling for e-mail settings of a webform.
 *
 * @author Nathan Haug <nate@lullabot.com>
 */

/**
 * Overview form of all components for this webform.
 */
function webform_mailer_settings_form($form, $form_state, $node) {

  $options = array('1' => t('Enabled'), '0' => t('Disabled'));

  $form['nid'] = array(
    '#type' => 'value',
    '#value' => $node->nid,
  );

  /* Start Edit Form */
  $form['enabled'] = array(
    '#type' => 'fieldset',
    '#title' => t('Webform Mailer Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#weight' => -4,
  );

  $form['enabled']['enabled'] = array(
	'#type' => 'checkbox',
	'#title' => t('Enabled Webform Mailer'),
	'#description' => t('By enabling Webform Mailer, you will be sending out emails to the users'),
	'#options' => $options,
	'#default_value' => '1',
  );

  $form['enabled']['notify'] = array(
	'#type' => 'radios',
	'#title' => t('Enabled Webform Mailer Notifications'),
	'#description' => t('Notify the selected users that they have to submit a response to this webform'),
	'#options' => $options,
	'#default_value' => '0',
  );
  
  $form['reminders'] = array(
    '#type' => 'fieldset',
    '#title' => t('Webform Mailer Reminders'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#weight' => -4,
  );
  
  $form['reminders']['number'] = array(
	'#type' => 'textfield',
	'#title' => t('Reminders'),
	'#description' => t('Select how many reminders that shall be sent to the users. 0 to disable reminders'),
	'#default_value' => '2',
	'#maxlength' => 2,
  );
  $period = drupal_map_assoc(array(3600, 10800, 21600, 32400, 43200, 86400, 172800, 259200, 604800, 1209600, 2419200, 4838400, 9676800), 'format_interval');

  $form['reminders']['interval'] = array(
	'#type' => 'select',
	'#title' => t('Interval'),
	'#description' => t('Deside on the interval of how long there should go before the system sends out reminders.'),
	'#default_value' => variable_get('timer', 604800),
	'#options' => $period,
  );
  
  $form['submit'] = array(
	'#type' => 'submit',
	'#value' => t('Save configuration'),
	'#weight' => 300,
	);

  return $form;
}



/**
 * Submit handler for webform_configure_form().
 */
function webform_mailer_settings_form_submit($form, &$form_state) {

  //Get data from form. 
  $nid = $form_state['values']['nid'];

  $enabled = $form_state['values']['enabled'];
  $notify = $form_state['values']['notify'];
  $reminders = $form_state['values']['number'];
  $interval = $form_state['values']['interval'];

  $query = db_insert('webform_mailer')
	->fields(array(
	  'nid' => $nid,
	  'sendmail' => $enabled,
	  'notify' => $notify,
	  'reminders' => $reminders,
	  'reminders_time' => $interval,
	))->execute();


  drupal_set_message(t('The form settings have been updated.'));

}

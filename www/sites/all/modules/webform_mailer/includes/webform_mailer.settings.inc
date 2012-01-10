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

  $query = db_query("SELECT * FROM {webform_mailer} wm where wm.nid = :nid", array(":nid" => $node->nid));
  
  if($query->rowCount() == 1){
	$webform = $query->fetchAssoc();
  }else{
	$webform = array();
	$webform['notify'] = 0;
	$webform['notify_text'] = '';
	$webform['mail_from'] = '';
	$webform['reminders'] = 2;
	$webform['reminders_text'] = '';
	$wenform['reminders_time'] = 21600;
  }

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

  $form['enabled']['notify'] = array(
	'#type' => 'radios',
	'#title' => t('Enabled Webform Mailer Notifications'),
	'#description' => t('Notify the selected users that they have to submit a response to this webform'),
	'#options' => $options,
	'#default_value' => $webform['notify'],
  );
  $form['enabled']['notify_text'] = array(
	'#type' => 'textarea',
	'#title' => t('Notification Text'),
	'#description' => t('Write the text you wish to email to the users - You are able to use tokens'),
	'#default_value' => $webform['notify_text'],
  );
  $form['enabled']['from'] = array(
	'#type' => 'textfield',
	'#title' => t('From adress'),
	'#description' => t('The email that should send your emails'),
	'#default_value' => $webform['mail_from'],
	'#maxlength' => 255,
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
	'#default_value' => $webform['reminders'],
	'#maxlength' => 2,
  );
  $form['reminders']['reminders_text'] = array(
	'#type' => 'textarea',
	'#title' => t('Reminder Text'),
	'#description' => t('Write the text you wish to email to the users - You are able to use tokens'),
	'#default_value' => $webform['reminders_text'],
  );
  $period = drupal_map_assoc(array(3600, 10800, 21600, 32400, 43200, 86400, 172800, 259200, 604800, 1209600, 2419200, 4838400, 9676800), 'format_interval');

  $form['reminders']['interval'] = array(
	'#type' => 'select',
	'#title' => t('Interval'),
	'#description' => t('Deside on the interval of how long there should go before the system sends out reminders.'),
	'#default_value' => $webform['reminders_time'],
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
  $from = $form_state['values']['from'];
  $notify = $form_state['values']['notify'];
  $n_text = $form_state['values']['notify_text'];
  $r_text = $form_state['values']['reminders_text'];
  $reminders = $form_state['values']['number'];
  $interval = $form_state['values']['interval'];

  $exists = db_query('SELECT nid FROM {webform_mailer} WHERE nid = '.$nid);

  if($exists->rowCount() >= 1){
	$query= db_update('webform_mailer')
		->fields(array(
		  'notify' 	=> $notify,
		  'reminders'	=> $reminders,
		  'mail_from' => $from,
		  'notify_text' => $n_text,
		  'reminders_text' => $r_text,
		  'reminders_time' => $interval,
		))
		->condition('nid', $nid)
		->execute();
  }else{
	  $query = db_insert('webform_mailer')
		->fields(array(
		  'nid' => $nid,
		  'notify' => $notify,
		  'notify_text' => $n_text,
		  'mail_from' => $from,
		  'reminders' => $reminders,
		  'reminders_time' => $interval,
		  'reminders_text' => $r_text,
		))->execute();
  }
  drupal_set_message(t('The form settings have been updated.'));

}

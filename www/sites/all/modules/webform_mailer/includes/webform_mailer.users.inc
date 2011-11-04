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
function webform_mailer_users_form($form, $form_state, $node) {

  $form = array(
    '#tree' => TRUE,
    '#node' => $node,
    'components' => array(),
  );

  $form['nid'] = array(
    '#type' => 'value',
    '#value' => $node->nid,
  );

  $form['user'] = array(
    '#type' => 'fieldset',
    '#title' => t('Webform Mailer Users'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#weight' => -2,
  );
  $roles = user_roles();

  $form['user']['role'] = array(
	'#type' => 'checkboxes',
	'#options' => $roles,
	'#title' => t('User roles'),
	'#description' => t('Select the roles to send mails to'),
  );

  $form['submit'] = array(
	'#type' => 'submit',
	'#value' => t('submit'),
  );

   return $form;
}

function webform_mailer_users_form_submit($form, &$form_state){
	
	$roles = $form_state['values']['user']['role'];

	foreach($roles as $role){
		if($role != 0){
			$ex[] = $role;
		}
	}
	$roles = implode('-', $ex);
	$form_state['redirect'] = array('node/' .$form['#node']->nid . '/webform/users/'.$roles); ;
}


function webform_mailer_users_roles_form($form, $form_state, $node, $roles = array()){

  $form = array(
    '#tree' => TRUE,
    '#node' => $node,
    'components' => array(),
  );

  $form['nid'] = array(
    '#type' => 'value',
    '#value' => $node->nid,
  );

  $form['users'] = array(
    '#type' => 'fieldset',
    '#title' => t('Webform Mailer Users'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#weight' => -2,
  );

  $options = array();
  $roles_rid = array();;

  foreach($roles as $role){
	$roles_rid[] = $role->rid;
  }

  $result = db_query("SELECT DISTINCT u.uid, u.name, u.mail FROM users u INNER JOIN users_roles ur on u.uid = ur.uid WHERE ur.rid IN (".implode(',', $roles_rid).")");

  foreach($result as $user){
	$options[$user->uid] = $user->name;
  }

  $form['users']['user'] = array(
	'#type' => 'checkboxes',
	'#options' => $options,
	'#title' => t('User roles'),
	'#description' => t('Select the roles to send mails to'),
  );


  $form['submit'] = array(
	'#type' => 'submit',
	'#value' => t('submit'),
	);

  return $form;
}

function webform_mailer_users_roles_form_submit($form, &$form_state){

	$users = array_intersect(array_keys($form_state['values']['users']['user']),
                          array_values($form_state['values']['users']['user']));

	$count = 0;
	$nid = $form_state['values']['nid'];
	
	foreach($users as $user){
		$account = user_load($user, TRUE);
		$token = md5('doek12'.$account->name);

		$query = db_insert('webform_mailer_users')
		->fields(array(
		  'nid' => $nid,
		  'uid' => $user,
		  'token' => $token,
		))->execute();

		$count++;
	}
	
	drupal_set_message($count.t(' Users have been saved to db'));


}
?>

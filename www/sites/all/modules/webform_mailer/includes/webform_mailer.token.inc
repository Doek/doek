<?php
/**
 * @file
 * Provides interface for accepting a webform with a token. 
 *
 * @author Henrik Thorn
 */

function get_confirm($node, $token){
	drupal_set_title($node->title);

	$extra = array('nid' => $node->nid, 'token' => $token);

	$form = drupal_get_form('continue_form', $extra);

	$output = "<br />";

	$user = db_query("SELECT * FROM {webform_mailer_users} WHERE token = :token", array(':token' => $token))->fetchObject();	

	$output .= t('Welcome'). ' ' . $user->firstname .' '. $user->lastname;
	$output .= "<br /><br />";
	$output .= t('Please click continue to answer the survey');
	$output .= "<br /><br />";

	$output .= drupal_render($form);

	return $output;
}

function continue_form($form, &$form_state, $extra){
        $form['#attached']['library'][] = array('webform_mailer', 'admin');

	$form['nid'] = array(
		'#type' => 'value',
		'#value' => $extra['nid'],
		);	
	
	$form['token'] = array(
		'#type' => 'value',
		'#value' => $extra['token'],
		);	

        $form['submit'] = array(
                '#type' => 'submit',
                '#value' => t('Continue'),
        );

	return $form; 
}
function continue_form_submit($form, &$form_state){

	$nid = $form_state['values']['nid'];
	$token = $form_state['values']['token'];

        $query = db_update('webform_mailer_users')
                ->fields(array(
                  'status' => 'started'
                ))
                ->condition('nid', $nid)
                ->condition('token', $token)
		->execute();

	drupal_goto('node/'.$nid);

}

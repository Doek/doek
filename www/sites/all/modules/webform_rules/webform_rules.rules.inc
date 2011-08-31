<?php
// $Id: webform_rules.rules.inc,v 1.2 2010/11/26 22:49:41 stborchert Exp $

/**
 * @file
 * Functions for rules integration.
 */

/**
 * Implements of hook_rules_event_info().
 * @ingroup rules
 */
function webform_rules_rules_event_info() {
  return array(
    'webform_rules_submit_as_draft' => array(
      'label' => t('After a webform has been saved as draft'),
      'group' => t('Webform'),
      'variables' => _webform_rules_event_variables(),
    ),
    'webform_rules_submit' => array(
      'label' => t('After a webform has been submitted'),
      'group' => t('Webform'),
      'variables' => _webform_rules_event_variables(),
    ),
  );
}

/**
 * Helper function for event variables.
 *
 * @return
 *   All available variables for the rules events provided by webform_rules.
 */
function _webform_rules_event_variables() {
  return array(
    'user' => array(
      'type' => 'user',
      'label' => t('User, who submitted the webform'),
    ),
    'node' => array(
      'type' => 'node',
      'label' => t('The webform node'),
    ),
    'data' => array(
      'type' => 'data',
      'label' => t('The submitted webform data'),
    ),
  );
}

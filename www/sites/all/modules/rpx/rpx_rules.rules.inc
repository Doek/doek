<?php
// $Id: rpx_rules.rules.inc,v 1.2 2011/01/20 22:19:16 geokat Exp $

/**
 * @file
 * Hooks and callback functions for rules.module integration.
 */

/**
 * Implements hook_rules_event_info().
 */
function rpx_rules_rules_event_info() {
  return array(
    'rpx_account_add' => array(
      'label'  => t('Linked account was added'),
      'group' => t('Janrain Engage'),
      'variables'   => array(
        'rpx' => array(
          'type' => 'rpx',
          'label' => t('Janrain Engage linked account'),
        ),
      ),
    ),
    'rpx_account_delete' => array(
      'label'  => t('Linked account was deleted'),
      'group' => t('Janrain Engage'),
      'variables'   => array(
        'rpx' => array(
          'type' => 'rpx',
          'label' => t('Janrain Engage linked account.'),
        ),
      ),
    ),
  );
}

/**
 * Implements hook_rules_data_info().
 */
function rpx_rules_rules_data_info() {
  return array(
    'rpx' => array(
      'label' => 'RPX',
      'wrap' => TRUE,
      'property info' => _rpx_rules_rpx_properties(),
    ),
  );
}

/**
 * Returns properties of rpx data object.
 */
function _rpx_rules_rpx_properties() {
  return array(
    'user' => array(
      'label' => t('User'),
      'type' => 'user',
      'description' => t('The linked account owner'),
    ),
    'id' => array(
      'label' => t('Engage ID'),
      'type' => 'text',
      'description' => t('The Janrain Engage (3rd party) ID.'),
    ),
    'provider_machinename' => array(
      'label' => t('Provider\'s machine name'),
      'type' => 'text',
      'description' => t('The Janrain Engage identity provider\'s internal (machine) name.'),
    ),
    'provider_title' => array(
      'label' => t('Provider\'s title'),
      'type' => 'text',
      'description' => t('The Janrain Engage identity provider\'s title.'),
    ),
  );
}

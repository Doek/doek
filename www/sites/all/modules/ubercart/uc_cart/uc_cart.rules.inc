<?php
// $Id: uc_cart.rules.inc,v 1.1 2010/07/16 12:57:36 islandusurper Exp $

/**
 * @file
 * This file contains the Rules hooks and functions necessary to
 * make the cart related entity, conditions, events, and actions work.
 */

/******************************************************************************
 * Rules Hooks                                                                *
 ******************************************************************************/

/**
 * Implement hook_rules_event_info().
 */
function uc_cart_rules_event_info() {
  $events['uc_checkout_complete'] = array(
    'label' => t('Customer completes checkout'),
    'group' => t('Cart'),
    'variables' => array(
      'order' => array(
        'type' => 'uc_order',
        'label' => t('Order'),
      ),
    ),
  );

  return $events;
}


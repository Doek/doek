<?php
// $Id: uc_quote.rules_defaults.inc,v 1.1 2010/07/16 12:57:37 islandusurper Exp $

/**
 * Rules configurations for shipping modules.
 */

function uc_quote_default_rules_configuration() {
  $configs = array();
  $methods = module_invoke_all('uc_shipping_method');

  foreach ($methods as $method) {
    $set = rules_and(array(
      'order' => array('type' => 'uc_order', 'label' => t('Order')),
    ));
    $set->label = t('@method conditions', array('@method' => $method['title']));

    $configs['get_quote_from_' . $method['id']] = $set;
  }

  return $configs;
}

<?php
// $Id: uc_order_handler_field_order_actions.inc,v 1.1 2010/11/24 15:18:30 islandusurper Exp $

/**
 * @file
 * Views handler: Return actions for order id - by justindodge
 */

/**
 * Return actions for an order
 */
class uc_order_handler_field_order_actions extends views_handler_field {

  function render($values) {
    $order = uc_order_load($values->order_id);
    return uc_order_actions($order, true);
  }

  function query() {
    //do nada, this isn't a real db field, just one we're rendering
  }

}

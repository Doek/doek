<?php
// $Id: uc_order_handler_field_order_status.inc,v 1.1 2010/11/24 15:18:30 islandusurper Exp $

/**
 * @file
 * Views handler: Order status field.
 */

/**
 * Return a human readable text for order status to display in the View.
 */
class uc_order_handler_field_order_status extends views_handler_field {

  function render($values) {
    return check_plain($values->{$this->aliases['title']});
  }

}

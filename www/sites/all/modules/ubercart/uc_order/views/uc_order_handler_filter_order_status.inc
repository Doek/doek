<?php
// $Id: uc_order_handler_filter_order_status.inc,v 1.1 2010/11/24 15:18:30 islandusurper Exp $

/**
 * Filter by order status
 */
class uc_order_handler_filter_order_status extends views_handler_filter_in_operator {

  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = t('Order Status');
      $statuses =  uc_order_status_list();
      foreach ($statuses as $status => $name) {
        $options[$name['id']] = $name['title'];
      }
      $this->value_options = $options;
    }
  }

}


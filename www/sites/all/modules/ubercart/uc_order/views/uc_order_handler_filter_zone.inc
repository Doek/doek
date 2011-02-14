<?php
// $Id: uc_order_handler_filter_zone.inc,v 1.1 2010/11/24 15:18:30 islandusurper Exp $

/**
 * Filter by zone code
 */
class uc_order_handler_filter_zone extends views_handler_filter_in_operator {

  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = t('Zone');
      $this->value_options = uc_zone_option_list();
    }
  }

}

<?php
// $Id: uc_order_handler_filter_country.inc,v 1.1 2010/11/24 15:18:30 islandusurper Exp $

/**
 * Filter by country code
 */
class uc_order_handler_filter_country extends views_handler_filter_in_operator {

  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = t('Country');
      $this->value_options = uc_country_option_list();
    }
  }

}

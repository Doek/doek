<?php
// $Id: template.php,v 1.2.4.8 2010/10/19 22:41:25 jmburnz Exp $

/**
 * Override or insert variables into page templates.
 */
function adaptivetheme_admin_preprocess_page(&$vars) {
  global $user;
  $vars['datetime_rfc'] = '';
  $vars['datetime_iso'] = '';
  $vars['datetime_rfc'] = date("r" , time()); // RFC2822 date format
  $vars['datetime_iso'] = date("c" , time()); // ISO 8601 date format
}

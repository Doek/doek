<?php
// $Id: template.js.inc,v 1.1.2.4 2010/12/17 01:28:09 jmburnz Exp $

$path = drupal_get_path('theme', 'adaptivetheme') .'/js/';
$regions = array(
  'region-leaderboard',
  'region-header',
  'region-secondary-content',
  'region-highlighted',
  'region-content-aside',
  'region-tertiary-content',
  'region-footer'
);
foreach ($regions as $region) {
  $setting = theme_get_setting('equalize_'. $region);
  if ($setting == 1) {
    $active_regions[] = $region;
  }
}
if (!empty($active_regions)) {
  $active_regions_list = $active_regions;
  drupal_add_js(array('active_regions' => $active_regions_list),
    array(
      'type' => 'setting',
      'scope' => 'header',
    )
  );
  drupal_add_js($path .'equalize-blocks.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'weight' => 999,
      'group' => JS_THEME,
      'preprocess' => TRUE,
      'cache' => TRUE,
    )
  );
}
if (theme_get_setting('equal_heights_sidebars')) {
  drupal_add_js($path .'equalize-columns.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'weight' => 999,
      'group' => JS_THEME,
      'preprocess' => TRUE,
      'cache' => TRUE,
    )
  );
}
if (theme_get_setting('equal_heights_gpanels')) {
  drupal_add_js($path .'equalize-gpanels.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'weight' => 999,
      'group' => JS_THEME,
      'preprocess' => TRUE,
      'cache' => TRUE,
    )
  );
}

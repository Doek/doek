<?php

global $theme, $base_path, $base_url, $theme_path, $arctica_theme_path, $abs_arctica_theme_path, $files_path;
/* Store theme paths in php and javascript variables */
$arctica_theme_path = drupal_get_path('theme', 'arctica');
$abs_arctica_theme_path = $base_path . $arctica_theme_path;
$files_path = variable_get('file_public_path', conf_path() . '/files');

drupal_add_css($arctica_theme_path . '/styling/css/arctica.reset.css', array('weight' => 0));
drupal_add_css($arctica_theme_path . '/styling/css/arctica.base.css', array('weight' => CSS_THEME));

// include theme dependency reporter
require_once($arctica_theme_path . '/includes/theme-system-report.inc');
// include theme overrides
require_once($arctica_theme_path . '/includes/theme-overrides.inc');
// include theme functions
require_once($arctica_theme_path . '/includes/theme-functions.inc');
// include theme settings controller
require_once($arctica_theme_path . '/includes/theme-settings-controller.inc');


if (theme_get_setting('meta') == 'RESET') {
  $link = "<strong><a href=\"admin/appearance/settings/$theme\">Arctica Configurator</a></strong>";
  drupal_set_message(t('Please visit the !conf for !theme and save the form. Some settings need to be initialized.', array(
      '!conf' => $link,
      '!theme' => $theme,
    )), 'warning');
}

/**
 * Implements hook_preprocess().
 *
 * This function checks to see if a hook has a preprocess file associated with
 * it, and if so, loads it.
 *
 * @param $vars
 * @param $hook
 * @return Array
 */
  /* if you rename the theme you have to change the the name of this function and of the drupal_get_path parameter */
function arctica_preprocess(&$vars, $hook) {
  if (is_file(drupal_get_path('theme', 'arctica') . '/preprocess/preprocess-' . str_replace('_', '-', $hook) . '.inc')) {
    include('preprocess/preprocess-' . str_replace('_', '-', $hook) . '.inc');
  }
}

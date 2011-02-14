<?php
// $Id: template.php,v 1.8.2.5 2011/01/13 06:04:30 jmburnz Exp $

/**
 * @file template.php
 */

/**
 * Override or insert variables into the html template.
 */
function pixture_reloaded_process_html(&$vars) {
  // Hook into color module
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
  $classes = explode(' ', $vars['classes']);
  $classes[] = theme_get_setting('font_family');
  $classes[] = theme_get_setting('headings_font_family');
  $classes[] = theme_get_setting('font_size');
  $classes[] = theme_get_setting('box_shadows');
  $classes[] = theme_get_setting('corner_radius');
  if (theme_get_setting('headings_styles_caps') == 1) {
    $classes[] = 'hs-caps';
  }
  if (theme_get_setting('headings_styles_weight') == 1) {
    $classes[] = 'hs-fwn';
  }
  if (theme_get_setting('headings_styles_shadow') == 1) {
    $classes[] = 'hs-ts';
  }
  $vars['classes'] = trim(implode(' ', $classes));
}

/**
 * Override or insert variables into the page template.
 */
function pixture_reloaded_process_page(&$vars) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
}

/**
 * Override or insert variables into the block template.
 */
function pixture_reloaded_preprocess_block(&$vars) {
  if($vars['block']->region == 'menu_bar') {
    $vars['title_attributes_array']['class'][] = 'element-invisible';
  }
  if($vars['block']->module == 'superfish' || $vars['block']->module == 'nice_menu') {
    $vars['content_attributes_array']['class'][] = 'clearfix';
  }
}

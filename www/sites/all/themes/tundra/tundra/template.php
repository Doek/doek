<?php
global $theme, $base_path, $theme_path, $tundra_theme_path, $abs_tundra_theme_path, $files_path;
/* Store theme paths in php and javascript variables */
$tundra_theme_path = drupal_get_path('theme', 'tundra');
$abs_tundra_theme_path = $base_path . $tundra_theme_path;

/**
 * Load Features
 */
foreach (file_scan_directory($tundra_theme_path . '/features', '/controller.inc/i') as $file) {
  require_once($file->uri);
}

/**
 * Color module integration
 */

/**
 * Returns HTML for a theme's color form.
 * Removes the mini-preview
 * @todo rework and implement the fullsize live preview from tundra 1.0
 */
function tundra_color_scheme_form($variables) {
  $form = $variables['form'];

  $theme = $form['theme']['#value'];
  $info = $form['info']['#value'];
  $path = drupal_get_path('theme', $theme) . '/';

  $output  = '';
  $output .= '<div class="color-form clearfix">';
  // Color schemes
  $output .= drupal_render($form['scheme']);
  // Palette
  $output .= '<div id="palette" class="clearfix">';
  foreach (element_children($form['palette']) as $name) {
    $output .= drupal_render($form['palette'][$name]);
  }
  $output .= '</div>';
  // Preview
  $output .= drupal_render_children($form);
  // Close the wrapper div.
  $output .= '</div>';

  return $output;
}

function tundra_preprocess_html(&$vars) {
  /**
   * If a theme wants to use advanced backgrounds these must go into their own
   * tags since they will have to use IE proprietary filters in order to work in
   * IE LTE IE8. Setting IE filters on the body tags causes problems.
   */
  $vars['page_backgrounds'] = '';

  if (theme_get_setting('gradient_enable')) {
    $vars['page_backgrounds'] .= '<div class="bg-gradient"></div>';
  }

  if (theme_get_setting('bg_image_enable')) {
    $vars['page_backgrounds'] .= '<div class="bg-image"></div>';
  }
}

/**
 * Hook into the color module.
 */
function tundra_process_html(&$vars) {
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
  $vars['cond_scripts_bottom'] .= '<div style="display:none">sfy39587stf03</div>';
}

function tundra_process_page(&$vars) {
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
}

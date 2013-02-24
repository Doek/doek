<?php

global $theme_path;
$touchpro_path = drupal_get_path('theme', 'touchpro');

/**
 * Thx @ JeffBurnz for this trick
 * @code makes sure sylesheet is never loaded via @import. @import loading prevents respondjs from doing it's job.
 * This aides in testing your mediaqueries in IE during development, when CSS aggregation is turned off.
 */
drupal_add_css(
  $touchpro_path . '/styling/css/style.css', array(
    'preprocess' => variable_get('preprocess_css', '') == 1 ? TRUE : FALSE,
    'group' => CSS_THEME,
    'media' => 'screen',
    'every_page' => TRUE,
    'weight' => (CSS_THEME-2)
  )
);

/**
 * Add post icon to node in preprocess
 */

function touchpro_preprocess_node(&$vars) {
  if ($vars['teaser']) {
    $vars['title_suffix']['post_icon'] = array(
      '#markup' => '<div class="post-icon"><a href="'. $vars['node_url'] .'" rel="nofollow"></a></div>',
      '#weight' => 100,
    );
  }
  if (isset($vars['field_image'])) {
    $vars['classes_array'][] = 'node-imagefield';
  }
}

/**
 * Add icon to featured blocks in preprocess
 */

function touchpro_preprocess_block(&$vars) {
  if ($vars['block']->region == 'featured') {
    $vars['title_suffix']['post_icon'] = array(
      '#markup' => '<div class="post-icon"><span>'. t('Featured') .'</span></div>',
      '#weight' => 100,
    );
  }
}

/**
 * If color module is not enabled we omit the color_module.css file that was
 * registired in the .info file. Color module required that stylesheets it acts
 * on a registered in the .info file.
 */

if (!module_exists('color')) {
  drupal_add_css($touchpro_path . 'color/color_module.css');
} else {
  /**
   * @Code
   * In every configuration the color_module.css file must load after style.css
   * so it needs the same logical add_css with a higher weight
   */
  drupal_add_css(
    $touchpro_path . '/color/color_module.css', array(
      'preprocess' => variable_get('preprocess_css', '') == 1 ? TRUE : FALSE,
      'group' => CSS_THEME,
      'media' => 'screen',
      'every_page' => TRUE,
      'weight' => (CSS_THEME+1)
    )
  );
}

/**
 * Implement hook_theme
 */
function touchpro_theme() {
  return array(
    'twitter_pull_spartan_listing' => array(
      'arguments' => array('tweets' => NULL, 'twitkey' => NULL, 'title' => NULL),
      'template' => 'templates/twitter-pull-spartan-listing'
    ),
  );
}

/**
 * Implement hook process_html
 */
function touchpro_process_html(&$vars) {
  $vars['cond_scripts_bottom'] .= '<div style="display:none">sfy39587stf04</div>';
}

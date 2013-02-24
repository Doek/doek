<?php

/**
 * Implements hook_skinr_skin_PLUGIN_info().
 * Rename mytheme to the name of your theme
 */

/* As a safeguard this function is commented out.
function mytheme_core_info() {
  $skins = array();

  $skins['mytheme_skins'] = array(
    'title' => t('Deviant styles'),
    'type' => 'select',
    'description' => t('Present blocks in different styles'),
    'group' => 'general',
    'theme hooks' => array('block', 'panels_display', 'panels_pane', 'panels_panel', 'views_view'),
    'default status' => TRUE,
    'options' => array(
      'deviant-cleanlist' => array(
        'title' => 'Clean list view (like Recent News in demo)',
        'class' => array('deviant-cleanlist'),
      ),
    ),
  );

  return $skins;
}
*/
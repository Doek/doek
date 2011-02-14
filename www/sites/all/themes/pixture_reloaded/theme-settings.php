<?php
// $Id: theme-settings.php,v 1.5.2.5 2011/01/13 06:04:30 jmburnz Exp $

/**
 * @file theme-settings.php
 */
/**
 * Implements hook_form_system_theme_settings_alter().
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function pixture_reloaded_form_system_theme_settings_alter(&$form, &$form_state)  {

  // Create the form using Forms API: http://api.drupal.org/api/7
  if (theme_get_setting('enable_styles') == 'on') {
    $form['styles'] = array(
      '#type' => 'fieldset',
      '#title' => t('Style settings'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    );
    $form['styles']['font'] = array(
      '#type' => 'fieldset',
      '#title' => t('Font and Headings settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['styles']['font']['font_family'] = array(
      '#type' => 'select',
      '#title' => t('Font family'),
      '#default_value' => theme_get_setting('font_family'),
      '#options' => array(
        'ff-sss' => t('Helvetica Nueue, Trebuchet MS, Arial, Nimbus Sans L, FreeSans, sans-serif'),
        'ff-ssl' => t('Verdana, Geneva, Arial, Helvetica, sans-serif'),
        'ff-a'   => t('Arial, Helvetica, sans-serif'),
        'ff-ss'  => t('Garamond, Perpetua, Nimbus Roman No9 L, Times New Roman, serif'),
        'ff-sl'  => t('Baskerville, Georgia, Palatino, Palatino Linotype, Book Antiqua, URW Palladio L, serif'),
        'ff-m'   => t('Myriad Pro, Myriad, Arial, Helvetica, sans-serif'),
        'ff-l'   => t('Lucida Sans, Lucida Grande, Lucida Sans Unicode, Verdana, Geneva, sans-serif'),
      ),
    );
    $form['styles']['font']['headings_font_family'] = array(
      '#type' => 'select',
      '#title' => t('Headings Font family'),
      '#default_value' => theme_get_setting('headings_font_family'),
      '#options' => array(
        'hff-sss' => t('Helvetica Nueue, Trebuchet MS, Arial, Nimbus Sans L, FreeSans, sans-serif'),
        'hff-ssl' => t('Verdana, Geneva, Arial, Helvetica, sans-serif'),
        'hff-a'   => t('Arial, Helvetica, sans-serif'),
        'hff-ss'  => t('Garamond, Perpetua, Nimbus Roman No9 L, Times New Roman, serif'),
        'hff-sl'  => t('Baskerville, Georgia, Palatino, Palatino Linotype, Book Antiqua, URW Palladio L, serif'),
        'hff-m'   => t('Myriad Pro, Myriad, Arial, Helvetica, sans-serif'),
        'hff-l'   => t('Lucida Sans, Lucida Grande, Lucida Sans Unicode, Verdana, Geneva, sans-serif'),
      ),
    );
    $form['styles']['font']['font_size'] = array(
      '#type' => 'select',
      '#title' => t('Base Font Size'),
      '#default_value' => theme_get_setting('font_size'),
      '#description' => t('This sets a base font-size on the body element - all text will scale relative to this value.'),
      '#options' => array(
        'fs-10' => t('0.833em'),
        'fs-11' => t('0.917em'),
        'fs-12' => t('1em'),
        'fs-13' => t('1.083em'),
        'fs-14' => t('1.167em'),
        'fs-15' => t('1.25em'),
        'fs-16' => t('1.333em'),
      ),
    );
    $form['styles']['font']['headings_styles'] = array(
      '#type' => 'fieldset',
      '#title' => t('Heading Styles'),
      '#description' => t('Add extra styles to headings. Shadows ony work for CSS3 capable browsers such as Firefox, Safari, IE9 etc.'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    );
    $form['styles']['font']['headings_styles']['headings_styles_caps'] = array(
      '#type' => 'checkbox',
      '#title' => t('All Caps'),
      '#default_value' => theme_get_setting('headings_styles_caps'),
    );
    $form['styles']['font']['headings_styles']['headings_styles_weight'] = array(
      '#type' => 'checkbox',
      '#title' => t('Font weight normal'),
      '#default_value' => theme_get_setting('headings_styles_weight'),
    );
    $form['styles']['font']['headings_styles']['headings_styles_shadow'] = array(
      '#type' => 'checkbox',
      '#title' => t('Text shadows'),
      '#default_value' => theme_get_setting('headings_styles_shadow'),
    );
    $form['styles']['corners'] = array(
      '#type' => 'fieldset',
      '#title' => t('Rounded corner settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['styles']['corners']['corner_radius'] = array(
      '#type' => 'select',
      '#title' => t('Corner radius'),
      '#default_value' => theme_get_setting('corner_radius'),
      '#description' => t('Change the corner radius for blocks, node teasers and comments.'),
      '#options' => array(
        'rc-0' => t('none'),
        'rc-4' => t('4px'),
        'rc-8' => t('8px'),
        'rc-12' => t('12px'),
      ),
    );
    $form['styles']['pagestyles'] = array(
      '#type' => 'fieldset',
      '#title' => t('Page style'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['styles']['pagestyles']['box_shadows'] = array(
      '#type' => 'radios',
      '#title' => t('Box shadow'),
      '#default_value' => theme_get_setting('box_shadows'),
      '#description' => t('Add styles for CSS3 browsers.'),
      '#options' => array(
        'bs-n' => t('None'),
        'bs-l' => t('Box shadow - light'),
        'bs-d' => t('Box shadow - dark'),
      ),
    );
  } // endif styles
}

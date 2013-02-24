<?php
/**
 * Implements hook_form_FORM_ID_alter().
 *
 * @param $form
 *   The form.
 * @param $form_state
 *   The form state.
 */
function bluemasters_form_system_theme_settings_alter(&$form, &$form_state) {

  $form['blue_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Bluemasters Theme Settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );
  $form['blue_settings']['slideshow'] = array(
    '#type' => 'fieldset',
    '#title' => t('Front Page Slideshow'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['blue_settings']['slideshow']['slideshow_display'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show slideshow'),
    '#default_value' => theme_get_setting('slideshow_display','bluemasters'),
    '#description'   => t("Check this option to show Slideshow in front page. Uncheck to hide."),
  );
    $form['blue_settings']['slideshow']['slide'] = array(
    '#markup' => t('You can change the image, description and URL of each slide in the following Slide Setting fieldsets.'),
  );
  
  
  $form['blue_settings']['slideshow']['slide1'] = array(
    '#type' => 'fieldset',
    '#title' => t('Slide 1'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['blue_settings']['slideshow']['slide1']['slide1_desc'] = array(
    '#type' => 'textfield',
    '#title' => t('Slide Description'),
    '#default_value' => theme_get_setting('slide1_desc','bluemasters'),
  );
  $form['blue_settings']['slideshow']['slide1']['slide1_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Slide URL'),
    '#default_value' => theme_get_setting('slide1_url','bluemasters'),
  );
  $form['blue_settings']['slideshow']['slide1']['slide1_image'] = array(
    '#type' => 'file',
    '#title' => t('Upload slide image'),
    '#description'   => t("Only include to overwrite existing image."),
  );
  
  
  $form['blue_settings']['slideshow']['slide2'] = array(
    '#type' => 'fieldset',
    '#title' => t('Slide 2'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['blue_settings']['slideshow']['slide2']['slide2_desc'] = array(
    '#type' => 'textfield',
    '#title' => t('Slide Description'),
    '#default_value' => theme_get_setting('slide2_desc','bluemasters'),
  );
  $form['blue_settings']['slideshow']['slide2']['slide2_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Slide URL'),
    '#default_value' => theme_get_setting('slide2_url','bluemasters'),
  );
  $form['blue_settings']['slideshow']['slide2']['slide2_image'] = array(
    '#type' => 'file',
    '#title' => t('Upload slide image'),
    '#description'   => t("Only include to overwrite existing image."),
  );
  
  
  $form['blue_settings']['slideshow']['slide3'] = array(
    '#type' => 'fieldset',
    '#title' => t('Slide 3'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['blue_settings']['slideshow']['slide3']['slide3_desc'] = array(
    '#type' => 'textfield',
    '#title' => t('Slide Description'),
    '#default_value' => theme_get_setting('slide3_desc','bluemasters'),
  );
  $form['blue_settings']['slideshow']['slide3']['slide3_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Slide URL'),
    '#default_value' => theme_get_setting('slide3_url','bluemasters'),
  );
  $form['blue_settings']['slideshow']['slide3']['slide3_image'] = array(
    '#type' => 'file',
    '#title' => t('Upload slide image'),
    '#description'   => t("Only include to overwrite existing image."),
  );
  
  $form['#submit'][] = 'bluemasters_settings_submit';
  $form['blue_settings']['slideshow']['slide1']['slide1_image']['#element_validate'][] = 'bluemasters_settings_submit';
  $form['blue_settings']['slideshow']['slide2']['slide2_image']['#element_validate'][] = 'bluemasters_settings_submit';
  $form['blue_settings']['slideshow']['slide3']['slide3_image']['#element_validate'][] = 'bluemasters_settings_submit';
}

/** * Capture theme settings submissions and update uploaded image */
function bluemasters_settings_submit($form, &$form_state) {
  // Check for a new uploaded file, and use that if available.
  if ($file = file_save_upload('slide1_image')) {
    $parts = pathinfo($file->filename);
    $filename = 'public://slide1_image.'. $parts['extension'];
    // The image was saved using file_save_upload() and was added to the
    // files table as a temporary file. We'll make a copy and let the garbage
    // collector delete the original upload.
    if (file_copy($file, $filename, FILE_EXISTS_RENAME)) {
      $_POST['slide1_image'] = $form_state['values']['slide1_image'] = $filename;
    }
  }
  if (file_save_upload('slide2_image')) {
    $parts = pathinfo($file->filename);
    $filename = 'public://slide2_image.'. $parts['extension'];
    if (file_copy($file, $filename, FILE_EXISTS_RENAME)) {
      $_POST['slide2_image'] = $form_state['values']['slide2_image'] = $filename;
    }
  }
  if (file_save_upload('slide3_image')) {
    $parts = pathinfo($file->filename);
    $filename = 'public://slide3_image.'. $parts['extension'];
    if (file_copy($file, $filename, FILE_EXISTS_RENAME)) {
      $_POST['slide3_image'] = $form_state['values']['slide3_image'] = $filename;
    }
  }
}
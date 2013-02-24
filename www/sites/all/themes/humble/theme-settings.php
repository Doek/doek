<?php

// $Id$
include_once 'template.php';

/**
 * Advanced theme settings.
 */
function humble_form_system_theme_settings_alter(&$form, $form_state) {

  $form['slider'] = array(
      '#type' => 'fieldset',
      '#title' => t('Slider managment'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
  );

  $form['slider']['slider_display'] = array(
      '#type' => 'select',
      '#title' => t('Show slider?'),
      '#options' => array('0' => 'No', '1' => 'Yes'),
      '#default_value' => theme_get_setting('slider_display'),
  );

  if (theme_get_setting('slider_display')) {
    $form['slider']['configuration'] = array(
        '#type' => 'fieldset',
        '#title' => t('Slider configuration'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
    );

    $form['slider']['configuration']['slider_use'] = array(
        '#type' => 'select',
        '#options' => array(
            1 => t('Polaroid'),
            0 => t('NivoSlider')
        ),
        '#title' => t('Select slider you want to use'),
        '#default_value' => theme_get_setting('slider_use'),
    );



    $slider_use = theme_get_setting('slider_use');

    if ($slider_use == 0) {
      $form['slider']['configuration']['nivo'] = array(
          '#type' => 'fieldset',
          '#title' => t('Nivoslider configuration'),
          '#collapsible' => TRUE,
          '#collapsed' => FALSE,
      );

      $form['slider']['configuration']['nivo']['nivo_effect'] = array(
          '#type' => 'textfield',
          '#title' => t('Effect'),
          '#description' => t('Specify sets like: \'random,fold,fade,sliceDown\''),
          '#default_value' => theme_get_setting('nivo_effect'),
      );
      $form['slider']['configuration']['nivo']['nivo_slices'] = array(
          '#type' => 'textfield',
          '#title' => t('Slices'),
          '#description' => t('For slice animations'),
          '#default_value' => theme_get_setting('nivo_slices'),
      );

      $form['slider']['configuration']['nivo']['nivo_boxCols'] = array(
          '#type' => 'textfield',
          '#title' => t('Box Cols'),
          '#description' => t('For box animations'),
          '#default_value' => theme_get_setting('nivo_boxCols'),
      );


      $form['slider']['configuration']['nivo']['nivo_boxRows'] = array(
          '#type' => 'textfield',
          '#title' => t('box Rows'),
          '#description' => t('For box animations'),
          '#default_value' => theme_get_setting('nivo_boxRows'),
      );


      $form['slider']['configuration']['nivo']['nivo_animSpeed'] = array(
          '#type' => 'textfield',
          '#title' => t('Animal Speed'),
          '#description' => t('Slide transition speed'),
          '#default_value' => theme_get_setting('nivo_animSpeed'),
      );



      $form['slider']['configuration']['nivo']['nivo_pauseTime'] = array(
          '#type' => 'textfield',
          '#title' => t('Pause Time'),
          '#description' => t('How long each slide will show'),
          '#default_value' => theme_get_setting('nivo_pauseTime'),
      );


      $form['slider']['configuration']['nivo']['nivo_directionNav'] = array(
          '#type' => 'select',
          '#options' => array('true' => 'Yes', 'false' => 'No'),
          '#title' => t('Direction Navigation'),
          '#description' => t('Next & Prev navigation'),
          '#default_value' => theme_get_setting('nivo_directionNav'),
      );


      $form['slider']['configuration']['nivo']['nivo_controlNav'] = array(
          '#type' => 'select',
          '#options' => array('true' => 'Yes', 'false' => 'No'),
          '#title' => t('Control Navigation'),
          '#description' => t('1,2,3... navigation'),
          '#default_value' => theme_get_setting('nivo_controlNav'),
      );
      $form['slider']['configuration']['nivo']['nivo_keyboardNav'] = array(
          '#type' => 'select',
          '#options' => array('true' => 'Yes', 'false' => 'No'),
          '#title' => t('keyboard Navigation'),
          '#default_value' => theme_get_setting('nivo_keyboardNav'),
      );

      $form['slider']['configuration']['nivo']['nivo_pauseOnHover'] = array(
          '#type' => 'select',
          '#options' => array('true' => 'Yes', 'false' => 'No'),
          '#title' => t('Pause on Hover'),
          '#default_value' => theme_get_setting('nivo_pauseOnHover'),
      );

      $form['slider']['configuration']['nivo']['nivo_captionOpacity'] = array(
          '#type' => 'textfield',
          '#title' => t('Caption Opacity, ex: 0.7'),
          '#default_value' => theme_get_setting('nivo_captionOpacity'),
      );
    }

    $slider_text = theme_get_setting('slider_left_text');


    $form['slider']['configuration']['slider_left_text'] = array(
        '#type' => 'textarea',
        '#title' => t('Slider text on left'),
        '#default_value' => $slider_text,
    );

    // Image upload section ======================================================
    $banners = humble_get_banners();

    $form['slider']['banner']['images'] = array(
        '#type' => 'vertical_tabs',
        '#title' => t('Banner images'),
        '#weight' => 2,
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
        '#tree' => TRUE,
    );

    $i = 0;
    foreach ($banners as $image_data) {
      $form['slider']['banner']['images'][$i] = array(
          '#type' => 'fieldset',
          '#title' => t('Image !number: !title', array('!number' => $i + 1, '!title' => $image_data['image_title'])),
          '#weight' => $i,
          '#collapsible' => TRUE,
          '#collapsed' => FALSE,
          '#tree' => TRUE,
          // Add image config form to $form
          'image' => _humble_banner_form($image_data),
      );

      $i++;
    }

    $form['slider']['banner']['image_upload'] = array(
        '#type' => 'file',
        '#title' => t('Upload a new image'),
        '#weight' => $i,
    );
    
     $form['#submit'][] = 'humble_settings_submit';
  }
 



  return $form;
}

/**
 * Save settings data.
 */
function humble_settings_submit($form, &$form_state) {
  $settings = array();

  // Update image field
  foreach ($form_state['input']['images'] as $image) {
    if (is_array($image)) {
      $image = $image['image'];

      if ($image['image_delete']) {
        // Delete banner file
        file_unmanaged_delete($image['image_path']);
        // Delete banner thumbnail file
        file_unmanaged_delete($image['image_thumb']);
      } else {
        // Update image
        $settings[] = $image;
      }
    }
  }

  // Check for a new uploaded file, and use that if available.
  if ($file = file_save_upload('image_upload')) {
    $file->status = FILE_STATUS_PERMANENT;
    if ($image = _humble_save_image($file)) {
      // Put new image into settings
      $settings[] = $image;
    }
  }

  // Save settings
  humble_set_banners($settings);
}

/**
 * Check if folder is available or create it.
 *
 * @param <string> $dir
 *    Folder to check
 */
function _humble_check_dir($dir) {
  // Normalize directory name
  $dir = file_stream_wrapper_uri_normalize($dir);

  // Create directory (if not exist)
  file_prepare_directory($dir, FILE_CREATE_DIRECTORY);
}

/**
 * Save file uploaded by user and generate setting to save.
 *
 * @param <file> $file
 *    File uploaded from user
 *
 * @param <string> $banner_folder
 *    Folder where save image
 *
 * @param <string> $banner_thumb_folder
 *    Folder where save image thumbnail
 *
 * @return <array>
 *    Array with file data.
 *    FALSE on error.
 */
function _humble_save_image($file, $banner_folder = 'public://banner/', $banner_thumb_folder = 'public://banner/thumb/') {
  // Check directory and create it (if not exist)
  _humble_check_dir($banner_folder);
  _humble_check_dir($banner_thumb_folder);

  $parts = pathinfo($file->filename);
  $destination = $banner_folder . $parts['basename'];
  $setting = array();

  $file->status = FILE_STATUS_PERMANENT;

  // Copy temporary image into banner folder
  if ($img = file_copy($file, $destination, FILE_EXISTS_REPLACE)) {
    // Generate image thumb
    $image = image_load($destination);
    $small_img = image_scale($image, 100, 59);
    $image->source = $banner_thumb_folder . $parts['basename'];
    image_save($image);

    // Set image info
    $setting['image_path'] = $destination;
    $setting['image_thumb'] = $image->source;
    $setting['image_title'] = '';
    $setting['image_description'] = '';
    $setting['image_url'] = '<front>';
    $setting['image_weight'] = 0;
    $setting['image_published'] = FALSE;
    $setting['image_visibility'] = '*';

    return $setting;
  }

  return FALSE;
}

/**
 * Provvide default installation settings for humble.
 */
function _humble_install() {
  // Deafault data
  $file = new stdClass;
  $banners = array();
  // Source base for images

  $src_base_path = drupal_get_path('theme', 'humble');
  $default_banners = theme_get_setting('default_banners');

  // Put all image as banners
  foreach ($default_banners as $i => $data) {
    $file->uri = $src_base_path . '/' . $data['image_path'];
    $file->filename = $file->uri;

    $banner = _humble_save_image($file);
    unset($data['image_path']);
    $banner = array_merge($banner, $data);
    $banners[$i] = $banner;
  }

  // Save banner data
  humble_set_banners($banners);

  // Flag theme is installed
  variable_set('theme_humble_first_install', FALSE);
}

/**
 * Generate form to mange banner informations
 *
 * @param <array> $image_data
 *    Array with image data
 *
 * @return <array>
 *    Form to manage image informations
 */
function _humble_banner_form($image_data) {
  $img_form = array();

  // Image preview
  $img_form['image_preview'] = array(
      '#markup' => theme('image', array('path' => $image_data['image_thumb'])),
  );

  // Image path
  $img_form['image_path'] = array(
      '#type' => 'hidden',
      '#value' => $image_data['image_path'],
  );

  // Thumbnail path
  $img_form['image_thumb'] = array(
      '#type' => 'hidden',
      '#value' => $image_data['image_thumb'],
  );

  // Image title
  $img_form['image_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $image_data['image_title'],
  );

  // Image description
  $img_form['image_description'] = array(
      '#type' => 'textarea',
      '#title' => t('Description'),
      '#default_value' => $image_data['image_description'],
  );

  // Link url
  $img_form['image_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Url'),
      '#default_value' => $image_data['image_url'],
  );

  // Image visibility
  $img_form['image_visibility'] = array(
      '#type' => 'textarea',
      '#title' => t('Visibility'),
      '#description' => t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are %blog for the blog page and %blog-wildcard for every personal blog. %front is the front page.", array('%blog' => 'blog', '%blog-wildcard' => 'blog/*', '%front' => '<front>')),
      '#default_value' => $image_data['image_visibility'],
  );

  // Image weight
  $img_form['image_weight'] = array(
      '#type' => 'weight',
      '#title' => t('Weight'),
      '#default_value' => $image_data['image_weight'],
  );

  // Image is published
  $img_form['image_published'] = array(
      '#type' => 'checkbox',
      '#title' => t('Published'),
      '#default_value' => $image_data['image_published'],
  );

  // Delete image
  $img_form['image_delete'] = array(
      '#type' => 'checkbox',
      '#title' => t('Delete image.'),
      '#default_value' => FALSE,
  );

  return $img_form;
}


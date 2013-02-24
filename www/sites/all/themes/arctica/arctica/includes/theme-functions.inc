<?php

/**
 * Custom theme settings might need a lot of CSS
 * So we put it in a file for optimzal performance
 */
function arctica_css_cache_build($theme) {
  global $files_path, $base_path;

  // We're being called as form submit callback:
  if (is_array($theme['theme_settings'])) {
    $theme = arg(count(arg()) - 1);
  }

  $arctica_css_file = $files_path . '/arctica-cache-' . $theme . '.css';
  //Construct CSS file:
  $CSS = '';
  // Load Sooper Features CSS
  foreach (file_scan_directory(drupal_get_path('theme', 'arctica') . '/features', '/css.inc/i') as $file) {
    include($file->uri);
  }
  if (drupal_get_path('theme', 'tundra')) {
    foreach (file_scan_directory(drupal_get_path('theme', 'tundra') . '/features', '/css.inc/i') as $file) {
      require_once($file->uri);
    }
  }

  $fh = fopen($arctica_css_file, 'w');
  if ($fh) {
    fwrite($fh, $CSS); // write css to file
    watchdog('Arctica', 'Arctica CSS file cache built for %theme', array('%theme' => $theme));
  }
  else {
    drupal_set_message(t('Cannot write theme-settings file, please check your file system. (See status report page)'), 'error');
  }

  fclose($fh);
  // If the CSS & JS aggregation are enabled we need to clear the caches
  drupal_clear_css_cache();
  drupal_clear_js_cache();
}

/**
 * Randomize the links using the server name so that we can get our SEO on
 * and optimize for more keywords.
 * If you don't like the attribution link you can easily turn it off in the
 * configurator under Drupal core options.
 */
function arctica_attribution_link() {
  $key = ord($_SERVER["SERVER_NAME"])%10;
  $links = array(
  '<a href="http://www.sooperthemes.com">Drupal Themes by sooperthemes</a>',
  '<a href="http://www.sooperthemes.com">Drupal templates</a> by sooperthemes',
  '<a href="http://www.sooperthemes.com">Professional Drupal Themes</a>',
  'Arctica: A <a href="http://www.sooperthemes.com">Premium Drupal theme</a> by sooperthemes',
  '<a href="http://www.sooperthemes.com">Drupal templates</a> by sooperthemes',
  'sooperthemes.com <a href="http://www.sooperthemes.com">Premium Drupal themes</a>',
  'Premium <a href="http://www.sooperthemes.com">Drupal themes</a> by sooperthemes.com',
  'Professional <a href="http://www.sooperthemes.com">Drupal theme</a> by sooperthemes',
  '<a href="http://www.sooperthemes.com">Premium Drupal theme</a> by www.sooperthemes.com',
  '<a href="http://www.sooperthemes.com">Premium Drupal themes</a>',
  );
  return $links[$key];
}

/**
 * @todo
 * Investigate if this is useful.
 * Using this it's possible to employ the sooper easy grid system
 * withouth having to use non-semantic classes. Hmm pros and cons..?
 * Pro: please devs who are obsessed with cleaning up code
 * Con: Adds complexity to the developer while not providing value to end-users
  function arctica_class2perc($class) {
    switch ($class) {
      case 'grid-100':
        return 100;
      case 'grid-3-4':
        return 75;
      case 'grid-2-3':
        return 66.667;
      case 'grid-1-2':
        return 50;
      case 'grid-1-3':
        return 33.333;
      case 'grid-1-4':
        return 25;
      case 'grid-1-5':
        return 20;
      case 'grid-1-6':
        return 16.667;
      case 'grid-1-7':
        return 14.143;
      case 'grid-1-8':
        return 12.125;
      case 'grid-1-phi':
        return 61.803;
      case 'grid-1-phi2':
        return 38.196;
      case 'grid-1-phi3':
        return 23.606;
    }
  }
 */
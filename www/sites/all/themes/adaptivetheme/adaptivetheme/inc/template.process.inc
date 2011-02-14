<?php
// $Id: template.process.inc,v 1.1.2.9 2011/01/13 03:34:03 jmburnz Exp $

// Preprocess variables for html.tpl.php.
// template_preprocess_html
function adaptivetheme_preprocess_html(&$vars) {
  if (module_exists('rdf')) {
    $vars['doctype'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML+RDFa 1.1//EN">' . "\n";
    $vars['rdf']->version = ' version="HTML+RDFa 1.1"';
    $vars['rdf']->namespaces = $vars['rdf_namespaces'];
    $vars['rdf']->profile = ' profile="' . $vars['grddl_profile'] . '"';
  } else {
    $vars['doctype'] = '<!DOCTYPE html>' . "\n";
    $vars['rdf']->version = '';
    $vars['rdf']->namespaces = '';
    $vars['rdf']->profile = '';
  }
  if (theme_get_setting('extra_page_classes')) {
    if (function_exists('locale')) {
      $vars['classes_array'][] = 'lang-'. $vars['language']->language;
    }
  }
  if (theme_get_setting('extra_page_classes') == 0) {
    foreach($vars['classes_array'] as $i =>$class) {
      if(preg_match('/page*/', $class)) {
        unset($vars['classes_array'][$i]);
      }
    }
  }
  if (theme_get_setting('layout_enable_settings') == 'on') {
    $vars['at_layout_width'] = 'div.container{width:'. theme_get_setting('layout_width') .';}';
    if (isset($vars['page']['sidebar_first']) || isset($vars['page']['sidebar_second'])) {
      $method = theme_get_setting('layout_method');
      $sidebar_last_width = theme_get_setting('layout_sidebar_last_width');
      $sidebar_first_width = theme_get_setting('layout_sidebar_first_width');
      if ($vars['language']->dir == 'ltr') {
        $left = 'left';
        $right = 'right';
      }
      if ($vars['language']->dir == 'rtl') {
        $left = 'right';
        $right = 'left';
      }
      $styles = array();
      if ($method == 0) {
        $push_right = $sidebar_last_width;
        $push_left  = $sidebar_first_width;
        $pull_right = $sidebar_last_width;
        $styles[] = '.two-sidebars .content-inner{margin-' . $left . ':' . $push_left . 'px;margin-' . $right . ':' . $push_right . 'px;}' . "\n";
        $styles[] = '.sidebar-first .content-inner{margin-' . $left . ':' . $push_left . 'px;margin-' . $right . ':0;}' . "\n";
        $styles[] = '.sidebar-second .content-inner{margin-' . $right . ':' . $push_right . 'px; margin-' . $left . ':0;}' . "\n";
        $styles[] = '.region-sidebar-first{width:' . $sidebar_first_width . 'px;margin-' . $left . ':-100%;}' . "\n";
        $styles[] = '.region-sidebar-second{width:' . $sidebar_last_width . 'px;margin-' . $left . ':-' . $pull_right . 'px;}' . "\n";
      }
      if ($method == 1) {
        $content_margin = $sidebar_last_width + $sidebar_first_width;
        $push_right     = $sidebar_last_width;
        $push_left      = $sidebar_first_width;
        $left_margin    = $sidebar_last_width + $sidebar_first_width;
        $right_margin   = $sidebar_last_width;
        $styles[] = '.two-sidebars .content-inner{margin-' . $right . ':' . $content_margin . 'px;margin-'. $left . ':0;}' . "\n";
        $styles[] = '.sidebar-first .content-inner{margin-' . $right . ':' . $push_left . 'px;margin-' . $left . ':0;}' . "\n";
        $styles[] = '.sidebar-second .content-inner{margin-' . $right . ':' . $push_right . 'px;margin-' . $left . ':0;}' . "\n";
        $styles[] = '.region-sidebar-first{width:' . $sidebar_first_width . 'px;margin-' . $left . ':-' . $left_margin . 'px;}' . "\n";
        $styles[] = '.region-sidebar-second{width:' . $sidebar_last_width . 'px;margin-' . $left . ':-' . $right_margin . 'px;}' . "\n";
        $styles[] = '.sidebar-first .region-sidebar-first{width:' . $sidebar_first_width . 'px;margin-' . $left . ':-' . $sidebar_first_width . 'px;}' . "\n";
      }
      if ($method == 2) {
        $content_margin = $sidebar_last_width + $sidebar_first_width;
        $left_margin    = $sidebar_first_width;
        $right_margin   = $sidebar_last_width;
        $push_right     = $sidebar_first_width;
        $styles[] = '.two-sidebars .content-inner{margin-' . $left . ':' . $content_margin . 'px;margin-' . $right . ':0;}' . "\n";
        $styles[] = '.sidebar-first .content-inner{margin-' . $left . ':' . $left_margin . 'px;margin-' . $right . ':0;}' . "\n";
        $styles[] = '.sidebar-second .content-inner{margin-' . $left . ':' . $right_margin . 'px;margin-' . $right . ':0;}' . "\n";
        $styles[] = '.region-sidebar-first{width:' . $sidebar_first_width . 'px; margin-' . $left . ': -100%;}' . "\n";
        $styles[] = '.region-sidebar-second{width:' . $sidebar_last_width . 'px; margin-' . $left . ': -100%;}' . "\n";
        $styles[] = '.two-sidebars .region-sidebar-second{width:' . $sidebar_last_width . 'px; position: relative;' . $left . ':' . $push_right . 'px;}' . "\n";
      }
      $vars['at_layout'] = implode('', $styles);
      $vars['layout'] = $vars['at_layout_width'] . $vars['at_layout'];
    }
    else {
      $vars['layout'] = $vars['at_layout_width'];
    }
    drupal_add_css($vars['layout'], array('group' => CSS_THEME, 'type' => 'inline'));
  }
}
// template_process_html
function adaptivetheme_process_html(&$vars) {
  $classes = explode(' ', $vars['classes']);
  if ($node = menu_get_object()) {
    $node_type_class = drupal_html_class('node-type-' . $node->type);
    if (in_array($node_type_class, $classes)) {
      theme_get_setting('extra_page_classes') == 0 ? '' : $classes = str_replace($node_type_class, '', $classes);
      $classes = str_replace('node-type-', 'page-type-', $classes);
    }
  }
  $vars['classes'] = trim(implode(' ', $classes));
}

// Preprocess variables for page.tpl.php.
// template_preprocess_page
function adaptivetheme_preprocess_page(&$vars) {
  $vars['logo_alt_text'] = check_plain(variable_get('site_name', '')) .' '. t('logo');
  $vars['logo_img'] = $vars['logo'] ? '<img src="'. check_url($vars['logo']) .'" alt="'. $vars['logo_alt_text'] .'"/>' : '';
  $vars['linked_site_logo'] = $vars['logo_img'] ? l($vars['logo_img'], '<front>', array(
    'attributes' => array(
      'rel' => 'home',
      'title' => t('Home page')
    ),
    'html' => TRUE,
    )
  ) : '';
  if (theme_get_setting('toggle_name') == FALSE) {
    $vars['visibility'] = 'element-invisible';
    $vars['hide_site_name'] = TRUE;
  }
  else {
    $vars['visibility'] = '';
    $vars['hide_site_name'] = FALSE;
  }
  $sitename = filter_xss_admin(variable_get('site_name', 'Drupal'));
  $vars['site_name'] = $sitename ? l($sitename, '<front>', array(
    'attributes' => array(
      'rel' => 'home',
      'title' => t('Home page')),
    )
  ) : '';
  if (isset($vars['main_menu'])) {
    $vars['primary_navigation'] = theme('links', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('menu', 'primary-menu', 'clearfix'),
       ),
     'heading' => array(
     'text' => t('Primary navigation'),
     'level' => 'h2',
       'class' => array('element-invisible'),
     )
    ));
  }
  if (!empty($vars['primary_navigation'])) {
    $vars['primary_navigation'] = _theme_menu_variables($vars['primary_navigation'], 'primary');
  }
  if (isset($vars['secondary_menu'])) {
    $vars['secondary_navigation'] = theme('links', array(
      'links' => $vars['secondary_menu'],
      'attributes' => array(
        'class' => array('menu', 'secondary-menu', 'clearfix'),
     ),
     'heading' => array(
     'text' => t('Secondary navigation'),
     'level' => 'h2',
       'class' => array('element-invisible'),
     )
    ));
  }
  if (!empty($vars['secondary_navigation'])) {
    $vars['secondary_navigation'] = _theme_menu_variables($vars['secondary_navigation'], 'secondary');
  }
  $vars['primary_local_tasks'] = menu_primary_local_tasks();
  $vars['secondary_local_tasks'] = menu_secondary_local_tasks();
  $vars['attribution'] = "<small id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></small>";
}

// Preprocess variables for node.tpl.php.
// template_preprocess_node
function adaptivetheme_preprocess_node(&$vars) {
  global $theme, $user;
  if (theme_get_setting('extra_article_classes')) {
    if (module_exists('translation')) {
      if ($vars['node']->language) {
        global $language;
        $vars['classes_array'][] = 'article-lang-'. $vars['node']->language;
      }
    }
  }
  if (theme_get_setting('extra_article_classes')) {
    $vars['classes_array'][] = $vars['zebra'];
  }
  if (!$vars['teaser'] && theme_get_setting('extra_article_classes')) {
    $vars['classes_array'][] = drupal_html_class('article-' . $vars['view_mode']);
  }
  $vars['title_attributes_array']['class'][] = 'article-title';
  $vars['content_attributes_array']['class'][] = 'article-content';
  $vars['datetime'] = format_date($vars['created'], 'custom', 'c');
  if (variable_get('node_submitted_' . $vars['node']->type, TRUE)) {
    $vars['submitted'] = t('Submitted by !username on !datetime',
      array(
        '!username' => $vars['name'],
        '!datetime' => '<time datetime="' . $vars['datetime'] . '" pubdate="pubdate">' . $vars['date'] . '</time>',
      )
    );
  }
  else {
    $vars['submitted'] = '';
  }
  $vars['unpublished'] = '';
  if (!$vars['status']) {
    $vars['unpublished'] = '<div class="unpublished">' . t('Unpublished') . '</div>';
  }
}
// template_process_node
function adaptivetheme_process_node(&$vars) {
  $classes = explode(' ', $vars['classes']);
  $classes = str_replace('node', 'article', $classes);
  if (in_array('article-' . $vars['node']->type, $classes)) {
    $classes = str_replace('article-' . $vars['node']->type, 'article-type-' . $vars['node']->type, $classes);
  }
  if (in_array('article-sticky', $classes)) {
    theme_get_setting('extra_article_classes') ? '' : $classes = str_replace('article-sticky', '', $classes);
  }
  if (in_array('article-promoted', $classes)) {
    theme_get_setting('extra_article_classes') ? '' : $classes = str_replace('article-promoted', '', $classes);
  }
  if (in_array('article-teaser', $classes)) {
    theme_get_setting('extra_article_classes') ? '' : $classes = str_replace('article-teaser', '', $classes);
  }
  if (in_array('article-preview', $classes)) {
    theme_get_setting('extra_article_classes') ? '' : $classes = str_replace('article-preview', '', $classes);
  }
  $vars['classes'] = trim(implode(' ', $classes));
}

// Preprocess variables for comment.tpl.php.
// template_preprocess_comment
function adaptivetheme_preprocess_comment(&$vars) {
  if (theme_get_setting('extra_comment_classes')) {
    $vars['classes_array'][] = $vars['zebra'];
  }
  $vars['title_attributes_array']['class'][] = 'comment-title';
  $vars['content_attributes_array']['class'][] = 'comment-content';
  $uri = entity_uri('comment', $vars['comment']);
  $uri['options'] += array('attributes' => array('rel' => 'bookmark'));
  $vars['title'] = l($vars['comment']->subject, $uri['path'], $uri['options']);
  $vars['permalink'] = l(t('Permalink'), $uri['path'], $uri['options']);
  $vars['created'] = '<span class="date-time permalink">' . l($vars['created'], $uri['path'], $uri['options']) . '</span>';
  $vars['datetime'] = format_date($vars['comment']->created, 'custom', 'c');
  $vars['unpublished'] = '';
  if ($vars['status'] == 'comment-unpublished') {
    $vars['unpublished'] = '<div class="unpublished">' . t('Unpublished') . '</div>';
  }
}
// template_process_comment
function adaptivetheme_process_comment(&$vars) {
  $classes = explode(' ', $vars['classes']);
  if (in_array('comment-by-anonymous', $classes)) {
    theme_get_setting('extra_comment_classes') ? '' : $classes = str_replace('comment-by-anonymous', '', $classes);
  }
  if (in_array('comment-by-node-author', $classes)) {
    $classes = str_replace('comment-by-node-author', 'comment-by-article-author', $classes);
    theme_get_setting('extra_comment_classes') ? '' : $classes = str_replace('comment-by-article-author', '', $classes);
  }
  if (in_array('comment-by-viewer', $classes)) {
    theme_get_setting('extra_comment_classes') ? '' : $classes = str_replace('comment-by-viewer', '', $classes);
  }
  if (in_array('comment-new', $classes)) {
    theme_get_setting('extra_comment_classes') ? '' : $classes = str_replace('comment-new', '', $classes);
  }
  $vars['classes'] = trim(implode(' ', $classes));
}

// Preprocess variables for block.tpl.php.
function adaptivetheme_preprocess_block(&$vars) {
  if (theme_get_setting('extra_block_classes')) {
    $vars['classes_array'][] = 'block-' . $vars['block_zebra'];
  }
  if (theme_get_setting('extra_block_classes')) {
    $vars['classes_array'][] = 'block-count-'. $vars['id'];
  }
  if (theme_get_setting('extra_block_classes')) {
    $vars['classes_array'][] = drupal_html_class('block-region-' . $vars['block']->region);
  }
  if (theme_get_setting('horizontal_login_block') && $vars['block']->module == 'user' && $vars['block']->delta == 'login') {
    $vars['classes_array'][] = 'lb-h';
  }
  if (theme_get_setting('slider_login_block') && $vars['block']->module == 'user' && $vars['block']->delta == 'login') {
    $vars['classes_array'][] = 'lb-s';
  }
  $vars['title_attributes_array']['class'][] = 'block-title';
  $vars['content_attributes_array']['class'][] = 'block-content';
  $nav_blocks = array('navigation', 'main-menu', 'management', 'user-menu', 'superfish', 'nice_menus');
  if (in_array($vars['block']->delta, $nav_blocks)) {
    $vars['theme_hook_suggestions'][] = 'block__menu';
  }
  if (in_array($vars['block']->delta, $nav_blocks) && $vars['block']->region == 'menu_bar') {
    $vars['theme_hook_suggestions'][] = 'block__menu_bar';
  }
  $nav_modules = array('superfish', 'nice_menus');
  if (in_array($vars['block']->module, $nav_modules)) {
    $vars['theme_hook_suggestions'][] = 'block__menu';
  }
  if ($vars['block']->region == 'menu_bar') {
    $vars['title_attributes_array']['class'][] = 'element-invisible';
  }
  // For example we can have templates such as block--header--search, or block--menu-bar--menu, which is cool...
  $vars['theme_hook_suggestions'][] = 'block__' . $vars['block']->region . '__' . $vars['block']->module;
}

// Changes the search form to use the "search" input element of HTML5 (from the Boron theme).
function adaptivetheme_preprocess_search_block_form(&$vars) {
  $vars['search_form'] = str_replace('type="text"', 'type="search"', $vars['search_form']);
}

// Modify search results based on theme settings.
function adaptivetheme_preprocess_search_result(&$vars) {
  $result = $vars['result'];
  $vars['url'] = check_url($result['link']);
  $vars['title'] = check_plain($result['title']);
  $vars['snippet'] = '';
  if (!empty($result['snippet']) && theme_get_setting('search_snippet')) {
    $vars['snippet'] = $result['snippet'];
  }
  $info = array();
  if (!empty($result['type']) && theme_get_setting('search_info_type')) {
    $info['type'] = check_plain($result['type']);
  }
  if (!empty($result['user']) && theme_get_setting('search_info_user')) {
    $info['user'] = $result['user'];
  }
  if (!empty($result['date']) && theme_get_setting('search_info_date')) {
    $info['date'] = format_date($result['date'], 'small');
  }
  if (isset($result['extra']) && is_array($result['extra'])) {
    if (!empty($result['extra'][0]) && theme_get_setting('search_info_comment')) {
      $info['comment'] = $result['extra'][0];
    }
    if (!empty($result['extra'][1]) && theme_get_setting('search_info_upload')) {
      $info['upload'] = $result['extra'][1];
    }
  }
  $vars['info_split'] = $info;
  $vars['info'] = implode(' - ', $info);
  $vars['info_separator'] = filter_xss(theme_get_setting('search_info_separator'));
}

// Preprocess variables for region.tpl.php.
function adaptivetheme_preprocess_region(&$vars, $hook) {
  if (strpos($vars['region'], 'sidebar_') === 0) {
    $vars['classes_array'][] = 'sidebar';
  }
}

// Process variables for aggregator-item.tpl.php.
function adaptivetheme_preprocess_aggregator_item(&$vars) {
  $item = $vars['item'];
  $vars['datetime'] = format_date($item->timestamp, 'custom', 'c');
}

// Process variables for maintenance-page.tpl.php.
function adaptivetheme_preprocess_maintenance_page(&$vars) {
  $vars['attribution'] = "<small id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></small>";
}

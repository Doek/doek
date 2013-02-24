<?php

function humble_main_menu_render_superfish() {

  drupal_add_js(path_to_theme() . '/js/superfish.js');
  $variables = array(
      'id' => 1,
      'menu_name' => 'main-menu',
      'mlid' => '0',
      'sfsettings' => array(
          'depth' => '-1',
          'type' => 'horizontal',
          'style' => 'none',
          'firstlast' => '1',
          'zebra' => 1,
          'dfirstlast' => 0,
          'dzebra' => 0,
          'itemcount' => 1,
          'itemcounter' => 1,
          'itemdepth' => 1,
          'ulclass' => '',
          'liclass' => '',
          'hlclass' => '',
          'wrapmul' => '',
          'wrapul' => '',
          'wraphl' => '',
          'wraphlt' => '',
          'linkdescription' => 0,
          'hldmenus' => '',
          'hldexclude' => '',
          'megamenu' => 0,
          'megamenu_depth' => '1',
          'megamenu_levels' => 2,
          'megamenu_exclude' => '',
      ),
  );
  global $user, $language;

  $id = $variables['id'];
  $menu_name = $variables['menu_name'];
  $mlid = $variables['mlid'];
  $sfsettings = $variables['sfsettings'];

  $cache_key = 'links:' . $menu_name . ':all-cid:' . $variables['mlid'] . ':' . $user->uid . ':' . $language->language;
  $cache = cache_get($cache_key, 'cache_menu');
  if ($cache && isset($cache->data)) {
    $cache = $cache->data;
  } else {
    // Start caching the menu.
    $cache = array();
    $cache['menu'] = menu_tree_all_data($menu_name);

    // For custom $menus and menus built all the way from the top-level we
    // don't need to "create" the specific sub-menu and we need to get the title
    // from the $menu_name since there is no "parent item" array.
    // Create the specific menu if we have a mlid.
    if (!empty($mlid)) {
      // Load the parent menu item.
      $item = menu_link_load($mlid);
      $cache['title'] = check_plain($item['title']);
      $cache['parent_depth'] = $item['depth'];
      // Narrow down the full menu to the specific sub-tree we need.
      for ($p = 1; $p < 10; $p++) {
        if ($sub_mlid = $item["p$p"]) {
          $subitem = menu_link_load($sub_mlid);
          $key = (50000 + $subitem['weight']) . ' ' . $subitem['title'] . ' ' . $subitem['mlid'];
          $cache['menu'] = (isset($cache['menu'][$key]['below'])) ? $cache['menu'][$key]['below'] : $cache['menu'];
        }
      }
    } else {
      $result = db_query("SELECT title FROM {menu_custom} WHERE menu_name = :a", array(':a' => $menu_name))->fetchField();
      $cache['title'] = check_plain($result);
    }
    cache_set($cache_key, $cache, 'cache_menu');
    $cache = cache_get($cache_key, 'cache_menu')->data;
  }

  $output['content'] = '';
  $output['subject'] = $cache['title'];
  if ($cache['menu']) {
    // Set the total menu depth counting from this parent if we need it.
    $depth = $sfsettings['depth'];
    $depth = ($sfsettings['depth'] > 0 && isset($cache['parent_depth'])) ? $cache['parent_depth'] + $depth : $depth;

    $var = array(
        'id' => $id,
        'menu' => $cache['menu'],
        'depth' => $depth,
        'trail' => humble_menu_build_page_trail(menu_tree_page_data($menu_name)),
        'sfsettings' => $sfsettings
    );
    if ($menu_tree = humble_menu_superfish_build($var)) {
      if ($menu_tree['content']) {
        // Add custom HTML codes around the main menu.
        if ($sfsettings['wrapmul'] && strpos($sfsettings['wrapmul'], ',') !== FALSE) {
          $wmul = explode(',', $sfsettings['wrapmul']);
          // In case you just wanted to add something after the element.
          if (drupal_substr($sfsettings['wrapmul'], 0) == ',') {
            array_unshift($wmul, '');
          }
        } else {
          $wmul = array();
        }
        $output['content'] = isset($wmul[0]) ? $wmul[0] : '';
        $output['content'] .= '<ul id="superfish-' . $id . '"';
        $output['content'] .= ' class="sf-menu ' . $menu_name . ' sf-' . $sfsettings['type'] . ' sf-style-' . $sfsettings['style'];
        $output['content'] .= ($sfsettings['itemcounter']) ? ' sf-total-items-' . $menu_tree['total_children'] : '';
        $output['content'] .= ($sfsettings['itemcounter']) ? ' sf-parent-items-' . $menu_tree['parent_children'] : '';
        $output['content'] .= ($sfsettings['itemcounter']) ? ' sf-single-items-' . $menu_tree['single_children'] : '';
        $output['content'] .= ($sfsettings['ulclass']) ? ' ' . $sfsettings['ulclass'] : '';
        $output['content'] .= ($language->direction == 1) ? ' rtl' : '';
        $output['content'] .= '">' . $menu_tree['content'] . '</ul>';
        $output['content'] .= isset($wmul[1]) ? $wmul[1] : '';
      }
    }
  }
  return $output;
}

/**
 * Helper function that builds the nested lists of a Superfish menu.
 */
function humble_menu_superfish_build($variables) {
  $output = array('content' => '');
  $id = $variables['id'];
  $menu = $variables['menu'];
  $depth = $variables['depth'];
  $trail = $variables['trail'];
  // Keep $sfsettings untouched as we need to pass it to the child menus.
  $settings = $sfsettings = $variables['sfsettings'];
  $megamenu = $settings['megamenu'];
  $total_children = $parent_children = $single_children = 0;
  $i = 1;

  // Reckon the total number of available menu items.
  foreach ($menu as $menu_item) {
    if (!isset($menu_item['link']['hidden']) || $menu_item['link']['hidden'] == 0) {
      $total_children++;
    }
  }

  foreach ($menu as $menu_item) {

    $show_children = $megamenu_wrapper = $megamenu_column = $megamenu_content = FALSE;
    $item_class = $link_options = $link_class = array();
    $mlid = $menu_item['link']['mlid'];

    if (!isset($menu_item['link']['hidden']) || $menu_item['link']['hidden'] == 0) {
      $item_class[] = ($trail && in_array($mlid, $trail)) ? 'active-trail' : '';

      // Add helper classes to the menu items and hyperlinks.
      $settings['firstlast'] = ($settings['dfirstlast'] == 1 && $total_children == 1) ? 0 : $settings['firstlast'];
      $item_class[] = ($settings['firstlast'] == 1) ? (($i == 1) ? 'first' : (($i == $total_children) ? 'last' : 'middle')) : '';
      $settings['zebra'] = ($settings['dzebra'] == 1 && $total_children == 1) ? 0 : $settings['zebra'];
      $item_class[] = ($settings['zebra'] == 1) ? (($i % 2) ? 'odd' : 'even') : '';
      $item_class[] = ($settings['itemcount'] == 1) ? 'sf-item-' . $i : '';
      $item_class[] = ($settings['itemdepth'] == 1) ? 'sf-depth-' . $menu_item['link']['depth'] : '';
      $link_class[] = ($settings['itemdepth'] == 1) ? 'sf-depth-' . $menu_item['link']['depth'] : '';
      $item_class[] = ($settings['liclass']) ? $settings['liclass'] : '';
      if (strpos($settings['hlclass'], ' ')) {
        $l = explode(' ', $settings['hlclass']);
        foreach ($l as $c) {
          $link_class[] = $c;
        }
      } else {
        $link_class[] = $settings['hlclass'];
      }
      $i++;

      // Add hyperlinks description (title) to their text.
      $show_linkdescription = ($settings['linkdescription'] == 1 && !empty($menu_item['link']['localized_options']['attributes']['title'])) ? TRUE : FALSE;
      if ($show_linkdescription) {
        if (!empty($settings['hldmenus'])) {
          $show_linkdescription = (is_array($settings['hldmenus'])) ? ((in_array($mlid, $settings['hldmenus'])) ? TRUE : FALSE) : (($mlid == $settings['hldmenus']) ? TRUE : FALSE);
        }
        if (!empty($settings['hldexclude'])) {
          $show_linkdescription = (is_array($settings['hldexclude'])) ? ((in_array($mlid, $settings['hldexclude'])) ? FALSE : $show_linkdescription) : (($settings['hldexclude'] == $mlid) ? FALSE : $show_linkdescription);
        }
        if ($show_linkdescription) {
          $menu_item['link']['title'] .= '<span class="sf-description">';
          $menu_item['link']['title'] .= (!empty($menu_item['link']['localized_options']['attributes']['title'])) ? $menu_item['link']['localized_options']['attributes']['title'] : array();
          $menu_item['link']['title'] .= '</span>';
          $link_options['html'] = TRUE;
        }
      }

      // Add custom HTML codes around the menu items.
      if ($sfsettings['wrapul'] && strpos($sfsettings['wrapul'], ',') !== FALSE) {
        $wul = explode(',', $sfsettings['wrapul']);
        // In case you just wanted to add something after the element.
        if (drupal_substr($sfsettings['wrapul'], 0) == ',') {
          array_unshift($wul, '');
        }
      } else {
        $wul = array();
      }

      // Add custom HTML codes around the hyperlinks.
      if ($settings['wraphl'] && strpos($settings['wraphl'], ',') !== FALSE) {
        $whl = explode(',', $settings['wraphl']);
        // The same as above
        if (drupal_substr($settings['wraphl'], 0) == ',') {
          array_unshift($whl, '');
        }
      } else {
        $whl = array();
      }

      // Add custom HTML codes around the hyperlinks text.
      if ($settings['wraphlt'] && strpos($settings['wraphlt'], ',') !== FALSE) {
        $whlt = explode(',', $settings['wraphlt']);
        // The same as above
        if (drupal_substr($settings['wraphlt'], 0) == ',') {
          array_unshift($whlt, '');
        }
        $menu_item['link']['title'] = $whlt[0] . check_plain($menu_item['link']['title']) . $whlt[1];
        $link_options['html'] = TRUE;
      }


      if (!empty($menu_item['link']['has_children']) && !empty($menu_item['below']) && $depth != 0) {
        // Megamenu is still beta, there is a good chance much of this will be changed.
        if (!empty($settings['megamenu_exclude'])) {
          if (is_array($settings['megamenu_exclude'])) {
            $megamenu = (in_array($mlid, $settings['megamenu_exclude'])) ? 0 : $megamenu;
          } else {
            $megamenu = ($settings['megamenu_exclude'] == $mlid) ? 0 : $megamenu;
          }
          // Send the result to the sub-menu.
          $sfsettings['megamenu'] = $megamenu;
        }
        if ($megamenu == 1) {
          $megamenu_wrapper = ($menu_item['link']['depth'] == $settings['megamenu_depth']) ? TRUE : FALSE;
          $megamenu_column = ($menu_item['link']['depth'] == $settings['megamenu_depth'] + 1) ? TRUE : FALSE;
          $megamenu_content = ($menu_item['link']['depth'] >= $settings['megamenu_depth'] && $menu_item['link']['depth'] <= $settings['megamenu_levels']) ? TRUE : FALSE;
        }
        // Render the sub-menu.
        $var = array(
            'id' => $id,
            'menu' => $menu_item['below'],
            'depth' => $depth, 'trail' => $trail,
            'sfsettings' => $sfsettings
        );
        $children = humble_menu_superfish_build($var);
        // Check to see whether it should be displayed.
        $show_children = (($menu_item['link']['depth'] <= $depth || $depth == -1) && $children['content']) ? TRUE : FALSE;
        if ($show_children) {
          // Add item counter classes.
          if ($settings['itemcounter']) {
            $item_class[] = 'sf-total-children-' . $children['total_children'];
            $item_class[] = 'sf-parent-children-' . $children['parent_children'];
            $item_class[] = 'sf-single-children-' . $children['single_children'];
          }
          // More helper classes.
          $item_class[] = ($megamenu_column) ? 'sf-megamenu-column' : '';
          $item_class[] = $link_class[] = 'menuparent';
        }
        $parent_children++;
      } else {
        $item_class[] = 'sf-no-children';
        $single_children++;
      }

      $item_class = implode(' ', array_filter($item_class));

      if (isset($menu_item['link']['localized_options']['attributes']['class'])) {
        $link_class_current = $menu_item['link']['localized_options']['attributes']['class'];
        $link_class = array_merge($link_class_current, array_filter($link_class));
      }
      $menu_item['link']['localized_options']['attributes']['class'] = $link_class;

      $link_options['attributes'] = $menu_item['link']['localized_options']['attributes'];

      // Render the menu item.
      $output['content'] .= '<li id="menu-' . $mlid . '-' . $id . '"';
      $output['content'] .= ($item_class) ? ' class="' . trim($item_class) . '">' : '>';
      $output['content'] .= ($megamenu_column) ? '<div class="sf-megamenu-column">' : '';
      $output['content'] .= isset($whl[0]) ? $whl[0] : '';
      $output['content'] .= l($menu_item['link']['title'], $menu_item['link']['link_path'], $link_options);
      $output['content'] .= isset($whl[1]) ? $whl[1] : '';
      $output['content'] .= ($megamenu_wrapper) ? '<ul class="sf-megamenu"><li class="sf-megamenu-wrapper ' . $item_class . '">' : '';
      $output['content'] .= ($show_children) ? (isset($wul[0]) ? $wul[0] : '') : '';
      $output['content'] .= ($show_children) ? (($megamenu_content) ? '<ol>' : '<ul>') : '';
      $output['content'] .= ($show_children) ? $children['content'] : '';
      $output['content'] .= ($show_children) ? (($megamenu_content) ? '</ol>' : '</ul>') : '';
      $output['content'] .= ($show_children) ? (isset($wul[1]) ? $wul[1] : '') : '';
      $output['content'] .= ($megamenu_wrapper) ? '</li></ul>' : '';
      $output['content'] .= ($megamenu_column) ? '</div>' : '';
      $output['content'] .= '</li>';
    }
  }
  $output['total_children'] = $total_children;
  $output['parent_children'] = $parent_children;
  $output['single_children'] = $single_children;
  return $output;
}

/**
 * Builds the active trail from the page's menu data.
 */
function humble_menu_build_page_trail($page_menu) {
  $trail = array();
  foreach ($page_menu as $item) {
    if ($item['link']['in_active_trail']) {
      $trail[] = $item['link']['mlid'];
    }
    if ($item['below']) {
      $trail = array_merge($trail, humble_menu_build_page_trail($item['below']));
    }
  }
  return $trail;
}


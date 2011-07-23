<?php
/*
# ------------------------------------------------------------------------
# JD engine
# ------------------------------------------------------------------------
# Copyright (C) 2004-2010 JoomlArt.com. All Rights Reserved.
# @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
# Author: JoomlArt.com
# Websites: http://www.joomlart.com - http://www.joomlancers.com.
# ------------------------------------------------------------------------
*/
class JDMenu extends JObject
{
	var $stype = null;
	var $data = null;
	var $name = null;
	var $title = null;
	var $description = null;
	var $extra_menu_param = null;
	var $showSeparatedSub = false;
	function __construct($style = NULL,$name = NULL)
	{
		if ($style)
			$this->stype = $style;
		if ($name)
			$this->name = $name;
		t3_add_css('css/menus/' . $style . '.css');
		$this->extra_menu_param = false;
	}
	function render($level = 0)
	{
		$style = $this->stype;
		$tree = $this->getData($level);
		$method = 'render_' . $style;
		return call_user_method($method, $this, $tree, $this->name);
	}
	/**
	 * For menu item admin edit
	 *
	 * @param menuitem $item
	 * @return if have admin edit
	 */
	function editItem($item)
	{
		return false;
	}
	function getStyle()
	{
		$menustyle = $this->get('styles');
		if (! $menustyle) {
			$menustyle = 'css';
			//$mobile = t3_mobile_device_detect();
			$device = isset($mobile) ? $mobile : 'default';
			if (in_array($device, array('iphone', 'handheld')))
				$menustyle = $device;
			$this->set('style', $menustyle);
		}
		return $menustyle;
	}
	function getData($level = 0)
	{
		return $this->jd_menu_tree_page_data($this->name);
	}
	function render_item($link,$extra_class = NULL,$id = NULL)
	{
		if (! empty($extra_class)) {
			$class .= ' ' . $extra_class;
		}
		if ($link['in_active_trail']) {
			$class .= ' active';
		}
		return '<li class="' . $class . '" id="' . $id . '">' . theme('menu_item_link', $link) . "</li>\n";
	}
	function getmenu($menu_name = 'navigation',$item = NULL,$increation = 1)
	{
		static $tree = array();
		// Use $mlid as a flag for whether the data being loaded is for the whole tree.
		$mlid = isset($item['mlid']) ? $item['mlid'] : 0;
		// Generate a cache ID (cid) specific for this $menu_name and $item.
		$cid = 'links:' . $menu_name . ':all-cid:' . $mlid . ':i:' . $increation;
		if (! isset($tree[$cid])) {
			// If the static variable doesn't have the data, check {cache_menu}.
			$cache = cache_get($cid, 'cache_menu');
			if ($cache && isset($cache->data)) {
				// If the cache entry exists, it will just be the cid for the actual data.
				// This avoids duplication of large amounts of data.
				$cache = cache_get($cache->data, 'cache_menu');
				if ($cache && isset($cache->data)) {
					$data = $cache->data;
				}
			}
			// If the tree data was not in the cache, $data will be NULL.
			if (! isset($data)) {
				// Build and run the query, and build the tree.
				if ($mlid) {
					// The tree is for a single item, so we need to match the values in its
					// p columns and 0 (the top level) with the plid values of other links.
					$args = array(0);
					for ($i = 1; $i < MENU_MAX_DEPTH; $i ++) {
						$args[ ] = $item["p$i"];
					}
					$args = array_unique($args);
					$placeholders = implode(', ', array_fill(0, count($args), '%d'));
					$where = ' AND ml.plid IN (' . $placeholders . ')';
					$parents = $args;
					$parents[ ] = $item['mlid'];
				} else {
					// Get all links in this menu.
					$where = '';
					$args = array();
					$parents = array();
				}
				array_unshift($args, $menu_name);
				// Select the links from the table, and recursively build the tree.  We
				// LEFT JOIN since there is no match in {menu_router} for an external
				// link.
				$result = db_query(
				"
	        SELECT m.load_functions, m.to_arg_functions, m.access_callback, m.access_arguments, m.page_callback, m.page_arguments, m.title, m.title_callback, m.title_arguments, m.type, m.description, ml.*,(ml.depth + $increation) AS depth
	        , 'a' as options
	        FROM {menu_links} ml LEFT JOIN {menu_router} m ON m.path = ml.router_path
	        WHERE ml.hidden =0 and ml.menu_name = '%s'" . $where . "
	        ORDER BY p1 ASC, p2 ASC, p3 ASC, p4 ASC, p5 ASC, p6 ASC, p7 ASC, p8 ASC, p9 ASC", $args);
				$data['tree'] = menu_tree_data($result, $parents);
				//var_dump($data['tree']);
				$data['node_links'] = array();
				menu_tree_collect_node_links($data['tree'], $data['node_links']);
				//var_dump($data['tree']);
				// Cache the data, if it is not already in the cache.
				$tree_cid = _menu_tree_cid($menu_name, $data);
				if (! cache_get($tree_cid, 'cache_menu')) {
					cache_set($tree_cid, $data, 'cache_menu');
				}
				// Cache the cid of the (shared) data using the menu and item-specific cid.
				cache_set($cid, $tree_cid, 'cache_menu');
			}
			// Check access for the current user to each item in the tree.
			menu_tree_check_access($data['tree'], $data['node_links']);
			$tree[$cid] = $data['tree'];
		}
		return $tree[$cid];
	}
	function jd_menu_tree_page_data($menu_name = 'navigation')
	{
		static $tree = array();
		// Load the menu item corresponding to the current page.
		if ($item = menu_get_item()) {
			// Generate a cache ID (cid) specific for this page.
			$cid = 'JDlinks:' . $menu_name . ':page-cid:' . $item['href'] . ':' . (int) $item['access'];
			if (! isset($tree[$cid])) {
				// If the static variable doesn't have the data, check {cache_menu}.
				$cache = cache_get($cid, 'cache_menu');
				if ($cache && isset($cache->data)) {
					// If the cache entry exists, it will just be the cid for the actual data.
					// This avoids duplication of large amounts of data.
					$cache = cache_get($cache->data, 'cache_menu');
					if ($cache && isset($cache->data)) {
						$data = $cache->data;
					}
				}
				// If the tree data was not in the cache, $data will be NULL.
				if (! isset($data)) {
					// Build and run the query, and build the tree.
					if ($item['access']) {
						// Check whether a menu link exists that corresponds to the current path.
						$args = array($menu_name, $item['href']);
						$placeholders = "'%s'";
						if (drupal_is_front_page()) {
							$args[ ] = '<front>';
							$placeholders .= ", '%s'";
						}
						$parents = db_fetch_array(db_query("SELECT p1, p2, p3, p4, p5, p6, p7, p8 FROM {menu_links} WHERE menu_name = '%s' AND link_path IN (" . $placeholders . ")", $args));
						if (empty($parents)) {
							// If no link exists, we may be on a local task that's not in the links.
							// TODO: Handle the case like a local task on a specific node in the menu.
							$parents = db_fetch_array(
							db_query("SELECT p1, p2, p3, p4, p5, p6, p7, p8 FROM {menu_links} WHERE menu_name = '%s' AND link_path = '%s'", $menu_name, $item['tab_root']));
						}
						// We always want all the top-level links with plid == 0.
						$parents[ ] = '0';
						// Use array_values() so that the indices are numeric for array_merge().
						$args = $parents = array_unique(array_values($parents));
						$placeholders = implode(', ', array_fill(0, count($args), '%d'));
						$expanded = variable_get('menu_expanded', array());
						// Check whether the current menu has any links set to be expanded.
						// Collect all the links set to be expanded, and then add all of
						// their children to the list as well.
						do {
							$result = db_query(
							"SELECT mlid FROM {menu_links} WHERE menu_name = '%s' AND has_children = 1 AND plid IN (" . $placeholders . ') AND mlid NOT IN (' . $placeholders . ')', 
							array_merge(array($menu_name), $args, $args));
							$num_rows = FALSE;
							while ($item = db_fetch_array($result)) {
								$args[ ] = $item['mlid'];
								$num_rows = TRUE;
							}
							$placeholders = implode(', ', array_fill(0, count($args), '%d'));
						} while ($num_rows);
						array_unshift($args, $menu_name);
					} else {
						// Show only the top-level menu items when access is denied.
						$args = array($menu_name, '0');
						$placeholders = '%d';
						$parents = array();
					}
					// Select the links from the table, and recursively build the tree. We
					// LEFT JOIN since there is no match in {menu_router} for an external
					// link.
					$data['tree'] = menu_tree_data(
					db_query(
					"
          SELECT m.load_functions, m.to_arg_functions, m.access_callback, m.access_arguments, m.page_callback, m.page_arguments, m.title, m.title_callback, m.title_arguments, m.type, m.description, ml.*
          FROM {menu_links} ml LEFT JOIN {menu_router} m ON m.path = ml.router_path
          WHERE ml.menu_name = '%s' AND ml.plid IN (" . $placeholders . ")
          ORDER BY p1 ASC, p2 ASC, p3 ASC, p4 ASC, p5 ASC, p6 ASC, p7 ASC, p8 ASC, p9 ASC", $args), $parents);
					$data['node_links'] = array();
					menu_tree_collect_node_links($data['tree'], $data['node_links']);
					// Cache the data, if it is not already in the cache.
					$tree_cid = _menu_tree_cid($menu_name, $data);
					if (! cache_get($tree_cid, 'cache_menu')) {
						cache_set($tree_cid, $data, 'cache_menu');
					}
					// Cache the cid of the (shared) data using the page-specific cid.
					cache_set($cid, $tree_cid, 'cache_menu');
				}
				// Check access for the current user to each item in the tree.
				menu_tree_check_access($data['tree'], $data['node_links']);
				$tree[$cid] = $data['tree'];
			}
			return $tree[$cid];
		}
		return array();
	}
}
?>
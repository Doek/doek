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
class T3Setting extends JObject
{
	var $theme = null;
	function __construct($theme)
	{
		if ($theme)
			$this->theme = $theme;
	}
	function getMenus()
	{
		$result = db_query("SELECT * FROM {menu_custom} ORDER BY title");
		$content = array();
		while ($menu = db_fetch_array($result)) {
			$content[$menu['menu_name']] = $menu['title'];
		}
		return $content;
	}
	function getMenuStyles()
	{
		global $t3_engine_path;
		$theme_path = drupal_get_path('theme', $this->theme);
		$menu_styles = array();
		$menu_path = $t3_engine_path . '/core/menus';
		$files = file_scan_directory($menu_path, '.class.php');
		foreach ($files as $paht => $file_info) {
			$name = explode(".", $file_info->name);
			if ($name[0] != 'base') {
				$menu_styles[$name[0]] = ucfirst($name[0]);
			}
		}
		$menu_path = $theme_path . '/core/menus';
		$files = file_scan_directory($menu_path, '.class.php');
		foreach ($files as $paht => $file_info) {
			$name = explode(".", $file_info->name);
			if ($name[0] != 'base') {
				$menu_styles[$name[0]] = ucfirst($name[0]);
			}
		}
		return $menu_styles;
	}
	function getThemeInfo($theme = null)
	{
		if (! $theme)
			$theme = $this->theme;
		if (! $theme)
			return null;
		$query = "SELECT filename, owner FROM {system} WHERE name='$theme' AND type = 'theme'";
		$result = db_query($query);
		$result = db_fetch_array($result);
		if ($result['filename'])
			$theme_info = drupal_parse_info_file($result['filename']);
		$result['base_theme'] = $theme_info['base_theme'];
		return $result;
	}
	function getLayouts()
	{
		global $t3_base_theme_path;
		
		$layouts = array();
		$layout_path = $t3_base_theme_path . '/layouts';
		
		$files = file_scan_directory($layout_path, '.xml');
		foreach ($files as $path => $file_info) {
			$layout_info = drupal_parse_info_file($path);
			$layout_info['content'] = file_get_contents($path);
			$layout_info['type'] = 'core';
			$layouts[$file_info->name] = $layout_info;
		}
		
		$theme_path = drupal_get_path('theme', $this->theme);
		
		$layout_path = $theme_path . '/core/layouts';
		$files = file_scan_directory($layout_path, '.xml');
		foreach ($files as $path => $file_info) {
			$layout_info = drupal_parse_info_file($path);
			$layout_info['content'] = file_get_contents($path);
			$layout_info['type'] = 'core';
			$layouts[$file_info->name] = $layout_info;
		}
		$layout_path = $theme_path . '/local/layouts';
		$files = file_scan_directory($layout_path, '.xml');
		foreach ($files as $path => $file_info) {
			$layout_info = drupal_parse_info_file($path);
			$layout_info['type'] = isset($layouts[$file_info->name]) ? 'override' : 'local';
			$layout_info['content'] = file_get_contents($path);
			$layouts[$file_info->name] = $layout_info;
		}
		return $layouts;
	}
	function getProfiles()
	{
		$theme_path = drupal_get_path('theme', $this->theme);
		$profiles = array();
		$profile_path = $theme_path . '/core/profiles';
		$files = file_scan_directory($profile_path, '.ini');
		foreach ($files as $path => $file_info) {
			$info = drupal_parse_info_file($path);
			
			
			$info['profile_type'] = 'core';
			$profiles[$file_info->name] = $info;
		}
		$profile_path = $theme_path . '/local/profiles';
		$files = file_scan_directory($profile_path, '.ini');
		foreach ($files as $path => $file_info) {
			$info = drupal_parse_info_file($path);
			$info['profile_type'] = isset($profiles[$file_info->name]) ? 'override' : 'local';
			$profiles[$file_info->name] =  $info;
		}
		
		//add some defaut value of default profile
		//setting_font = 3, setting_screen = wide, menu_style = css
		
		 
//		
//		foreach ($profiles as $name => $info) {
//			if (! is_array($info))
//				$info = array();
//			$profiles[$name] = array_merge($profiles['default'], $info);
//		}
//		
//		if(!isset($profiles['default']['setting_font'])) $profiles['default']['setting_font'] = 3; 
//		if(!isset($profiles['default']['setting_screen'])) $profiles['default']['setting_screen'] = 'wide';
//		if(!isset($profiles['default']['layout_handheld'])) $profiles['default']['layout_handheld'] = 'handheld';
//		if(!isset($profiles['default']['layout_iphone'])) $profiles['default']['layout_iphone'] = 'iphone';
//		
		
		return $profiles;
	}
	function getThemes()
	{
		global $t3_theme_path, $t3_engine_path, $t3_theme_info;
		t3_import('core/libs/object');
		t3_import('core/libs/simplexml');
		t3_import('core/libs/archive/zip');
		t3_import('core/xml');
		$T3Xml = & T3Xml::getInstance();
		$themes = array();
		$admin_themes = array();
		$t3_sub_theme_path = $t3_theme_path . '/core/themes';
		$files = file_scan_directory($t3_sub_theme_path, 'info.xml');
		$admin_themes['core'] = array();
		foreach ($files as $path => $file_info) {
			$name = basename(dirname($path));
			$theme_info = $T3Xml->parse($path);
			$theme_info = $theme_info->document;
			$theme_info->_type = 'core';
			$themes[$name] = $theme_info;
			$admin_themes['core'][$name] = $theme_info;
		}
		$t3_sub_theme_path = $t3_theme_path . '/local/themes';
		$files = file_scan_directory($t3_sub_theme_path, 'info.xml');
		$admin_themes['local'] = array();
		foreach ($files as $path => $file_info) {
			$name = basename(dirname($path));
			$theme_info = $T3Xml->parse($path);
			$theme_info = $theme_info->document;
			$theme_info->_type = isset($themes[$name]) ? 'override' : 'local';
			$themes[$name] = $theme_info;
			$admin_themes['local'][$name] = $theme_info;
		}
		$ret = array();
		$ret['theme'] = $themes;
		$ret['admin_theme'] = $admin_themes;
		return $ret;
	}
	function getBlocks($bids)
	{
		$theme = $this->theme;
		$query = "SELECT * FROM {blocks} WHERE theme ='$theme' AND region != '' ORDER BY region";
		$result = db_query($query);
		$hasSuffixs = array();
		$blocks = array();
		$module_blocks = array();
		while ($row = db_fetch_array($result)) {
			if (! $module_blocks[$row['module']])
				$module_blocks[$row['module']] = module_invoke($row['module'], 'block', 'list');
			$row['info'] = $module_blocks[$row['module']][$row['delta']]['info'];
			/*if($bids[$row['bid']]) {
				$row['suffix'] = $bids[$row['bid']];
				$hasSuffixs[] = $row;
			}else{
				if(!$blocks[$row['region']]) $blocks[$row['region']] = array();
				// we need get info of row here
			
				$blocks[$row['region']][] = $row;
			}*/
			if ($bids[$row['bid']]) {
				$row['suffix'] = $bids[$row['bid']];
			}
			$blocks[$row['region']][ ] = $row;
		}
		return $blocks;
		$ret = array('has' => $hasSuffixs, 'nhas' => $blocks);
		return $ret;
	}
	function getGeneral()
	{
		global $t3_theme_path;
		$general = array();
		//first load general at core
		$general_file = $t3_theme_path . '/core/general.ini';
		if (file_exists($general_file)) {
			$general = drupal_parse_info_file($general_file);
		}
		//then load more from local, local can be override option at core
		$general_file = $t3_theme_path . '/local/general.ini';
		if (file_exists($general_file)) {
			$general = drupal_parse_info_file($general_file);
		}
		
//		$new_general = array();
//		foreach ($general as $pages=>$profile){
//			$new_general[htmlspecialchars($pages)] = $profile;
//		}
//		
		return $general;
	}
}
?>
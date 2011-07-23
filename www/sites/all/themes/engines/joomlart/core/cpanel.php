<?php
/*
# ------------------------------------------------------------------------
# JD engine
# ------------------------------------------------------------------------
# Copyright (C) 2004-2009 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
# ------------------------------------------------------------------------
*/
class T3Cpanel extends JObject {
	function __construct($options = array()){
		
	}
	
	function &getInstance($options = array()){
		static $instance ;
		
		if(!$instance){
			$instance = new T3Cpanel($options);
		}

		return $instance;
	}
	
	function getProfiles(){
		global $t3_base_theme_path, $theme_key;
		
		$theme_path = drupal_get_path('theme', $theme_key);
		
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
		return $profiles;
	}
	
	function getThemes(){
		global $theme_path;
		$themes = array();
		$t3_sub_theme_path = $theme_path.'/core/themes';
		$files = file_scan_directory($t3_sub_theme_path, 'info.xml');
		
		foreach ($files as $path=>$file_info){
			$name = basename(dirname($path));
			$theme_info = drupal_parse_info_file($path);
			$theme_info['type'] = 'core';
			$themes[$name] = $theme_info;
		}
		
		$t3_sub_theme_path = $theme_path.'/local/themes';
		$files = file_scan_directory($t3_sub_theme_path, 'info.xml');
		
		
		foreach ($files as $path=>$file_info){
			$name = basename(dirname($path));
			$theme_info = drupal_parse_info_file($path);
			$theme_info['type'] = isset($themes[$name]) ? 'override': 'local';
			$themes[$name] = $theme_info;
		}
		
		return $themes;
	}
	
	function getLayouts(){
		global $t3_base_theme_path, $theme_key;
		
		$theme_path = drupal_get_path('theme', $theme_key);
		$layouts = array();
		
		
		$layout_path = $theme_path.'/core/layouts';
		$files = file_scan_directory($layout_path, '.xml');
		
		foreach ($files as $path=>$file_info){
			$layout_info['type'] = 'core';
			$layouts[$file_info->name] = $layout_info;
		}
		
		$layout_path = $theme_path.'/local/layouts';
		$files = file_scan_directory($layout_path, '.xml');
		
		foreach ($files as $path=>$file_info){
			$layout_info['type'] = isset($layouts[$file_info->name])? 'override':'local';
			$layouts[$file_info->name] = $layout_info;
		}
		
		
		return $layouts;
	}
}
?>
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
class T3Profile extends JObject {
	/**
	 * Default profile information
	 * Local default profile : extend of core default profile
	 * Sther profile extend : local default profile
	 * So we have get default profile in here
	 *
	 * @var array
	 */
	var $default_profile 		= null;
	
	/**
	 * current profile information
	 *
	 * @var array
	 */
	var $profile 				= null;
	
	var $themes					= null;
	
	var $profile_name 			= null;
	/**
	 * general information
	 *
	 * @var array
	 */
	var $_general 				= null;
	
	function __construct($options = array()){
		$T3Cache =& T3Cache::getInstance();
		$default_profile = $T3Cache->getCache('profile', 'default');
		if(!$default_profile){
			$default_profile = $this->_load_profile('default');
		}
		
		$this->default_profile = $default_profile;
		//load general
		$general = $T3Cache->getCache('profile', 'general');
		if(!$general) $general = $this->_load_general();
		
		$this->_general = $general;
		
		//set caches
		$T3Cache->setCache('profile', 'default', $default_profile);
		$T3Cache->setCache('profile', 'general', $general);
	}
	
	function &getInstance($options = array()){
		static $instance ;
		
		if(!$instance){
			$instance = new T3Profile($options);
		}

		return $instance;
	}
	

	/**
	 * Get profile of current page
	 *
	 * @return array	profile information
	 */
	function getProfile(){
		if(!is_array($this->profile)){
			$path = drupal_get_path_alias($_GET['q']);
			$T3Cache =& T3Cache::getInstance();
			
			$profile = $T3Cache->getCache('profile', $path);
			
			
//			if(is_array($profile)){
//				$this->profile = $profile;
//				return $profile;
//			}
			
			//if have not cached or cachmode not is cache
			if(!count($this->_general)) {
				
				$this->profile = $this->default_profile;
				
				return $this->profile;
			}
			
			
			
			//if general exits
			foreach ($this->_general as $pages=>$profile){
				//default is all page, so if meet this page, we ignore
				if($pages == 'default') continue;
				
				$profile = trim($profile);
				$pages = str_replace(",", "\n", $pages);
				
				
				$page_match = drupal_match_path($path, $pages);

				
				//each page just only use one profile, so if match, do not check any more
				if($page_match){
					$this->profile_name = $profile;
					//$this->profile = $this->_load_profile($profile);
					break;
				}
			}
			
			if(!$this->profile_name){
				$this->profile_name = $this->_general['default'] ? $this->_general['default'] : 'default';
			}
			
			$T3Params =& T3Params::getInstance();
			
			$this->profile_name = $T3Params->getProfile($this->profile_name);
			
			
			$this->profile = $this->_load_profile($this->profile_name);
			
			//set cache
			$T3Cache->setCache('profile', $path, $this->profile);
		}
		 
		
		return $this->profile;
	}
	
	/**
	 * get current layout (defined in profile)
	 *
	 * @param string $device
	 * @return string
	 */
	function getLayout($device){
		$T3Params =& T3Params::getInstance();
		$profile = $this->getProfile();
		
		if($device){
			
			$key = 'layout_' . $device;
			
			return $this->getValue($key, $device);
		}else{
			return $T3Params->get('layout_desktop', 'default');
		}
	}
	
	/**
	 * get themes are using (defined in profile)
	 *
	 * @return array
	 */
	function getThemes(){
		
		if(!$this->themes){
			$profile = $this->getProfile();
			
			$themes = array();
			
			$themes = $profile['themes'];
			$themes = explode(",", $themes);
			
			//always have default themes
			//$themes = array_merge(array('default'), $themes);
			$themes[] = 'default';
			$themes = array_reverse($themes);
			$themes = t3_trim_array($themes);
			$this->themes = $themes;
		}
		
		return $this->themes;
	}
	
	/**
	 * load rule of profile and page (what profile is using for current page)
	 *
	 */
	function _load_general(){
		global $theme_path;
		if(!$this->_general){
			$general = array();
			//first load general at core
			$general_file = $theme_path.'/core/general.ini';
			
			if(file_exists($general_file)){
				$general = drupal_parse_info_file($general_file);
			}
			
			
			//then load more from local, local can be override option at core
			$general_file = $theme_path.'/local/general.ini';
			if(file_exists($general_file)){
				$general = drupal_parse_info_file($general_file);
			}
			
			if(!is_array($general)) $general = array('default'=>'default');
			$general = array_reverse($general);
			$this->_general = $general;
		}
		
		return $this->_general;
	}
	
	/**
	 * load profile from file to array
	 *
	 * @param string $profile	name of profile
	 * @return array	profile information
	 */
	function _load_profile($profile){
		global $theme_path, $base_theme_info;
		
		$base_theme_path = dirname($base_theme_info[0]->filename);
		//need to get default profile first
		$t3_profile = array();
		
		//if is default profile, maybe have been parsed
		if($profile == 'default'){
			if($this->default_profile){
				$t3_profile = $this->default_profile;
			}else{
				$base_theme_default_path = $base_theme_path.'/default/profiles/default.ini';
				
				if(file_exists($base_theme_default_path)){
					$t3_profile = drupal_parse_info_file($base_theme_default_path);
				}
				
				$core_default_path = $theme_path.'/core/profiles/'.$profile.'.ini';
				if(file_exists($core_default_path)){
					$t3_profile = drupal_parse_info_file($core_default_path);
				}
				
				$local_default_path = $theme_path.'/local/profiles/'.$profile.'.ini';
				if(file_exists($local_default_path)){
					$t3_profile = drupal_parse_info_file($local_default_path);
				}
			}
		}else{
			$core_default_path = $theme_path.'/core/profiles/'.$profile.'.ini';
			if(file_exists($core_default_path)){
				$t3_profile = drupal_parse_info_file($core_default_path);
			}
			
			$local_default_path = $theme_path.'/local/profiles/'.$profile.'.ini';
			if(file_exists($local_default_path)){
				$t3_profile = drupal_parse_info_file($local_default_path);
			}
			
			//if is not default profile, we need merge with default profile
			$default_profile = $this->default_profile;
			foreach ($default_profile as $name=>$value){
				if(preg_match('/gfont_[a-z]+.style/', $name, $matchs)){
					$default_profile[$name] = $t3_profile[$name];
				}
			}
			$t3_profile = array_merge($default_profile, $t3_profile);
		}
		
		
		return $t3_profile;
	}
	
	function getValue($name, $default){
		if(isset($this->profile[$name])) return $this->profile[$name];
		
		return $default;
	}
}
?>
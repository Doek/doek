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
class T3Params extends JObject {
	/* store theme setting parameters */
	var $_theme_params		= null;
	
	/* store setting in profile */
	var $_profile			= null;
	
	function __construct($options = array()){
		global $theme_key;
		$this->_theme_params = theme_get_setting($theme_key);
		
		//get profile
		
		
	}
	
	function &getInstance($options = array()){
		static $instance ;
		
		if(!$instance){
			$instance = new T3Params($options);
		}

		return $instance;
	}
	
	function get($name, $default = null){
		global $theme_key;
		
		$T3Profile =& T3Profile::getInstance();
		$this->_profile = $T3Profile->getProfile();
		//First get from $_GET
		if($_GET[$name]) return $_GET[$name];
		
		if($_POST[$name]) return $_POST[$name];
		
		if($_COOKIE[$theme_key . '_' . $name]) return $_COOKIE[$theme_key . '_' . $name];
		
		if(isset($this->_profile[$name])) return $this->_profile[$name];
		
		if($this->_theme_params[$name]) return $this->_theme_params[$name];
		
		return $default;
	}
	
	function getFront($name, $default = null){
		global $theme_key;
		$T3Profile =& T3Profile::getInstance();
		$this->_profile = $T3Profile->getProfile();
		//First get from $_GET
		if($_GET[$name]) return $_GET[$name];
		
		if($_POST[$name]) return $_POST[$name];
		
		if($_COOKIE[$theme_key . '_' . $name]) return $_COOKIE[$theme_key . '_' . $name];
		
		if($this->_profile['setting_'.$name]) return $this->_profile['setting_'.$name];
		
		if($this->_theme_params[$name]) return $this->_theme_params[$name];
		
		return $default;
	}
	
	function getProfile( $default = null){
		global $theme_key;
		$name = 'profile';
		//First get from $_GET
		if($_GET[$name]) return $_GET[$name];
		
		if($_POST[$name]) return $_POST[$name];
		
		if($_COOKIE[$theme_key . '_' . $name]) return $_COOKIE[$theme_key . '_' . $name];
		
		if($this->_theme_params[$name]) return $this->_theme_params[$name];
		
		return $default;
	}
	
	function set($name, $value){
		global $theme_key;
		
		$exp = time() + 60*60*24*355;
		setCookie ($theme_key.'_'.$name, $value, $exp, '/');
	}
}
?>
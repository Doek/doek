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
class T3Util {
	
	private static $_ie		= null;
	
	private static $_device = null;
	
	private static $_cache_mode = -1;
	/**
	 * get version ie of browser are using
	 *
	 * @return int
	 */
	function getIE(){
		if(self::$_ie == null){
			ereg('MSIE ([0-9]\.[0-9])', $_SERVER['HTTP_USER_AGENT'], $reg);
			if (! isset($reg[1])) {
				return - 1;
			} else {
				self::$_ie = floatval($reg[1]);
			}
		}
		
		return self::$_ie;
	}
	
	function getLanguage(){
		global $language;
		
		return $language;
	}
	/**
	 * true if browser is IE
	 *
	 * @return boolean
	 */
	function isIE(){
		return (T3Util::getIE() != - 1);
	}
	
	/**
	 * something diffirent with IE 6, so need this function to check if browser is IE 6
	 *
	 * @return boolean
	 */
	function isIE6(){
		return (T3Util::getIE() == 6);
	}
	
		/**
	 * Check if user are using Right To Left language
	 *
	 * @return boolean
	 */
	function isRTL(){
		$language = T3Util::getLanguage();
		
		return ($language->direction == LANGUAGE_RTL);
	}
	
	/**
	 * detect handheld device
	 *
	 * @return string
	 */
	function getDevice_(){
		static $device;
		
		if(!$device){
			$T3Params =& T3Params::getInstance();
			$ui = $T3Params->get('ui');
			//detect mobile
			t3_import ('core/libs/mobile_device_detect');
			//bypass special browser:
			$special = array('jigs', 'w3c ', 'w3c-', 'w3c_');		
			if (in_array(strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4)), $special)) $mobile_device = false;
			else $mobile_device = mobile_device_detect('iphone','android','opera','blackberry','palm','windows');
			
			if($ui == 'desktop'){
				$device = false;
			}else if($ui=='mobile' && !$mobile_device){
				$device = 'iphone';
			}elseif ($ui=='handheld' && !$mobile_device){
				$device = 'handheld';
			}else{
				$device = $mobile_device;
			}
		}
		
		return $device;
	}
	
	
	function getDevice () {
		
		static $device;
		
		if(!$device){
			
			$T3Params =& T3Params::getInstance();
			$ui = $T3Params->get('ui');
			
			if ($ui=='desktop') $device = false;
			
			//detect mobile
			t3_import ('core/libs/Browser');
			$browser = new Browser();
			
			//bypass
			if ($browser->isRobot()) $device = false; 
	
			//mobile
			if ($browser->isMobile()) {
				if (in_array($browser->getBrowser(), array(Browser::BROWSER_IPHONE, Browser::BROWSER_IPOD, Browser::BROWSER_SAFARI))) 
					$device = 'iphone';
				else
					$device = strtolower($browser->getBrowser());
				
			}
			//Not mobile
			
			if ($ui=='mobile') $device = 'iphone'; //default for mobile layout on desktop
			
		}
		
		
		return $device;	
	}
	/**
	 * get current url
	 * just temp function, need to write custom core for this function
	 *
	 * @return string
	 */
	function getCurrentURL(){
		global $base_path;
		
		return url($base_path);
	}
	
	/**
	 * what cache mode are used
	 * 1 : cache will be change when user change any ini file
	 * 2 : just change when user using admin and changed data
	 *
	 * @return int
	 */
	function cacheMode(){
		global $theme_key;
		if(self::$_cache_mode == -1){
			$devmode = variable_get($theme_key.'_devmode', 1);
			self::$_cache_mode = $devmode == 1 ? 0 : 2;
		}
		
		return self::$_cache_mode;
	}
	
	function getBrowserSortName () {
		t3_import('core/libs/Browser');
		$browser = new Browser();
		$bname = $browser->getBrowser();
		switch ($bname) {
			case Browser::BROWSER_IE:
				return 'ie';
			case Browser::BROWSER_POCKET_IE:
				return 'pie';
			case Browser::BROWSER_FIREFOX:
				return 'ff';
			case Browser::BROWSER_OPERA:
				return 'op';
			case Browser::BROWSER_OPERA_MINI:
				return 'mop';
			case Browser::BROWSER_MOZILLA:
				return 'moz';
			case Browser::BROWSER_KONQUEROR:
				return 'kon';
			case Browser::BROWSER_CHROME:
				return 'chr';
			default:
				return strtolower(str_replace (' ', '-', $bname));
		}
	}
	function getBrowserMajorVersion () {
		t3_import('core/libs/Browser');
		$browser = new Browser();
		$bver = explode ('.', $browser->getVersion());
		return $bver[0]; //Major version only		
	}
}
?>
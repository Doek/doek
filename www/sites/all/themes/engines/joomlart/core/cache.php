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
class T3Cache extends JObject{
	
	public static $_keys	= array();
	
	/**
	 * make sure we dont need to access to database many time
	 * we store each type of caches to one cache object
	 *
	 * @var array
	 */
	public static $_caches	= array();
	
	function __construct($options = array()){
		global $theme_key;
		//get all of cache and store into caches parameter
		if(T3Util::cacheMode()!= 0){
			$key = md5('T3-'.$theme_key);
			
			if($cache = cache_get($key)){
				self::$_caches = $cache->data;
			}
		}
		
		
		//detructor
		register_shutdown_function(array($this, 'destroy'));
		
	}
	
	function &getInstance($options = array()){
		static $instance ;
		
		if(!$instance){
			$instance = new T3Cache($options);
		}

		return $instance;
	}
	/**
	 * get cache data
	 *
	 * @param string $type
	 * @param string $key
	 * @return string
	 */
	function getCache($type, $key){
		//profile just only cache in full cache mode
		if(T3Util::cacheMode()){
			$key = $this->key($type, $key);
			$cache = self::$_caches[$type][$key];
			return $cache;
		}
		
		return null;
	}
	
	function setCache($type, $key, $data){
		if(T3Util::cacheMode()){
			$key = $this->key($type, $key);
			if(!is_array(self::$_caches[$type])) self::$_caches[$type] = array();
			if(!self::$_caches[$type][$key]){
				self::$_caches[$type][$key] = $data;	
			}
		}
	}
	
	/**
	 * get keyword for cache
	 *
	 * @param string $type
	 * @param string $key
	 * @return string
	 */
	function key($type, $key){
		global $theme_key;
		//make sure key does not get more than one time
		if(self::$_keys[md5($type.$key)]){
			return self::$_keys[md5($type.$key)];
		}
		
		$base_on_key = '';
		switch ($type) {
			case 'path':
				//path anytime just base on themes
				$T3Profile =& T3Profile::getInstance();
				$themes = $T3Profile->getThemes();
				$base_on_key = serialize($themes);
				break;
			case 'layout':
				/**
				 * Layout will be change when :
				 * 	- Change layout content
				 * 	- Change settings or profile (something like menu type, screen setting..) 
				 */
				$T3Profile =& T3Profile::getInstance();
				$profile = $T3Profile->getProfile();
				break;
			case 'theme':
				if(T3Util::cacheMode() == 1){
					//in this mode, we check cache base on layout content
					//and profile and parameter
					$T3Profile =& T3Profile::getInstance();
					$T3Layout =& T3Layout::getInstance();
					
					$themes = $T3Profile->getThemes();
					$layouts = $T3Layout->getParams();
					
					$base_on_key = md5(serialize($themes).serialize($layouts));
				}elseif (T3Util::cacheMode() == 2){
					//in full mode, base on key is null
					
				}
				break;
			case 'page':
				t3_import ('core/libs/Browser');
				$browser = new Browser();
				$base_on_key .= $browser->getBrowser().":".$browser->getVersion();
				
				//and this base-on cookie was use for this framework
				foreach ($_COOKIE as $key=>$value){
					if(strpos($key, $theme_key) === true){
						$base_on_key .= $key. ":". $value;
					}
				}
				
				break;
			case 'menu':
				$base_on_key = $_COOKIE[$theme_key.'_menu_style'];
				break;
			default:
				break;
		}
		//else, get from cache
		$cache_key = md5($base_on_key . $key);
		
		self::$_keys[md5($type.$key)] = $cache_key;
		
		return $cache_key;
	}
	
	function destroy(){
		global $theme_key;
		if(T3Util::cacheMode()){
			$key = md5('T3-'.$theme_key);
			$cache = self::$_caches;
			
			cache_set($key, $cache);
		}
	}
}
?>
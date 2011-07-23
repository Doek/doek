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
class T3Path{
	/**
	 * base cache key of this class
	 * can be set by global, so need to using public keyword
	 *
	 * @var string
	 */
	public static $cache_key		= null;
	
	/**
	 * using with cache mode
	 * if has been cached, set value = true
	 * else value = false
	 *
	 * @var boolean
	 */
	public static $is_cached		= null;
	/**
	 * paths list, this is very important for t3 framework
	 * so we need to private, dont want to global access from any code
	 *
	 * @var array
	 */
	private static $_paths				= array();
	
	/**
	 * add path to path list of T3Path object
	 * so when we look for any file, we just need to look
	 * folow by path list with order of its
	 *
	 * @param string $path
	 * @param string $type
	 */
	function addPath($path, $type='default'){
		if(!is_array(self::$_paths[$type])) self::$_paths[$type] = array();
		
		if(!in_array($path, self::$_paths[$type]))
			self::$_paths[$type][] = $path;
	}
	
	/**
	 * using to set paths array
	 *
	 * @param array $paths
	 */
	function setPaths($paths){
		self::$_paths = $paths;
	}
	
	/**
	 * get paths
	 *
	 * @return array
	 */
	function getPaths(){
		return self::$_paths;
	}
	
	
	/**
	 * return path of file
	 *
	 * @param string $filename
	 * @param string $extra_path
	 * @param array $default
	 * @param int $return_type
	 * @param string $type
	 * @return string
	 */
	function getPath($filename, $extra_path='', $default = array(), $return_type = 0, $css = false, $type='default'){
		
		$T3Cache =& T3Cache::getInstance();
		
		$key =  md5($filename.$extra_path.serialize($default).$return_type.$css.$type);
		
		$path = $T3Cache->getCache('path', $key);
		
		if($path) return $path;

		
		//if have not cached, work as normal
		global $base_url, $base_root, $base_path;
		//process extra path first, we dont want to use extra path like '/block'
		//so here need add forward slass (/) if have extra path
		if($extra_path != '') $extra_path = '/'.$extra_path;
		
		
		//1 : return like /joom/dev/drupal/sites/all/themes/joomlart/local/profiles/default.ini
		//2: using for js and css with full url as http://url
		$init = '';
		if($return_type == 1){
			$init = $base_path;
		}else if ($return_type == 2) {
			$init = $base_url.'/';
		}
			
		
		$result_path = '';
		foreach (self::$_paths[$type] as $path){
			$tmp = $path.$extra_path.'/'.$filename;
			if(file_exists($tmp) && is_file($tmp)){
				if($css){
					if(!is_array($result_path)) $result_path = array();
					$result_path[] = $init.$tmp;
				}else{
					$result_path = $init.$tmp;
				}
			}
		}
		
		//default do not using for css
		if($result_path == '' && count($default)){
			foreach (self::$_paths[$type] as $path){
				foreach ($default as $default_file){
					$tmp = $path.$extra_path.'/'.$default_file;
					if(file_exists($tmp) && is_file($tmp)){
						$result_path = $init.$tmp;
					}
				}
			}
		}
		
		//ok, if using cache mode, need to set data to cache
		$T3Cache->setCache('path', $key, $result_path);
		
		return $result_path;
	}
	
	/**
	 * get general page to render themes
	 *
	 * @param string $device : need like file name
	 * @return string
	 */
	function getPage($device){
		return T3Path::getPath($device ,'page', array('handheld.php', 'default.php'));
	}
	
	/**
	 * get layout file path
	 *
	 * @param string $layout
	 * @return string
	 */
	function getLayout($layout){
	$device = T3Util::getDevice();
		if($device){
		  return T3Path::getPath($layout.'.xml', 'layouts', array('handheld.xml'), 0 ,false, 'layout');
	  }else{
	    return T3Path::getPath($layout.'.xml', 'layouts', array('default.xml'), 0 ,false, 'layout');
	  }
	}
	
	/**
	 * get block page
	 *
	 * @param string $type : type of block (mannav, header, topsportlight..)
	 * @return string
	 */
	function getBlock($type){
		return T3Path::getPath($type .'.php', 'blocks', array('middle.php'));
	}
	
	
	function getBlockRound($type){
		return T3Path::getPath($type.'.php', 'blocks/round', array('default.php'));
	}
	/**
	 * get template block file
	 *
	 * @param string $style
	 * @return string
	 */
	function getBlockTpl($style){
		global $t3_base_theme, $t3_themes;
		
		$tpl_file = 'block.tpl.php';
		$tpl_style_file = 'block'.($style?'-'.$style:'').'.tpl.php';
		
		$block_file = T3Path::getPath($tpl_style_file, 'tpl', array('block.tpl.php'));
		
		return $block_file;
	}
	
	/**
	 * get template of node file
	 *
	 * @param string $style
	 * @return string
	 */
	function getNodeTpl($type){
		$tpl_file = 'node.tpl.php';
		$tpl_style_file = 'node'.($type?'-'.$type:'').'.tpl.php';
		
		return T3Path::getPath($tpl_style_file, 'tpl', array('node.tpl.php'));
	}
	
	/**
	 * get template file to parse
	 *
	 * @param string $tpl_file
	 * @return string
	 */
	function getTplFile($tpl_file){
	  return T3Path::getPath($tpl_file, 'tpl',array());
	}
	
	/**
	 * get css sources
	 *
	 * @param string $src
	 * @return array
	 */
	function getCss($src, $url_type = 1){
		return T3Path::getPath($src,'',array(), $url_type, true);
	}
	
	/**
	 * get javascript source
	 *
	 * @param string $src
	 * @return string
	 */
	function getJs($src, $url_type = 1){
		return T3Path::getPath($src,'',array(), $url_type);
	}
}
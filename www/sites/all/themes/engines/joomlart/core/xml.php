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
class T3Xml extends JObject {
	var $_xmls 			= array();
	
	function __construct($options = array()){
		
	}
	
	function &getInstance($options = array()){
		static $instance ;
		
		if(!$instance){
			$instance = new T3Xml($options);
		}

		return $instance;
	}
	
	function parse($file){
		$key = md5($file);
		
		if(!$this->_xmls[$key]){
			$SimpleXml = new JSimpleXML();
			$SimpleXml->loadFile($file);
			
			$this->_xmls[$key]  = $SimpleXml;
		}
		
		return $this->_xmls[$key];
	}
}
?>
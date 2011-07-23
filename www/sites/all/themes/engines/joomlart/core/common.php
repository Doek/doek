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
/**
 * check if layout using t3 block
 *
 * @param string $t3block t3 block name like : left1, right1, content-mass-top...
 * @return boolean
 */
//function t3_has_block($t3block){
//	$T3Layout =& T3Layout::getInstance();
//	return $T3Layout->hasBlock($t3block);
//}


/**
 * count drupal block have used in t3 layout
 *
 * @param string $drupal_block
 * @return boolean
 */
function t3_count_drupal_block($drupal_block){
	$T3Layout =& T3Layout::getInstance();
	return $T3Layout->countBlocks($drupal_block);
}

function t3_get_column_width($name){
	$T3Layout =& T3Layout::getInstance();
	return $T3Layout->getColumnWidth($name);
}

function t3_cal_spotlight($spotlight,$totalwidth, $specialwidth, $special){
	$T3Layout =& T3Layout::getInstance();
	return $T3Layout->calSpotlight($spotlight,$totalwidth, $specialwidth, $special);
}
?>
<?php
/*
common function for T3
*/
function t3_import ($object) {
	$path = dirname(dirname(__FILE__)).'/'.$object.'.php';
	if (file_exists ($path)){
		require_once ($path);
		return true;
	}
	
	return false;
}



function t3_set_message($message = NULL, $type='error', $repeat = TRUE){
	if($message){
		if(!isset($_SESSION['t3_messages'])){
			$_SESSION['t3_messages'] = array();
		}
		
		
		if(!is_array($_SESSION['t3_messages'][$type])){
			$_SESSION['t3_messages'][$type] = array();
		}
		
		$_SESSION['t3_messages'][$type][] = $message;
	}
  	// messages not set when DB connection fails
  	return isset($_SESSION['t3_messages']) ? $_SESSION['t3_messages'] : NULL;
}

function t3_get_messages($type = NULL, $clear_queue = TRUE) {
  if ($messages = t3_set_message()) {
    if ($type) {
      	if ($clear_queue) {
        	unset($_SESSION['t3_messages'][$type]);
      	}
      	if (isset($messages[$type])) {
        	return array($type => $messages[$type]);
      	}
    }else {
      	if ($clear_queue) {
        	unset($_SESSION['t3_messages']);
      	}
      	return $messages;
    }
  }
  return array();
}

function t3_valid_name($name){
	$name = trim($name);
	
	$regex = '/[^a-zA-Z 0-9\-_]+/';
	$name = preg_replace($regex, '', $name);
	
	$regex = '/[\s]+/';
	$name = preg_replace($regex, ' ', $name);
	
	$regex = '/[\s-]+/';
	$name = preg_replace($regex, '_', $name);
	
	return $name;
}

function t3_trim_array($array){
	if(!is_array($array) || !count($array)) return;
	
	for ($i=0;$i<count($array);$i++){
		$array[$i] = trim($array[$i]);
	}
	
	return $array;
}

function t3_ignore_array($data, $ignore){
	foreach ($ignore as $item){
		unset($data[$item]);
	}
	
	return $data;
}

function t3_compare_array($array1, $array2){
	if(!is_array($array1) || !is_array($array2)) return true;
	foreach ($array1 as $key=>$value){
		if($array2[$key] != $value) return true;
	}
	
	foreach ($array2 as $key=>$value){
		if($array1[$key] != $value) return true;
	}
	
	return false;
}

function t3_file_save_data($data, $dest){
	 $temp = file_directory_temp();
  	// On Windows, tempnam() requires an absolute path, so we use realpath().
  	$file = tempnam($temp, 'file');
  	if (!$fp = fopen($file, 'wb')) {
	    t3_set_message(t('The file could not be created.'));
	    return false;
  	}
  	fwrite($fp, $data);
  	fclose($fp);
  	
  	if(!file_exists(dirname($dest))){
  		$result = t3_create_folder(dirname($dest));
  		if(!$result) return false;
  	}
  	
  	if (!@copy($file, $dest)) {
  		t3_set_message(t('Cannot copy file "@file"',array('@file'=>$dest)));
    	return false;
  	}

  	return $file;
}

function t3_create_folder($path, $mode = 0755){
	
	static $nested = 0;
	$parent = dirname($path);
	
	if (!file_exists($parent)) {
		// Prevent infinite loops!
		$nested++;
		if (($nested > 20) || ($parent == $path)) {
			t3_set_message('create folder : '.$path.' : Infinite loop detected', 't3_admin');
			$nested--;
			return false;
		}

		// Create the parent directory
		if (t3_create_folder($parent, $mode) !== true) {
			// JFolder::create throws an error
			$nested--;
			return false;
		}

		// OK, parent directory has been created
		$nested--;
	}
	

	// Check if dir already exists
	if (file_exists($path) && is_dir($path)) {
		return true;
	}
	
	//have not exists folder, need to create
	
	//first we need to umask
	$org = @umask(0);
	
	if(!$ret = @mkdir($path, $mode)){
		@umask($org);
		
		t3_set_message('Could not create directory-' . t('Path:') . $path);
		
		return false;
	}
	
	@umask($org);
	
	return $ret;
}


function t3_copy_folder($src, $dest){
	if (!file_exists($src)) {
		t3_set_message(t('Cannot find source folder'));
		
		return false;
	}
	
	
	// Make sure the destination exists
	if(t3_create_folder($dest) !== true){
		t3_set_message(t('Unable to create target folder'));
		return false;
	}
	
	if (!($dh = @opendir($src))) {
		t3_set_message(t('Unable to open source folder'));
		return false;
	}
	// Walk through the directory copying files and recursing into folders.
	while (($file = readdir($dh)) !== false) {
		$sfid = $src . '/' . $file;
		$dfid = $dest . '/' . $file;
		switch (filetype($sfid)) {
			case 'dir':
				if ($file != '.' && $file != '..') {
					$ret = t3_copy_folder($sfid, $dfid);
					if ($ret != true) {
						return $ret;
					}
				}
				break;

			case 'file':
				if (!@copy($sfid, $dfid)) {
					t3_set_message(t('Copy failed'));
					return false;
				}
				break;
		}
	}
	
	closedir($dh);
	return true;
}

function t3_delete_folder($folder){
	if (!file_exists($folder)) {
		t3_set_message(t('Folder "@folder" does exits."', array('@folder'=>$folder)));
		return false;
	}
	
	if (!($dh = @opendir($folder))) {
		t3_set_message(t('Cannot open folder "@folder"', array('@folder'=>$folder)));
		return false;
	}
	// Walk through the directory copying files and recursing into folders.
	while (($file = readdir($dh)) !== false) {
		
		$sub_folder = $folder .'/'. $file;
		
		switch (filetype($sub_folder)) {
			case 'dir':
				if($file != '.' && $file != '..'){
					$ret = t3_delete_folder($sub_folder);
					if (!$ret) {
						return $ret;
					}
				}
				break;
			case 'file':
				if (!@unlink($sub_folder)) {
					t3_set_message(t('Cannot delete file "@file".', array('@file'=>$sub_folder)));
					return false;
				}
				break;
		}
	}
	closedir($dh);
	
	//delete this folder
	$ret = @rmdir($folder);
	
	if(!ret){
		t3_set_message(t('Cannot delete folder "@folder"', array('@folder'=>$folder)));
	}
	return $ret;
}

function t3_array_diff($array1, $array2){
	$result = array();
	foreach ($array1 as $key=>$value){
		if(!$array2[$key] || $array2[$key] != $value){
			$result[$key] = $value;
		}
	}
	
	return $result;
}
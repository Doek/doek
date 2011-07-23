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
class T3Admin extends JObject
{
	var $t3_task = null;
	function T3Admin($t3_task = NULL){
		if ($t3_task) {
			$this->t3_task = $t3_task;
		}
	}

	function execute($task){
		if (method_exists($this, $task)) {
			return call_user_method($task, $this);
		}
	}

	function store(){
		//get theme process and profile path
		$var = $_POST['var'];
		preg_match('/theme_(.*)_settings/', $var, $matchs);
		if (! $matchs[1])
		return;
		$theme_key = $matchs[1];
		$theme_path = drupal_get_path('theme', $theme_key);
		$this->save_profile($theme_path);
		$this->save_general($theme_path, $theme_key);

		$ret = array('success' => 1);
		echo drupal_to_js($ret);

	}
	/**
	 * Store only layout (for layout save ajax button)
	 *
	 */
	function store_layout(){
		global $t3_base_theme_path;
		$theme = $_POST['theme'];
		$name = $_REQUEST['t3_layout_name'];
		$new_name = trim($_REQUEST['t3_layout_name_edit']) == '' ? $name : trim(
		$_REQUEST['t3_layout_name_edit']);
		//init return array and set default state is 'nochange'
		$ret = array();
		$ret['state'] = 'nochange';
		$ret['old_name'] = $name;
		$ret['new_name'] = $new_name;
		$ret['type'] = 'local';
		$ret['message'] = array();
		$rename = false;
		if ($new_name != '' && $new_name != $name) {
			//rename here
			$rename = true;
		}

		$content = $_POST['t3_layout_content'];
		$theme_path = drupal_get_path('theme', $theme);
		$file = $theme_path . '/local/layouts/' . $name . '.xml';

		if (! file_exists($file)) {
			$ret['type'] = 'override';
			$file = $theme_path . '/core/layouts/' . $name . '.xml';
		}

		if (! file_exists($file)) {
			$ret['type'] = 'override';
			$file = $t3_base_theme_path . '/layouts/' . $name . '.xml';
		}
		//detect type of old layout
		if (file_exists($file)) {
			$info = file_get_contents($file);
			if ($content != $info) {
				$file = $theme_path . '/local/layouts/' . $new_name . '.xml';
				$result = t3_file_save_data($content, $file);
				if(!$result){
					$ret = array();
					$ret['success'] = 0;
					$ret['message'] = t3_get_messages();

					echo drupal_to_js($ret);
					return;
				}
				//delete old layout
				if ($rename) {
					$old_file = $theme_path . '/local/layouts/' . $name . '.xml';
					if (file_exists($old_file)) {
						@unlink($old_file);
					}
					$ret['rename'] = 1;
					t3_set_message(t('"@oname" layout was successfully renamed', array('@oname'=>$name)), 'status');
				}

				$ret['success'] = 1;
				$ret['state'] = 'edit';
				t3_set_message(t('"@name" layout was successfully updated!',array('@name'=>$new_name)),'status');
				$ret['message'] = t3_get_messages();

			} else {
				if ($rename) {
					$old_file = $theme_path . '/local/layouts/' . $name . '.xml';
					$new_file = $theme_path . '/local/layouts/' . $new_name . '.xml';
					if(@rename($old_file, $new_file)){
						$ret['success'] = 1;
						$ret['state'] = 'edit';
						$ret['rename'] = 1;
						t3_set_message(t('@name layout was successfully renamed.', array('@name'=>$name)),'status');
						$ret['message'] = t3_get_messages();
					}else{
						$ret['success'] = 0;
						$ret['state'] = 'edit';
						$ret['rename'] = 1;
						t3_set_message(t('@name layout cannot be renamed.', array('@name'=>$name)));
						$ret['message'] = t3_get_messages();
					}
				}else{
					$ret = array();
					$ret['success'] = 0;
					t3_set_message(t('Layout "@name" already exists. Please enter another layout name.', array('@name'=>$new_name)));
					$ret['message'] = t3_get_messages();

					echo drupal_to_js($ret);
					return;
				}
			}
		} else {
			$file = $theme_path . '/local/layouts/' . $new_name . '.xml';

			if(!file_exists($theme_path . '/local/layouts')){
				if(t3_create_folder($theme_path . '/local/layouts') !== true){
					$ret['success'] = 0;
					$ret['message'] = t3_get_messages();
					echo drupal_to_js($ret);
					return;
				}
			}

			t3_file_save_data($content, $file);
			$ret['success'] = 1;
			$ret['state'] = 'new';
			$ret['type'] = 'local';
			t3_set_message(t('<strong>@name</strong> was successfully added!',array('@name'=>$new_name)),'status');
			$ret['message'] = t3_get_messages();
		}


		echo drupal_to_js($ret);
	}

	function delete_layout(){
		$name = $_POST['name'];
		$theme = $_POST['theme'];
		$theme_path = drupal_get_path('theme', $theme);
		$layout_file = $theme_path . '/local/layouts/' . $name . '.xml';
		$result = 0;
		if (file_exists($layout_file)) {
			if (! @unlink($layout_file)) {
				t3_set_message(t("Cannot delete the @name layout..", array('@name'=>$name)));
				$ret = array(
				'success' => 0, 
				'message' => t3_get_messages());
				echo drupal_to_js($ret);
			} else {
				$ret = array('success' => 1, 'name' => $name);
				t3_set_message(t('The @name layout was successfully deleted!.', array('@name'=>$name)),'status');
				$ret['message'] = t3_get_messages();
				echo drupal_to_js($ret);
			}
		} else {
			t3_set_message(t("The @name layout cannot be found..", array('@name'=>$name)));

			$ret = array('success' => 0, 'message' => t3_get_messages());

			echo drupal_to_js($ret);
		}
		return;
	}

	function delete_layout_profile($layout, $theme){
		$theme_path = drupal_get_path('theme', $theme);

		//we need to get all of local profile
		$profile_path = $theme_path.'/local/profiles';

		//find all profile
		$files = file_scan_directory($profile_path,'.ini');
		foreach ($files as $path=>$file_info){
			$rewrite = false;

			$profile = drupal_parse_info_file($path);

			if($profile['layout_desktop'] && $profile['layout_desktop'] == $layout){
				$rewrite = true;
				unset($profile['layout_desktop']);
			}
		}
	}
	function reset_layout(){
		global $t3_base_theme_path;

		$name = $_POST['name'];
		$theme = $_POST['theme'];
		$theme_path = drupal_get_path('theme', $theme);
		$layout_file = $theme_path . '/local/layouts/' . $name . '.xml';
		$core_layout_file = $theme_path . '/core/layouts/' . $name . '.xml';

		$base_theme_source_file = $t3_base_theme_path . '/layouts/' . $name . '.xml';

		if (! file_exists($core_layout_file)) $core_layout_file = $base_theme_source_file;
		if (! file_exists($core_layout_file)) {
			t3_set_message(t("This layout is not a core layout."));
			$ret = array('success' => 0, 'message' => t3_get_messages());
			echo drupal_to_js($ret);
			return;
		}
		$result = 0;
		if (file_exists($layout_file)) {
			$result = @unlink($layout_file);
			if (! $result) {
				t3_set_message(t("This layout cannot be deleted or you have not enough permissions to perform this operation."));
				$ret = array(
				'success' => 0, 
				'error' => t3_get_messages());
				echo drupal_to_js($ret);
			} else {
				$content = file_get_contents($core_layout_file);
				$ret = array('success' => 1, 'name' => $name, 'content' => $content);
				t3_set_message(t('The @name layout was successfully reset to default settings!', array('@name'=>$name)), 'status');
				$ret['message'] = t3_get_messages();
				echo drupal_to_js($ret);
			}
		} else {
			t3_set_message(t("The @name layout does not exit..", array('@name'=>$name)));
			$ret = array('success' => 0, 'message' => t3_get_messages());
			echo drupal_to_js($ret);
		}
		return;
	}

	function clone_layout(){
		global $base_path, $t3_base_theme_path;
		$t3_layout_source = $_POST['t3_layout_source'];
		$name = $_POST['t3_layout_simple_name'];
		$theme = $_POST['theme'];
		$theme_path = drupal_get_path('theme', $theme);
		//find source file
		$local_source_file = $theme_path . '/local/layouts/' . $t3_layout_source . '.xml';
		$core_source_file = $theme_path . '/core/layouts/' . $t3_layout_source . '.xml';

		$source_file = file_exists($local_source_file) ? $local_source_file : $core_source_file;

		$base_theme_source_file = $t3_base_theme_path . '/layouts/' . $t3_layout_source . '.xml';

		$source_file = !file_exists($source_file) ? $base_theme_source_file : $source_file;

		if(!file_exists($source_file)){
			$ret = array();

			$ret['success'] = 0;
			t3_set_message(t('The @name layout cannot be found..', array('@name'=>$t3_layout_source)));
			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			exit;
		}

		$des_file = $theme_path . '/local/layouts/' . $name . '.xml';

		if(!file_exists($theme_path . '/local/layouts')){
			t3_create_folder($theme_path . '/local/layouts');
		}

		$ret = @copy($source_file, $des_file);
		if ($ret) {
			$ret = array();
			$ret['success'] = 1;
			$ret['name'] = $name;
			$ret['content'] = file_get_contents($des_file);
			t3_set_message(t('@name layout was cloned successfully.', array('@name'=>$name, '@oldname'=>$t3_layout_source)),'status');
			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
		} else {
			$ret = array('success' => 0);
			t3_set_message(t('Cannot copy file!'));
			$ret['message'] = t3_get_messages();

			echo drupal_to_js($ret);
		}
		return;
	}


	function add_profile(){
		$theme_key = $_POST['theme_key'];
		$theme_path = drupal_get_path('theme', $theme_key);
		$name = $_POST['profile'];

		$dir = $theme_path.'/local/profiles';
		$file = $dir.'/'.$name.'.ini';

		$ret = array();
		if(file_exists($file)){
			t3_set_message(t('The @name profile already exists!', array('@name'=>$name)));
			$ret['message'] = t3_get_messages();
			$ret['success'] = 0;

			echo drupal_to_js($ret);
			return;
		}

		//before save file we need check folder exits first
		//if have not exits, need create it
		if(!file_exists($dir) && !is_dir($dir)){
			if(t3_create_folder($dir) !== true){
				$ret['success'] = 0;
				$ret['message'] = t3_get_messages();
				echo drupal_to_js($ret);
				return;
			}
		}
		//ok now save file
		if(t3_file_save_data('', $file)){
			$ret['success'] = 1;
			$ret['name'] = $name;
			t3_set_message(t('@name profile was successfully created! Now you can customize it.', array('@name'=>$name)), 'status');

			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			return;
		}else{
			$ret['success'] = 0;
			t3_set_message(t('Cannot create the @name.ini file..', array('@name'=>$name)));

			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			return;
		}
	}

	function delete_profile(){
		$theme_key = $_POST['theme_key'];
		$theme_path = drupal_get_path('theme', $theme_key);
		$name = $_POST['profile'];

		$file = $theme_path.'/local/profiles/'.$name.'.ini';

		$ret = array();
		if(!file_exists($file)){
			t3_set_message(t('The @name profile  does not exists!', array('@name'=>$name)));
			$ret['message'] = t3_get_messages();
			$ret['success'] = 0;

			echo drupal_to_js($ret);
			return;
		}

		//ok now save file
		if(@unlink($file)){
			$ret['success'] = 1;
			$ret['name'] = $name;
			t3_set_message(t('Profile @name was successfully deleted.', array('@name'=>$name)), 'status');
			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			return;
		}else{
			$ret['success'] = 0;
			t3_set_message(t('Cannot delete the @name.ini file..', array('@name'=>$name)));

			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			return;
		}
	}

	function clone_profile(){
		$theme_key = $_POST['theme_key'];
		$theme_path = drupal_get_path('theme', $theme_key);
		$name = $_POST['profile'];
		$source = $_POST['source'];

		$dir = $theme_path.'/local/profiles';
		$file = $dir.'/'.$name.'.ini';


		$source_file = $theme_path.'/local/profiles/'.$source.'.ini';

		if(!file_exists($source_file)) {
			$source_file = $theme_path.'/core/profiles/'.$source.'.ini';
		}



		if(!file_exists($source_file)){
			t3_set_message(t('The @name profile does not exist!', array('@name'=>$source)));
			$ret['message'] = t3_get_messages();
			$ret['success'] = 0;

			echo drupal_to_js($ret);
			return;
		}

		$ret = array();
		if(file_exists($file)){
			t3_set_message(t('The @name profile already exists!', array('@name'=>$name)));
			$ret['message'] = t3_get_messages();
			$ret['success'] = 0;

			echo drupal_to_js($ret);
			return;
		}


		//ok now save file

		$content = file_get_contents($source_file);

		if(!file_exists($dir) || !is_dir($dir)){
			if(t3_create_folder($dir) !== true){
				$ret['success'] = 0;
				$ret['message'] = t3_get_messages();

				echo drupal_to_js($ret);
				return;
			}
		}

		if(t3_file_save_data($content, $file)){
			$ret['success'] = 1;
			$ret['name'] = $name;
			$ret['source'] = $source;

			t3_set_message(t('The @name profile was successfully cloned!', array('@name'=>$name)),'status');
			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			return;
		}else{
			$ret['success'] = 0;
			t3_set_message(t('Cannot create the @name.ini file..', array('@name'=>$name)));

			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			return;
		}
	}

	function rename_profile(){
		$theme_key = $_POST['theme_key'];
		$theme_path = drupal_get_path('theme', $theme_key);
		$name = $_POST['profile'];
		$source = $_POST['source'];

		$file = $theme_path.'/local/profiles/'.$name.'.ini';

		$source_file = $theme_path.'/local/profiles/'.$source.'.ini';

		if(!file_exists($source_file)) {
			if(file_exists($source_file = $theme_path.'/core/profiles/'.$source.'.ini')){
				t3_set_message(t('Cannot rename a core profile!'));
				$ret['message'] = t3_get_messages();
				$ret['success'] = 0;

				echo drupal_to_js($ret);
				return;
			}
		}

		if(!file_exists($source_file)){
			t3_set_message(t('The @name profile does not exist!', array('@name'=>$source)));

			$ret['message'] = t3_get_messages();
			$ret['success'] = 0;

			echo drupal_to_js($ret);
			return;
		}

		$ret = array();
		if(file_exists($file)){
			t3_set_message(t('The @name profile already exists!', array('@name'=>$name)));

			$ret['message'] = t3_get_messages();
			$ret['success'] = 0;

			echo drupal_to_js($ret);
			return;
		}


		//ok now save file
		if(@rename($source_file, $file)){
			$ret['success'] = 1;
			$ret['name'] = $name;
			$ret['source'] = $source;
			t3_set_message(t('Profile "@name" was successfully renamed!', array('@name'=>$name)),'status');


			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			return;
		}else{
			$ret['success'] = 0;
			t3_set_message(t('Cannot create the @name.ini file..', array('@name'=>$name)));

			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			return;
		}
	}

	function reset_profile(){
		$theme_key = $_POST['theme_key'];
		$theme_path = drupal_get_path('theme', $theme_key);
		$name = $_POST['profile'];

		$file = $theme_path.'/local/profiles/'.$name.'.ini';
		//for value to reset at front end
		$core_file = $theme_path.'/core/profiles/'.$name.'.ini';

		$ret = array();
		if(!file_exists($file)){
			t3_set_message(t('The @name profile cannot be overwritten!', array('@name'=>$name)));
			$ret['message'] = t3_get_messages();

			$ret['success'] = 0;

			echo drupal_to_js($ret);
			return;
		}

		//ok now save file
		if(@unlink($file)){
			$ret['success'] = 1;
			$ret['name'] = $name;
			t3_set_message(t('The @name profile was successfully reset to default settings.', array('@name'=>$name)),'status');

			$ret['value'] = drupal_parse_info_file($core_file);
			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			return;
		}else{
			$ret['success'] = 0;
			t3_set_message(t('Cannot delete the @name.ini file..', array('@name'=>$name)));
			$ret['message'] = t3_get_messages();

			echo drupal_to_js($ret);
			return;
		}
	}

	function save_profile($theme_path){
		$data = $_POST['profile'];
		$ignore = array('t3_task', 'var');
		$data = t3_ignore_array($data, $ignore);

		//get default profile first
		$default_profile = drupal_parse_info_file($theme_path.'/core/profiles/default.ini');
		$default_core_profile = $default_profile;
		if(file_exists($theme_path.'/local/profiles/default.ini')){
			$default_profile =  drupal_parse_info_file($theme_path.'/local/profiles/default.ini');
		}

		$ret = array();

		foreach ($data as $profile_name => $profile_info) {
			unset($profile_info['block_suffix']);
			$file = $theme_path . '/local/profiles/' . $profile_name . '.ini';
			unset($profile_info['profile_type']);

			if (! file_exists($file)) {
				$file = $theme_path . '/core/profiles/' . $profile_name . '.ini';
			}
			if (file_exists($file)) {

				$info = drupal_parse_info_file($file);
				if (t3_compare_array($profile_info, $info)) {
					$file = $theme_path . '/local/profiles/' . $profile_name . '.ini';
					if(!$this->save_array($profile_info, $file)){
						$ret['success'] = 0;
						$ret['message'] = t3_get_messages();

						echo drupal_to_js($ret);
						exit;
					}
				}

			} else {
				$file = $theme_path . '/local/profiles/' . $profile_name . '.ini';
				if(!$this->save_array($profile_info, $file)){
					$ret['success'] = 0;
					$ret['message'] = t3_get_messages();

					echo drupal_to_js($ret);
					exit;
				}
			}
		}
	}

	function save_general($theme_path, $theme_key){
		$general = array();
		//get old general first
		$core_general = array();
		$core_general_file = $theme_path.'/core/general.ini';
		if(file_exists($core_general_file)){
			$core_general = drupal_parse_info_file($core_general_file);
			$general = $core_general;
		}

		$local_general = array();
		$local_general_file = $theme_path.'/local/general.ini';
		if(file_exists($local_general_file)){
			$local_general = drupal_parse_info_file($local_general_file);
			$general = $local_general;
		}


		$user_general = $_REQUEST['general'];

		if(t3_compare_array($general, $user_general)){
			//save data here
			if(!$this->save_array($user_general, $local_general_file)){
				$ret['success'] = 0;
				$ret['message'] = t3_get_messages();

				echo drupal_to_js($ret);
				exit;
			}else{
				$ret['success'] = 1;
				$ret['message'] = t3_get_messages();

				echo drupal_to_js($ret);
				exit;
			}
		}

		//save devmode
		$devmode = $_POST['devmode'];
		variable_set($theme_key.'_devmode', $devmode);
	}

	function save_layout($theme_path){
		$data = $_POST['layout'];
		foreach ($data as $layout_name => $layout_info) {
			$content = $layout_info['content'];
			$file = $theme_path . '/local/layouts/' . $layout_name . '.ini';
			if (! file_exists($file)) {
				$file = $theme_path . '/core/layouts/' . $layout_name . '.ini';
			}
			if (file_exists($file)) {
				$info = file_get_contents($file);
				if ($content != $info) {
					$file = $theme_path . '/local/layouts/' . $layout_name . '.ini';
					t3_file_save_data($content, $file);
				}
			} else {
				$file = $theme_path . '/local/layouts/' . $layout_name . '.ini';
				t3_file_save_data($content, $file);
			}
		}
	}

	function save_array($data,$file){
		global $base_path;
		$text = $this->parse_array($data);

		return t3_file_save_data($text, $file);
	}

	function parse_array($array,$name = '',$text = ''){
		if(!is_array($array)) return $text;

		foreach ($array as $child_name => $value) {
			if(is_array($value)){
				$child_name = $name == '' ? $child_name : $name . '[' . $child_name . ']';
				$text .= $this->parse_array($value, $child_name, $text);
			}else if($value == null || trim($value)==''){
				continue;
			}else{
				$text .= $name == '' ? $child_name . ' = ' . $value . "\n" : $name . '[' . $child_name .
				 '] = ' . $value . "\n";
			}

		}
		return $text;
	}


	function upload_theme(){
		global $base_path, $base_root, $base_url;
		t3_import('core/libs/object');
		t3_import('core/libs/simplexml');
		t3_import('core/libs/archive/zip');
		t3_import('core/xml');

		$T3Xml = T3Xml::getInstance();

		$theme = $_REQUEST['theme'];

		$theme_path = drupal_get_path('theme', $theme);

		$des = $theme_path . '/local/themes';

		$core_path = $theme_path . '/core/themes';

		$tmp_dir = file_directory_temp();


		$tmp_dir = $tmp_dir . '/' . md5(rand());
		$ret = array();

		$archive = new JArchiveZip();

		//need to upload zip file to tmp folder
		$tmp_zip = file_directory_temp() . '/'.$_GET['qqfile'];

		if (isset($_GET['qqfile'])){
			$tmp_zip = file_directory_temp() . '/'.$_GET['qqfile'];
			$ufile = new UploadFileXhr();
		} elseif (isset($_FILES['qqfile'])){
			$tmp_zip = file_directory_temp() . '/'.$_FILES['qqfile']['name'];
			$ufile = new UploadFileForm();
		} else {
			return array('success'=>false);
		}
		
		if(!$ufile->save($tmp_zip)){
			t3_set_message(t('Cannot upload file.') . $tmp_zip);

			$ret = array();
			$ret['success'] = 0;
			$ret['message'] = t3_get_messages();

			echo drupal_to_js($ret);
			exit;
		}

		$result = $archive->extract($tmp_zip, $tmp_dir);


		//now delete temp folder uploaded zip file
		@unlink($tmp_zip);


		//echo $_FILES['sub_theme']['tmp_name'];
		if(!$result){
			$ret['success'] = 0;
			//t3_set_message(t('Theme @name already exists.', array('@name'=>$dir)));
			$ret['message'] = t3_get_messages();
			echo drupal_to_js($ret);
			exit();
		}

		$files = file_scan_directory($tmp_dir, 'info.xml');
		$is_subtheme = false;


		foreach ($files as $path => $file_info) {
			$info = $T3Xml->parse($path);
			$info = $info->document;
			//$info = $info->document;
			if (strtolower($info->attributes('engine')) == 'joomlart' ) {
				if(isset($info->name)){
					$dir = t3_valid_name($info->name[0]->data());
				}

				if($dir == ''){
					$ret['success'] = 0;
					t3_set_message(t('Theme name is not defined!'));
					$ret['message'] = t3_get_messages();
					echo drupal_to_js($ret);
					exit();
				}

				if (file_exists($des . '/' . $dir)) {
						
					$ret['success'] = 0;
					t3_set_message(t('The @name theme already exists. Please use a different theme name.', array('@name'=>$dir)));
					$ret['message'] = t3_get_messages();
					echo drupal_to_js($ret);
					exit();
				} else {
					if(t3_copy_folder(dirname($path), $des . '/' . $dir) != true){
						$ret['success'] = 0;
						$ret['message'] = t3_get_messages();
						echo drupal_to_js($ret);
						exit();
					}
				}
				$is_subtheme = true;
				break;
			}
		}
		if (! $is_subtheme) {
			$ret['success'] = 0;
			t3_set_message(t('The info.xml file was not found.'));
			$ret['message'] = t3_get_messages();

			echo drupal_to_js($ret);
			exit();
		}
		//parse info here
		$ret = array();
		$ret['theme_folder'] = $dir;
		$ret['success'] = true;
		$ret['version'] = $info->version[0]->data();
		$ret['author'] = $info->author[0]->data();
		t3_set_message(t('The @name theme was successfully uploaded!', array('@name'=>$dir)), 'status');
		$ret['message'] = t3_get_messages();
		echo drupal_to_js($ret);
	}

	function delete_theme(){
		$name = $_POST['name'];
		$theme = $_POST['theme'];
		$theme_path = drupal_get_path('theme', $theme);
		$theme_folder = $theme_path . '/local/themes/' . $name;
		$result = 0;
		if (file_exists($theme_folder) && is_dir($theme_folder)) {
			$result = t3_delete_folder($theme_folder);
			if (! $result) {

				$ret = array(
				'success' => 0, 
				'message' => t3_get_messages());
				echo drupal_to_js($ret);
			} else {
				$ret = array('success' => 1, 'name' => $name);
				t3_set_message(t('The @name theme was successfully deleted.', array('@name'=>$name)),'status');
				$ret['message'] = t3_get_messages();
				echo drupal_to_js($ret);
			}
		} else {
			$ret = array('success' => 0, 'error' => t("The @name theme does not exit.", array('@name'=>$name)));
			echo drupal_to_js($ret);
		}
		return;
	}

	function save_menu(){
		$menu_style = $_REQUEST['style'];
		//$menu_name = $t3_profile['menu'];
		t3_import('libraries/t3');
		t3_import('core/menus/base.class');
		t3_import('core/menus/' . $menu_style . '.class');
		$menu_class = 'JD' . ucfirst($menu_style) . 'Menu';
		if (class_exists($menu_class)) {
			$t3_menu = new $menu_class($menu_style);
		}
		$t3_menu->saveItems($_POST['id']);
	}
}

class UploadFileXhr {
	function save($path){
		if(!($input = fopen("php://input", "r"))){
			return false;
		}
		if(!($fp = fopen($path, "w"))){
			return false;
		}
		while ($data = fread($input, 1024)){
			fwrite($fp,$data);
		}
		fclose($fp);
		fclose($input);
		return true;
	}
	function getName(){
		return $_GET['qqfile'];
	}
	function getSize(){
		$headers = apache_request_headers();
		return (int)$headers['Content-Length'];
	}
}

class UploadFileForm {
	function save($path){
		return @move_uploaded_file($_FILES['qqfile']['tmp_name'], $path);
	}
	function getName(){
		return $_FILES['qqfile']['name'];
	}
	function getSize(){
		return $_FILES['qqfile']['size'];
	}
}
?>
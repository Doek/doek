<?php
function t3_init($template){
	global $theme_info, $theme_path, $base_theme_info;
	$T3Cache =& T3Cache::getInstance();
	//important we need init path first
	$T3Profile =& T3Profile::getInstance();
	$T3Xml = T3Xml::getInstance();
	$themes = $T3Profile->getThemes();

	$rebuild = true;
	//need to cache here because there is some file_exits command
	//it take much time to process

	$paths = $T3Cache->getCache('path', 'paths');

	if($paths) {
		$rebuild =false;
		T3Path::setPaths($paths);
	}

	//if need to rebuild
	if($rebuild){
		//first we need add path for this theme
		$path = dirname($template->filename);


		T3Path::addPath($path);
		T3Path::addPath($path,'layout');
		T3Path::addPath($path.'/core','layout');
		T3Path::addPath($path.'/local','layout');

		if($template->base_theme){
			$themes = $T3Profile->getThemes();
			//now, add sub-themes path
			foreach ($themes as $theme){
				//we need to add core theme first
				if(file_exists($theme_path.'/core/themes/'.$theme)){
					T3Path::addPath($theme_path.'/core/themes/'.$theme);
				}
				//local theme override core theme
				if(file_exists($theme_path.'/local/themes/'.$theme)){
					T3Path::addPath($theme_path.'/local/themes/'.$theme);
				}
			}
		}
		//check more time, if in cache mode, store data to cache
		if($template->name != 'core_jdt3'){
		  $T3Cache->setCache('path', 'paths', T3Path::getPaths());
		}
	}
	//Ok, now add css and js
	$stylesheets = $template->stylesheets;
	foreach ($stylesheets as $media=>$files){
		foreach ($files as $file=>$url){
			t3_add_css($file, 'theme', $media);
		}
	}


	//now, go throught themes
	foreach ($themes as $theme){
		$sub_theme_info = array();

		if(file_exists($theme_info_file = $theme_path.'/local/themes/'.$theme.'/info.xml')){
			//try to parse by xml
			$sub_theme_info = $T3Xml->parse($theme_info_file);
		}else{
			if(file_exists($theme_info_file = $theme_path.'/core/themes/'.$theme.'/info.xml')){
				$sub_theme_info = $T3Xml->parse($theme_info_file);
			}
		}

		if($stylesheets = $sub_theme_info->document->stylesheets[0]){
			foreach ($stylesheets->file as $oFile){
				t3_add_css($oFile->data(), $oFile->attributes('media'));
			}
		}

		if($scripts = $sub_theme_info->document->scripts[0]){
			if(is_array($scripts->file)){
				foreach ($scripts->file as $oFile){
					t3_add_js($oFile->data());
				}
			}
		}
	}
}

function _t3_reprocess_css(&$vars){
	global $base_theme_info;

	$styles = $vars['css'];

	foreach ($styles as $media => $old_pre_styles) {
		// Add the non-colored stylesheet first as we might not find a
		// re-colored stylesheet for replacement later.
		foreach ($old_pre_styles as $type=>$files){
			$new_theme_css = array();
			foreach ($files as $old_path=>$old_preprocess){

				if(is_array($base_theme_info[0]->stylesheets[$media])){
					if(!in_array($old_path, $base_theme_info[0]->stylesheets[$media]) || $type != 'theme'){
						$new_theme_css[$old_path] = $old_preprocess;
					}
				}
			}

			$vars['css'][$media][$type] = $new_theme_css;
		}
	}
	$vars['styles'] = drupal_get_css($vars['css']);
}

function _t3_reprocess_js(&$vars){
	global $base_theme_info;

	if(!$base_theme_info) return ;
	$scripts = array();
	$scripts['theme'] = array();
	$scripts['theme']['header']= array();
	foreach ($base_theme_info[0]->scripts as $file=>$path){
		$scripts['theme']['header'][$path] = array('cache' => TRUE, 'defer' => NULL, 'preprocess' => TRUE);
	}

	$scripts = drupal_get_js('header', $scripts);
}
function t3_get_layout(){
	static $layout = array();

	if(empty($layout)){
		$t3_device = T3Util::getDevice();
		$T3Profile = T3Profile::getInstance();

		$name = $T3Profile->getLayout($t3_device);

		//get default layout first
		$default = _t3_parse_layout('default');
		$layout = _t3_parse_layout($name);

		$layout = array_merge($default, $layout);
	}
	return $layout;
}

function t3_get_region_style($rname){
	static $styles = array();

	if(isset($styles[$rname])) return $styles[$rname];

	$layout = t3_get_layout();

	foreach ($layout as $name =>$blocks){
		$style = '';
		if($blocks['#style']) $style = $blocks['#style'];
		if($blocks['#children']){
			if(is_array($blocks['#children'])){
				foreach ($blocks['#children'] as $bname=>$block){
					if($block['#data']){
						$block_style = $block['#style']? $block['#style'] : $style;
						$data = $block['#data'];
						$regions = preg_split("/[\s,]+/", $data);
						foreach ($regions as $region){
							if(!isset($styles[$region])){
								$styles[$region] = $block_style;
							}
						}
					}
				}
			}
		}
	}

	return $styles[$rname];
}

function t3_get_block_style($name){
	static $styles = array();

	if(isset($styles[$name])) return $styles[$name];

	$layout = t3_get_layout();

	$style = $layout['middle']['#style'] ? $layout['middle']['#style'] : '';
	if(is_array($children = $layout['middle']['#children'])){
		foreach($children as $name=>$block){
			$styles[$name] = $block['#style'] ? $block['#style'] : $style;
		}
	}

	return $styles[$name];
}

function t3_get_base_width($name){
	//params always init in contructor, so just need to get here
	$layout = t3_get_layout();


	//if have not this block, sure, it's column widht = 0
	if(!isset($layout['middle']['#children'][$name]['#data'])) return 0;

	if($layout['middle']['#children'][$name]['#width']) {
		return $layout['middle']['#children'][$name]['#width'];
	}

	//else we can get from middle blocks
	if($layout['middle']['#colwidth']){
		return $layout['middle']['#colwidth'];
	}

	//default is 20
	return 20;
}

function t3_has_block($name, $vars){
	//params always init from contructor, so just need to get here
	$layout = t3_get_layout();

	//not defined this block in layout, sure it has not this block
	if(!isset($layout['middle']['#children'][$name]['#data'])){
		return false;
	}

	$regions = $layout['middle']['#children'][$name]['#data'];
	$regions = preg_split('/[\s,]+/', $regions);

	foreach ($regions as $region) {
		if($vars[$region]) return true;
	}

	return false;
}

function t3_get_colwidth($vars){
	static $colwidth = array();


	if(!empty($colwidth)) return $colwidth;

	//Left
	$l = $l1 = $l2 = 0;
	if (t3_has_block('left-mass-top', $vars) || t3_has_block('left-mass-bottom', $vars) || (t3_has_block(
		'left1', $vars) && t3_has_block('left2', $vars))) {
	$l = 2;
	$l1 = t3_get_base_width('left1');
	$l2 = t3_get_base_width('left2');
		} else
		if (t3_has_block("left1", $vars)) {
			$l = 1;
			$l1 = t3_get_base_width('left1');
		} else
		if (t3_has_block("left2", $vars)) {
			$l = 1;
			$l2 = t3_get_base_width('left2');
		}
		$cls_l = $l ? "l$l" : "";
		$l = $l1 + $l2;
		//right
		$r = $r1 = $r2 = 0;
		if (t3_has_block("right-mass-top", $vars) || t3_has_block("right-mass-bottom", $vars) || (t3_has_block(
	"right1", $vars) && t3_has_block("right2", $vars))) {
		$r = 2;
		$r1 = t3_get_base_width('right1');
		$r2 = t3_get_base_width('right2');
	} else
	if (t3_has_block("right1", $vars)) {
		$r = 1;
		$r1 = t3_get_base_width('right1');
	} else
	if (t3_has_block("right2", $vars)) {
		$r = 1;
		$r2 = t3_get_base_width('right2');
	}
	$cls_r = $r ? "r$r" : "";
	$r = $r1 + $r2;
	//inset
	$i1 = $i2 = 0;
	if (t3_has_block("inset1", $vars))
	$i1 = t3_get_base_width('inset1');
	if (t3_has_block("inset2", $vars))
	$i2 = t3_get_base_width('inset2');
	//width
	$total_width = 100;
	if(T3Util::isIE()){
		$total_width = 99.99;
	}
	$colwidth['r'] = $r;
	if ($r) {
		$colwidth['r1'] = round($r1 * 100 / $r);
		$colwidth['r2'] = $total_width - $colwidth['r1'];
	}
	$colwidth['mw'] = $total_width - $r;
	$m = $total_width - $l - $r;
	$colwidth['l'] = ($l + $m) ? round($l * 100 / ($l + $m)) : 0;
	if ($l) {
		$colwidth['l1'] = round($l1 * 100 / $l);
		$colwidth['l2'] = $total_width - $colwidth['l1'];
	}
	$colwidth['m'] = $total_width - $colwidth['l'];
	$c = $m - $i1 - $i2;
	$colwidth['i2'] = round($i2 * 100 / $m);
	$colwidth['cw'] = $total_width - $colwidth['i2'];
	$colwidth['i1'] = ($c + $i1) ? round($i1 * 100 / ($c + $i1)) : 0;
	$colwidth['c'] = $total_width - $colwidth['i1'];
	$cls_li = t3_has_block("inset1", $vars) ? 'l1' : '';
	$cls_ri = t3_has_block("inset1", $vars) ? 'r1' : '';
	$colwidth['cls_w'] = ($cls_l || $cls_r) ? "ja-$cls_l$cls_r" : "";
	$colwidth['cls_m'] = ($cls_li || $cls_ri) ? "ja-$cls_li$cls_ri" : "";
	$colwidth['cls_l'] = t3_has_block("left1", $vars) && t3_has_block("left2", $vars) ? "ja-l2" : (t3_has_block(
	"left1", $vars) || t3_has_block("left2", $vars) ? "ja-l1" : "");
	$colwidth['cls_r'] = t3_has_block("right1", $vars) && t3_has_block("right2", $vars) ? "ja-r2" : (t3_has_block(
	"right1", $vars) || t3_has_block("right2", $vars) ? "ja-r1" : "");

	return $colwidth;
}

function _t3_parse_layout($name){
	static $layouts = array();

	//return if this layout has parsed
	if(!empty($layouts[$name])) return $layouts[$name];

	//continue work on parse layout
	$T3Xml = T3Xml::getInstance();
	$layout_path = T3Path::getLayout($name);
	$xml = $T3Xml->parse($layout_path);
	$params = array();

	//get blocks
	if($xml->document){
		$blocks = $xml->document->blocks;
		if(is_array($blocks)){
			foreach ($blocks as $oBlock){
				$params[$oBlock->attributes('name')] = array();
				if($attrs = $oBlock->attributes()){
					foreach ($attrs as $name=>$value){
						$params[$oBlock->attributes('name')]['#'.$name] = $value;
					}
				}

				$children = array();
				if(is_array($oBlock->block)){
					foreach ($oBlock->block as $block){
						$children[$block->attributes('name')] = array();

						if($attrs = $block->attributes()){
							foreach ($attrs as $name=>$value){
								$children[$block->attributes('name')]['#'.$name] = $value;
							}
						}

						$children[$block->attributes('name')]['#data'] = $block->data();
					}
				}

				$params[$oBlock->attributes('name')]['#children'] = $children;
			}
		}

		//check for extension css and js
		$ss = array();
		$stylesheets = $xml->document->stylesheets;
		if($stylesheets && is_array($stylesheets)){
			$files = $stylesheets[0]->file;
			if(is_array($files)){
				foreach ($files as $file){
					if($src = $file->data()){
						$ss[] = $src;
					}
				}
			}
		}

		//check for extension css and js
		$sc = array();
		$scripts = $xml->document->scripts;
		if($scripts && is_array($scripts)){
			$files = $scripts[0]->file;
			if(is_array($files)){
				foreach ($files as $file){
					if($src = $file->data()){
						$sc[] = $src;
					}
				}
			}
		}

		$params['#style'] = $ss;
		$params['#script'] = $sc;

		$attrs = $xml->document->attributes();
		if(is_array($attrs)){
			foreach ($attrs as $name=>$value){
				$params['#'.$name] = $value;
			}
		}

		$layouts[$name] = $params;
	}

	return $layouts[$name];
}

function _t3_gen_block($block){
	if(!$block['#type']) $block['#type'] = 'middle';
	$block_round_file = T3Path::getBlockRound($block['#type']);

	ob_start();
	include ($block_round_file);

	$contents = ob_get_contents();   // Get the contents of the buffer
	ob_end_clean();

	$contents = preg_split('/[\s,]+{content}[\s,]+/', $contents);

	return $contents;
}

function _t3_cal_spotlight($spotlight, $totalwidth = 100,$specialwidth = 0,$special = 'left', $vars){
	/********************************************
	 $spotlight = array ('position1', 'position2',...)
	 *********************************************/
	$modules = array();
	$modules_s = array();
	foreach ($spotlight as $region) {
		if ($vars[$region]) {
			$modules_s[ ] = $region;
		}
		$modules[$region] = array('class' => '-full', 'width' => $totalwidth . '%');
	}
	if (! count($modules_s))
	return null;
	if ($specialwidth) {
		if (count($modules_s) > 1) {
			$width = round(($totalwidth - $specialwidth) / (count($modules_s) - 1), 2) . "%";
			$specialwidth = $specialwidth . "%";
		} else {
			$specialwidth = $totalwidth . "%";
		}
	} else {
		$width = (round($totalwidth / (count($modules_s)), 2)) . "%";
		$specialwidth = $width;
	}
	if (count($modules_s) > 1) {
		$modules[$modules_s[0]]['class'] = "-left";
		$modules[$modules_s[0]]['width'] = ($special == 'left' || $special == 'first') ? $specialwidth : $width;
		$modules[$modules_s[count($modules_s) - 1]]['class'] = "-right";
		$modules[$modules_s[count($modules_s) - 1]]['width'] = ($special != 'left' && $special !=
		 'first') ? $specialwidth : $width;
		for ($i = 1; $i < count($modules_s) - 1; $i ++) {
			$modules[$modules_s[$i]]['class'] = "-center";
			$modules[$modules_s[$i]]['width'] = $width;
		}
	}
	return $modules;
}

//////////////////////////////////////////////////////////////////////
/////////////////////// CSS and JS Function //////////////////////////
//////////////////////////////////////////////////////////////////////
function t3_add_css($path = NULL, $type = 'theme', $media = 'all', $preprocess = TRUE) {
	static $css = array();

	// Create an array of CSS files for each media type first, since each type needs to be served
	// to the browser differently.
	if (isset($path)) {
		// This check is necessary to ensure proper cascading of styles and is faster than an asort().
		if (!isset($css[$media])) {
			$css[$media] = array('module' => array(), 'theme' => array());
		}
		$css[$media][$type][$path] = $preprocess;
	}

	return $css;
}

function t3_get_css($css = NULL) {
	global $language;
	//get browser version here
	t3_import('core/util');
	$bname = T3Util::getBrowserSortName();
	$bver = T3Util::getBrowserMajorVersion();

	$output = '';
	if (!isset($css)) {
		$css = t3_add_css();
	}

	$no_module_preprocess = '';
	$no_theme_preprocess = '';

	$preprocess_css = (variable_get('preprocess_css', FALSE) && (!defined('MAINTENANCE_MODE') || MAINTENANCE_MODE != 'update'));
	$directory = file_directory_path();
	$is_writable = is_dir($directory) && is_writable($directory) && (variable_get('file_downloads', FILE_DOWNLOADS_PUBLIC) == FILE_DOWNLOADS_PUBLIC);

	// A dummy query-string is added to filenames, to gain control over
	// browser-caching. The string changes on every update or full cache
	// flush, forcing browsers to load a new copy of the files, as the
	// URL changed.
	$query_string = '?'. substr(variable_get('css_js_query_string', '0'), 0, 1);

	$device = T3Util::getDevice();
	$ie = T3Util::getIE();

	$arr_media = array('screen', 'all','print');
	if($ie) $arr_media = array_merge($arr_media, array('ie'.$ie));
	if($device) $arr_media = array_merge($arr_media, array('handheld', $device));

	foreach ($css as $media => $types) {
			
		if(!in_array($media, $arr_media)) continue;
			
		$output .= '<style type="text/css" media="'.$media.'">';
			
		if($device && $device != 'handheld' && $media == $device) $media = 'handheld';
		// If CSS preprocessing is off, we still need to output the styles.
		// Additionally, go through any remaining styles if CSS preprocessing is on and output the non-cached ones.
		foreach ($types as $type => $files) {
			if ($type == 'module') {
				// Setup theme overrides for module styles.
				$theme_styles = array();
				foreach (array_keys($css[$media]['theme']) as $theme_style) {
					$theme_styles[] = basename($theme_style);
				}
			}
			foreach ($types[$type] as $file => $preprocess) {
				// If the theme supplies its own style using the name of the module style, skip its inclusion.
				// This includes any RTL styles associated with its main LTR counterpart.
				if ($type == 'module' && in_array(str_replace('-rtl.css', '.css', basename($file)), $theme_styles)) {
					// Unset the file to prevent its inclusion when CSS aggregation is enabled.
					unset($types[$type][$file]);
					continue;
				}
				// Only include the stylesheet if it exists.
				$files = T3Path::getCSS($file, 0);
					
				if(is_array($files)){
					foreach ($files as $file){
						if (!$preprocess || !($is_writable && $preprocess_css)) {
							// If a CSS file is not to be preprocessed and it's a module CSS file, it needs to *always* appear at the *top*,
							// regardless of whether preprocessing is on or off.
							if (!$preprocess && $type == 'module') {
								$no_module_preprocess .= '@import url("'. base_path() . $file . $query_string .'");'."\n";
							}
							// If a CSS file is not to be preprocessed and it's a theme CSS file, it needs to *always* appear at the *bottom*,
							// regardless of whether preprocessing is on or off.
							else if (!$preprocess && $type == 'theme') {
								$no_theme_preprocess .= '@import url("'. base_path() . $file . $query_string .'");'."\n";
							}
							else {
								$output .= '@import url("'. base_path() . $file . $query_string .'");'."\n";
							}
						}
					}
				}
			}
		}

		$output .= '</style>';
		if ($is_writable && $preprocess_css) {
			// Prefix filename to prevent blocking by firewalls which reject files
			// starting with "ad*".
			$types = $types['theme'];
			$retypes = array();
			foreach ($types as $path=>$cache){
				if($cache){
					$paths = T3Path::getCSS($path, 0);
					if(is_array($paths)){
						foreach ($paths as $final_path){
							$retypes[$final_path] = $cache;
						}
					}

				}
			}
			$filename = 't3css_'. md5(serialize($retypes) . $query_string) .'.css';

			$preprocess_file = t3_build_css_cache($retypes, $filename);
			$output .= '<link type="text/css" rel="stylesheet" media="'. $media .'" href="'. base_path() . $preprocess_file .'" />'."\n";
		}
			
			
	}


	//Brower, RTL
	foreach ($css as $media => $types) {
		if(!in_array($media, $arr_media)) continue;
			
		$output .= '<style type="text/css" media="'.$media.'">';
			
		if($device && $device != 'handheld' && $media == $device) $media = 'handheld';
		// If CSS preprocessing is off, we still need to output the styles.
		// Additionally, go through any remaining styles if CSS preprocessing is on and output the non-cached ones.
		foreach ($types as $type => $files) {

			foreach ($types[$type] as $file => $preprocess) {
					
				// Only include the stylesheet if it exists.
				$files = array();
					
				$browser_file = str_replace ( '.css', '-'. $bname .'.css', $file );
				$browser_files = T3Path::getCss($browser_file, 0);
					
				if(is_array($browser_files)) $files = array_merge($files, $browser_files);
					
				//version of browser
				$vbrowser_file = str_replace ( '.css', '-'. $bname.$bver .'.css', $file );
				$vbrowser_files = T3Path::getCss($vbrowser_file, 0);
					
				if(is_array($vbrowser_files)) $files = array_merge($files, $vbrowser_files);
				//right to left first
				if($language->direction == LANGUAGE_RTL) {
					$rtl_file = str_replace ( '.css', '-rtl.css', $file );
					$rtl_files = T3Path::getCss($rtl_file, 0);

					if(is_array($rtl_files)) $files = array_merge($files, $rtl_files);
					//browser rtl file
					$rtl_browser_file = str_replace ( '.css', '-rtl.css', $browser_file );
					$rtl_browser_files = T3Path::getCss($rtl_browser_file, 0);

					if(is_array($rtl_browser_files)) $files = array_merge($files, $rtl_browser_files);
					//version of browser right to left
					$rtl_vbrowser_file = str_replace ( '.css', '-rtl.css', $vbrowser_file );
					$rtl_vbrowser_files = T3Path::getCss($rtl_vbrowser_file, 0);

					if(is_array($rtl_vbrowser_files)) $files = array_merge($files, $rtl_vbrowser_files);
				}
					
				if(is_array($files)){
					foreach ($files as $file){
						if (!$preprocess || !($is_writable && $preprocess_css)) {
							// If a CSS file is not to be preprocessed and it's a module CSS file, it needs to *always* appear at the *top*,
							// regardless of whether preprocessing is on or off.
							if (!$preprocess && $type == 'module') {
								$no_module_preprocess .= '@import url("'. base_path() . $file . $query_string .'");'."\n";
							}
							// If a CSS file is not to be preprocessed and it's a theme CSS file, it needs to *always* appear at the *bottom*,
							// regardless of whether preprocessing is on or off.
							else if (!$preprocess && $type == 'theme') {
								$no_theme_preprocess .= '@import url("'. base_path() . $file . $query_string .'");'."\n";
							}
							else {
								$output .= '@import url("'. base_path() . $file . $query_string .'");'."\n";
							}
						}
					}
				}
			}
		}

		$output .= "</style>";
		if ($is_writable && $preprocess_css) {
			// Prefix filename to prevent blocking by firewalls which reject files
			// starting with "ad*".
			$types = $types['theme'];
			$retypes = array();
			foreach ($types as $path=>$cache){
				if($cache){
					//$paths = T3Path::getCSS($path, 0);
					$paths = array();
					$browser_path = str_replace ( '.css', '-'. $bname .'.css', $path );
					$browser_paths = T3Path::getCss($browser_path, 0);

					if(is_array($browser_paths)) $paths = array_merge($paths, $browser_paths);

					//version of browser
					$vbrowser_path = str_replace ( '.css', '-'. $bname.$bver .'.css', $path );
					$vbrowser_paths = T3Path::getCss($vbrowser_path, 0);

					if(is_array($vbrowser_paths)) $paths = array_merge($paths, $vbrowser_paths);


					if($language->direction == LANGUAGE_RTL) {
						$rtl_path = str_replace ( '.css', '-rtl.css', $path );
						$rtl_paths = T3Path::getCss($rtl_path, 0);
							
						if(is_array($rtl_paths)) $paths = array_merge($paths, $rtl_paths);
						//browser rtl path
						$rtl_browser_path = str_replace ( '.css', '-rtl.css', $browser_path );
						$rtl_browser_paths = T3Path::getCss($rtl_browser_path, 0);
							
						if(is_array($rtl_browser_paths)) $paths = array_merge($paths, $rtl_browser_paths);
						//version of browser right to left
						$rtl_vbrowser_path = str_replace ( '.css', '-rtl.css', $vbrowser_path );
						$rtl_vbrowser_paths = T3Path::getCss($rtl_vbrowser_path, 0);
							
						if(is_array($rtl_vbrowser_paths)) $paths = array_merge($paths, $rtl_vbrowser_paths);
					}

					if(is_array($paths)){
						foreach ($paths as $final_path){
							$retypes[$final_path] = $cache;
						}
					}

				}
			}
			$filename = 't3css_'. md5(serialize($retypes) . $query_string) .'.css';

			$preprocess_file = t3_build_css_cache($retypes, $filename);
			$output .= '<link type="text/css" rel="stylesheet" media="'. $media .'" href="'. base_path() . $preprocess_file .'" />'."\n";
		}
			
			
	}

	return $no_module_preprocess . $output . $no_theme_preprocess;
}

function t3_build_css_cache($types, $filename) {
	$data = '';

	// Create the css/ within the files folder.
	$csspath = file_create_path('css');
	file_check_directory($csspath, FILE_CREATE_DIRECTORY);

	if (!file_exists($csspath .'/'. $filename)) {
		// Build aggregate CSS file.
		foreach ($types as $file => $cache) {
			if ($cache) {
				$contents = drupal_load_stylesheet($file, TRUE);
				// Return the path to where this CSS file originated from.
				$base = base_path() . dirname($file) .'/';
				_drupal_build_css_path(NULL, $base);
				// Prefix all paths within this CSS file, ignoring external and absolute paths.
				$data .= preg_replace_callback('/url\([\'"]?(?![a-z]+:|\/+)([^\'")]+)[\'"]?\)/i', '_drupal_build_css_path', $contents);
			}
		}


		// Per the W3C specification at http://www.w3.org/TR/REC-CSS2/cascade.html#at-import,
		// @import rules must proceed any other style, so we move those to the top.
		$regexp = '/@import[^;]+;/i';
		preg_match_all($regexp, $data, $matches);
		$data = preg_replace($regexp, '', $data);
		$data = implode('', $matches[0]) . $data;

		// Create the CSS file.
		file_save_data($data, $csspath .'/'. $filename, FILE_EXISTS_REPLACE);
	}
	return $csspath .'/'. $filename;
}


/**
 * add any t3 script, check more addCss function
 *
 * @param string $data 		data of script(inline script or path of file)
 * @param string $type		type: file, inline
 * @param string $scope		scrope: header, footer...
 */
function t3_add_js($data, $type = 'file', $scope = 'header', $defer = FALSE, $cache = TRUE, $preprocess = TRUE) {
	static  $scripts = array();
	if (isset ( $data )) {
		// Add jquery.js and drupal.js, as well as the basePath setting, the
		// first time a Javascript file is added.
		if (empty ( $scripts )) {
			$scripts ['header'] = array ('file' => array (), 'inline' => array () );
		}
			
		if (isset ( $scope ) && ! isset ( $scripts [$scope] )) {
			$scripts [$scope] = array ('file' => array (), 'inline' => array () );
		}
			
		if (isset ( $type ) && isset ( $scope ) && ! isset ( $scripts [$scope] [$type] )) {
			$scripts [$scope] [$type] = array ();
		}
			
		switch ($type) {
			case 'inline' :
				$scripts [$scope] [$type] [] = array ('code' => $data, 'defer' => $defer );
				break;
			default :
				// If cache is FALSE, don't preprocess the JS file.
				if($data != 'header'){
					$scripts [$scope] [$type] [$data] = array ('cache' => $cache, 'defer' => $defer, 'preprocess' => (! $cache ? FALSE : $preprocess) );
				}
		}
	}

	if (isset ( $scope )) {
			
		if (isset ( $scripts [$scope] )) {
			return $scripts [$scope];
		} else {
			return array ();
		}
	} else {
		return $scripts;
	}

}

function t3_get_js($scope='header'){
	$javascript = t3_add_js($scope);

	if (!$javascript) {
		return '';
	}

	$output = '';
	$preprocessed = '';
	$no_preprocess = array('core' => '', 'module' => '', 'theme' => '');
	$files = array();
	$preprocess_js = (variable_get('preprocess_js', FALSE) && (!defined('MAINTENANCE_MODE') || MAINTENANCE_MODE != 'update'));
	$directory = file_directory_path();
	$is_writable = is_dir($directory) && is_writable($directory) && (variable_get('file_downloads', FILE_DOWNLOADS_PUBLIC) == FILE_DOWNLOADS_PUBLIC);

	// A dummy query-string is added to filenames, to gain control over
	// browser-caching. The string changes on every update or full cache
	// flush, forcing browsers to load a new copy of the files, as the
	// URL changed. Files that should not be cached (see drupal_add_js())
	// get time() as query-string instead, to enforce reload on every
	// page request.
	$query_string = '?'. substr(variable_get('css_js_query_string', '0'), 0, 1);

	// For inline Javascript to validate as XHTML, all Javascript containing
	// XHTML needs to be wrapped in CDATA. To make that backwards compatible
	// with HTML 4, we need to comment out the CDATA-tag.
	$embed_prefix = "\n<!--//--><![CDATA[//><!--\n";
	$embed_suffix = "\n//--><!]]>\n";

	foreach ($javascript as $type => $data) {

		if (!$data) continue;

		switch ($type) {
			case 'inline':
				foreach ($data as $info) {
					$output .= '<script type="text/javascript"' . ($info['defer'] ? ' defer="defer"' : '') . '>' . $embed_prefix . $info['code'] . $embed_suffix . "</script>\n";
				}
				break;
			default:
				// If JS preprocessing is off, we still need to output the scripts.
				// Additionally, go through any remaining scripts if JS preprocessing is on and output the non-cached ones.
				foreach ($data as $path => $info) {
					$path = T3Path::getJS($path, 0);
					if (!$info['preprocess'] || !$is_writable || !$preprocess_js) {
						$no_preprocess[$type] .= '<script type="text/javascript"'. ($info['defer'] ? ' defer="defer"' : '') .' src="'. base_path() . $path . ($info['cache'] ? $query_string : '?'. time()) ."\"></script>\n";
					}else {
						$files[$path] = $info;
					}
				}
		}
	}


	// Aggregate any remaining JS files that haven't already been output.
	if ($is_writable && $preprocess_js && count($files) > 0) {
		// Prefix filename to prevent blocking by firewalls which reject files
		// starting with "ad*".
		$filename = 'js_'. md5(serialize($files) . $query_string) .'.js';
		$preprocess_file = drupal_build_js_cache($files, $filename);
		$preprocessed .= '<script type="text/javascript" src="'. base_path() . $preprocess_file .'"></script>'."\n";
	}

	// Keep the order of JS files consistent as some are preprocessed and others are not.
	// Make sure any inline or JS setting variables appear last after libraries have loaded.
	$output = $preprocessed . implode('', $no_preprocess) . $output;

	return $output;
}

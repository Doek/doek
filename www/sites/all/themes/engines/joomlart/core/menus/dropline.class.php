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
class JDDroplineMenu extends JDMenu {
	function __construct($style = NULL, $name = NULL) {
		global $t3_styles, $t3_scripts;
		parent::__construct ( $style, $name );
		t3_add_js ( 'js/menus/dropline.js' );
		$this->extra_menu_param = true;
		$this->title = t ( 'JD Dropline Menu Parameters' );
		$this->description = t ( 'Extended parameters for JD Dropline Menu.' );
		$this->showSeparatedSub = 1;
		$str_inline = '	        
		 		var jasdl_delay = 1000;
	          $(document).ready(function(){         
	            jasdl_initJAScriptDLMenu()
	          });';
		t3_add_js ( $str_inline, 'inline' );
	}
	function render_dropline($tree, $menu_name) {
		$count = 0;
		$showdesc = 0;
		foreach ( $tree as $item ) {
			if (! $item ['link'] ['hidden']) {
				if ($item ['link'] ['options'] ['attributes'] ['title']) {
					$showdesc ++;
				}
				$data [] = $item;
				$count ++;
			}
		}
		$tree = $data;
		if (! is_array ( $tree ))
			return;
		foreach ( $tree as $k => $data ) {
			$extra_class = isset ( $data ['link'] ['localized_options'] ['extra class'] ) ? $data ['link'] ['localized_options'] ['extra class'] : NULL;
			$link = $data ['link'];
			$mlid = $link ['mlid'];
			$param = variable_get ( 'jd-megamenu-' . $mlid, NULL );
			$image = $param ['image'];
			$link ['class'] .= 'dropline';
			if ($link ['in_active_trail']) {
				$link ['class'] .= ' active';
				t3_add_js ( "jasdl_recover = " . $mlid . ";", "inline" );
			}
			if ($link ['has_children']) {
				$link ['class'] .= ' haschild';
				if ($link ['in_active_trail']) {
					$display = 'block';
				} else {
					$display = 'none';
				}
			}
			if ($k == 0)
				$link ['class'] .= ' first-item';
			elseif ($k == $count - 1)
				$link ['class'] .= ' last-item';
			$out .= '<li class="' . $link ['class'] . ' menu-item' . $link ['mlid'] . '" id="jasdl-mainnav' . $link ['mlid'] . '">';
			$out .= '<a href="' . url ( $link ['link_path'] ) . '" class="' . $link ['class'] . '" title="' . $link ['title'] . '">';
			if ($image) {
				global $base_url;
				$out .= '<span class="has-image" style="background-image: url(\'' . $base_url . '/' . $image . '\');">';
			}
			$out .= '<span class="menu-title">' . $link ['title'] . '</span>';
			if ($link ['options'] ['attributes'] ['title'])
				$out .= '<span class="menu-desc">' . $link ['options'] ['attributes'] ['title'] . '</span>';
			elseif ($showdesc)
				$out .= '<span class="menu-desc">&nbsp;</span>';
			if ($image) {
				$out .= '</span>';
			}
			$out .= '</a>';
			$out .= '</li>';
			//$out .= $this->render_item($link, 'menu-item' . $link['mlid'], 'jasdl-mainnav' . $link['mlid']);
			$subout = '';
			if ($data ['below'] && $link ['has_children']) {
				$count1 = 0;
				$showdesc1 = 0;
				foreach ( $data ['below'] as $item ) {
					if (! $item ['link'] ['hidden']) {
						if ($item ['link'] ['options'] ['attributes'] ['title']) {
							$showdesc1 ++;
						}
						$tmp [] = $item;
						$count1 ++;
					}
				}
				foreach ( $data ['below'] as $i => $subdata ) {
					$extra_class = isset ( $subdata ['link'] ['localized_options'] ['extra class'] ) ? $subdata ['link'] ['localized_options'] ['extra class'] : NULL;
					$sublink = $subdata ['link'];
					if ($sublink ['in_active_trail']) {
						$sublink ['class'] .= ' active';
					}
					if ($i == 0)
						$sublink ['class'] .= ' first-item';
					elseif ($i == $count1 - 1)
						$sublink ['class'] .= ' last-item';
					$param = variable_get ( 'jd-megamenu-' . $sublink ['mlid'], NULL );
					$image = $param ['image'];
					if ($subdata ['below'] && $sublink ['has_children']) {
						$child = $this->dropline_item ( $subdata ['below'], $menu_name );
						$subout .= '<li class="' . $sublink ['class'] . ' menu-item" id="jasdl-subnavitem' . $sublink ['mlid'] . '">';
						$subout .= '<a href="' . url ( $sublink ['link_path'] ) . '" class="haschild' . $sublink ['class'] . '" title="' . $sublink ['title'] . '">';
						if ($image) {
							global $base_url;
							$subout .= '<span class="has-image" style="background-image: url(\'' . $base_url . '/' . $image . '\');">';
						}
						$subout .= '<span class="menu-title">' . $sublink ['title'] . '</span>';
						if ($sublink ['options'] ['attributes'] ['title'])
							$subout .= '<span class="menu-desc">' . $sublink ['options'] ['attributes'] ['title'] . '</span>';
						elseif ($showdesc1)
							$subout .= '<span class="menu-desc">&nbsp;</span>';
						if ($image) {
							$subout .= '</span>';
						}
						$subout .= '</a>';
						$subout .= '<ul id="jasdl-subnav' . $mlid . '">' . $child . '</ul>';
						$subout .= '</li>';
					} else {
						$subout .= '<li class="' . $sublink ['class'] . ' menu-item" id="jasdl-subnavitem' . $sublink ['mlid'] . '">';
						$subout .= '<a href="' . url ( $sublink ['link_path'] ) . '" class=" ' . $sublink ['class'] . '" title="' . $sublink ['title'] . '">';
						if ($image) {
							global $base_url;
							$subout .= '<span class="has-image" style="background-image: url(\'' . $base_url . '/' . $image . '\');">';
						}
						$subout .= '<span class="menu-title">' . $sublink ['title'] . '</span>';
						if ($sublink ['options'] ['attributes'] ['title'])
							$subout .= '<span class="menu-desc">' . $sublink ['options'] ['attributes'] ['title'] . '</span>';
						elseif ($showdesc1)
							$subout .= '<span class="menu-desc">&nbsp;</span>';
						if ($image) {
							$subout .= '</span>';
						}
						$subout .= '</a>';
						$subout .= '</li>';
					}
				}
			}
			$sub .= '<ul id="jasdl-subnav' . $data ['link'] ['mlid'] . '" class="' . $link ['class'] . '" style="display:' . $display . '">';
			$sub .= $subout ? $subout : '&nbsp;';
			$sub .= '</ul>';
		}
		$mainnav = '<div id="jasdl-mainnav">
	          <ul>
	            ' . $out . '
	          </ul>
	        </div>';
		$subnav = '<div id="jasdl-subnav">
	        	' . $sub . '
	        </div>';
		return array ('mainnav' => $mainnav, 'subnav' => $subnav );
	}
	function dropline_item($tree, $menu_name) {
		$output = '';
		$count = 0;
		if (! is_array ( $tree ))
			return;
		foreach ( $tree as $item ) {
			if (! $item ['link'] ['hidden']) {
				if ($item ['link'] ['options'] ['attributes'] ['title'] && $item ['link'] ['depth'] == 1) {
					$this->showdesc ++;
				}
				$data [] = $item;
				$count ++;
			}
		}
		$tree = $data;
		foreach ( $tree as $k => $menu_item ) {
			$link = $menu_item ['link'];
			$mlid = $menu_item ['link'] ['mlid'];
			// Check to see if it is a visible menu item.
			if ($link ['in_active_trail'])
				$link ['class'] .= ' active';
			if ($k == 0)
				$link ['class'] .= ' first-item';
			elseif ($k == $count - 1)
				$link ['class'] .= ' last-item';
				// If it has children build a nice little tree under it.
			if ($menu_item ['below'] && $link ['has_children']) {				
				$output .= '<li id="jasdl-subnavitem' . $mlid . '" class="' . $link ['class'] . '">';
				$children = $this->dropline_item ( $menu_item ['below'], $menu_name );
				// Build the child UL only if children are displayed for the user.
				$output .= '<a href="' . url ( $link ['link_path'] ) . '" class="' . $link ['class'] . '" title="' . $link ['title'] . '">';
				$output .= '<span class="menu-title">' . $link ['title'] . '</span>';
				$output .= '</a>';
				if ($children) {
					$output .= '<ul id="jasdl-subnav' . $mlid . '">';
					$output .= $children;
					$output .= "</ul>\n";
				}
				$output .= "</li>\n";
			} else {
				$output .= '<li class="' . $link ['class'] . ' menu-item" id="jasdl-subnavitem' . $link ['mlid'] . '">';
				$output .= '<a href="' . url ( $link ['link_path'] ) . '" class="' . $link ['class'] . '" title="' . $link ['title'] . '">';
				if ($image) {
					global $base_url;
					$output .= '<span class="has-image" style="background-image: url(\'' . $base_url . '/' . $image . '\');">';
				}
				$output .= '<span class="menu-title">' . $link ['title'] . '</span>';
				if ($image) {
					$output .= '</span>';
				}
				$output .= '</a>';
				$output .= '</li>';
				//$output .= $this->render_item($menu_item['link']);
			}
		}
		return $output;
	}
	function editItem($itemid) {
		$data = variable_get ( 'jd-megamenu-' . $itemid, NULL );
		$form ['image'] = array ('#type' => 'select', '#title' => t ( 'Menu Image' ), '#description' => t ( 'A small image to be placed beside your Menu Item. Images must be stored in "sites/all/images/". Image type: png' ), '#options' => $this->getImageList (), '#default_value' => $data ['image'] ? $data ['image'] : '' );
		$form ['id'] = array ('#type' => 'hidden', '#value' => $itemid );
		$form ['t3_task'] = array ('#type' => 'hidden', '#value' => 'save_menu' );
		$form ['style'] = array ('#type' => 'hidden', '#value' => 'mega' );
		global $theme_key;
		$form ['url'] = array ('#type' => 'hidden', '#value' => url ( 'admin/build/themes/settings/' . $theme_key ) );
		return $form;
	}
	function saveItems($itemid) {
		$subwidth = &$_POST ['subwidth'];
		$subwidth = preg_replace ( '/[^0-9]+/', '', $subwidth );
		variable_del ( 'jd-megamenu-' . $itemid );
		variable_set ( 'jd-megamenu-' . $itemid, $_POST );
		echo 'ok';
	}
	function getImageList() {
		$path = 'sites/all/images';
		$files = file_scan_directory ( $path, '.png' );
		$result [0] = t ( 'None' );
		foreach ( $files as $file ) {
			$result [$file->filename] = $file->basename;
		}
		return $result;
	}
}
?>

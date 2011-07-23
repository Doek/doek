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
class JDMegaMenu extends JDMenu {
	private $menu = 1;
	public $style = 1;
	private $showdesc = 0;
	function __construct($style = NULL, $name = NULL) {
		global $t3_styles, $t3_scripts;
		parent::__construct ( $style, $name );
		t3_add_js ( 'js/menus/jquery.easing.js' );
		t3_add_js ( 'js/menus/mega.js' );
		$this->extra_menu_param = true;
		$this->title = t ( 'JD Mega Menu Parameters' );
		$this->description = t ( 'Extended parameters for JD Mega Menu. Please read the Usage Instructions if you are new to Mega Menu.' );
	}
	function render_mega($tree, $menu_name) {
		global $language;
		$T3Profile = & T3Profile::getInstance ();
		
		$effectIn = $T3Profile->getValue ( 'menu_effectin', 'jswing' );
		$effectOut = $T3Profile->getValue ( 'menu_effectout', 'jswing' );
		$durationIn = $T3Profile->getValue ( 'menu_durationin', '300' );
		$durationOut = $T3Profile->getValue ( 'menu_durationout', '400' );
		$delay = $T3Profile->getValue ( 'menu_delay', '1000' );
		$rtl = $language->direction;
		$output = '<script type="text/javascript">';
		$output .= 'jQuery(document).ready(function(){
    		jQuery(\'.ja-megamenu\').JDMegaMenu({effectIn:"' . $effectIn . '",effectOut:"' . $effectOut . '",durationIn:' . $durationIn . ',durationOut:' . $durationOut . ',delay:' . $delay . ',direction:' . $rtl . '});
    	});';
		$output .= '</script>';
		$output .= '<div class = "ja-megamenu" id="ja-megamenu">';
		$output .= $this->render_mega_item ( $tree, true, 0 );
		$output .= '</div>';
		return array ('mainnav' => $output );
	}
	function render_mega_item($tree, $root = true, $level = 0) {
		if (! is_array ( $tree ))
			return;
		$output = '';
		$count = 0;
		//$this->showdesc = 0;
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
		if (! is_array ( $tree ))
			return;
		foreach ( $tree as $k => $menu_item ) {
			if ($k == 0)
				$menu_item ['link'] ['class'] .= ' first';
			elseif ($k == $count - 1)
				$menu_item ['link'] ['class'] .= ' last';
			if ($menu_item ['link'] ['in_active_trail'])
				$menu_item ['link'] ['class'] .= ' active';
			$output .= $this->render_mega_menu ( $menu_item );
		}
		if ($root) {
			$output = '<ul class="megamenu level' . $level . '">' . $output . '</ul>';
		}
		return $output;
	}
	function render_mega_menu(&$menu_item) {
		$link = &$menu_item ['link'];
		$width = 0;
		$mlid = $link ['mlid'];
		$param = variable_get ( 'jd-megamenu-' . $mlid, NULL );
		$group = $param ['group'];
		$subcontent = $param ['subcontent'];
		if (! $link ['has_children'] && $subcontent > 1)
			$link ['has_children'] = 1;
			//$link['class'] .= ' ' . $param['class'];
		if ($link ['has_children']) {
			$output .= $this->render_mega_sub_menu ( $menu_item, $param );
		} else {
			$output .= $this->render_mega_menu_item ( $link, $param );
		}
		$this->menu ++;
		return $output;
	}
	function render_mega_sub_menu(&$menu_item, $param) {
		$this->render_width ( $param );
		$colswidth = $param ['colxw'];
		$subwidth = $param ['subwidth'] ? $param ['subwidth'] : NULL;
		$link = $menu_item ['link'];
		$column = $param ['column'];
		$group = $param ['group'];
		$image = $param ['image'];
		$subcontent = $param ['subcontent'];
		if ($menu_item ['below']) {
			foreach ( $menu_item ['below'] as $item ) {
				if (! $item ['link'] ['hidden']) {
					$data [] = $item;
				}
			}
			$menu_item ['below'] = $data;
		}
		// Build the child UL only if children are displayed for the user.
		if (! $subcontent || $subcontent == 1) {
			$children = $this->render_mega_child_content ( $menu_item, $param );
		}
		if ($subcontent == 2) {
			$children = $this->render_mega_child_blocks ( $menu_item, $param );
		}
		if ($subcontent == 3) {
			$children = $this->render_mega_child_region ( $menu_item, $param );
		}
		if ($children) {
			$link ['class'] .= ' haschild';
			if ($group) {
				$link ['class'] .= ' group';
			}
			$output .= '<li class="mega ' . $link ['class'] . '" id="menu' . $this->menu . '">';
			if ($group) {
				$output .= '<div class="group">'; //Open group
				$output .= '<div class="group-title">'; //Open group title
			}
			$output .= '<a href="' . url ( $link ['link_path'] ) . '" class="mega ' . $link ['class'] . '" title="' . $link ['title'] . '">';
			if ($image) {
				global $base_url;
				$output .= '<span class="has-image" style="background-image: url(\'' . $base_url . '/' . $image . '\');">';
			}
			$output .= '<span class="menu-title">' . $link ['title'] . '</span>';
			if ($link ['options'] ['attributes'] ['title'])
				$output .= '<span class="menu-desc">' . $link ['options'] ['attributes'] ['title'] . '</span>';
			elseif ($this->showdesc && $link ['depth'] == 1)
				$output .= '<span class="menu-desc">&nbsp;</span>';
			if ($image) {
				$output .= '</span>';
			}
			$output .= '</a>';
			if ($group) {
				$output .= '</div>'; //Close group title
				$output .= '<div class="group-content">'; //Open group content
			} else {
				$subwidth = $subwidth ? $subwidth : 200;
				$output .= '<div class="childcontent childcontent-menu' . $this->menu . '" style="overflow:hidden;width:' . $subwidth . 'px;">'; //Open Childcontent
				$output .= '<div class="childcontent-inner-wrap childcontent-inner-wrap-menu' . $this->menu . '" style="top:0;position:relative; width:' . $subwidth . 'px;">'; //Open Inner Wrap
				if ($this->style == 2) {
					$output .= '<div class="l"></div>'; // Left border
				}
				if ($this->style == 3) {
					$output .= "<div class=\"top\" ><div class=\"tl\"></div><div class=\"tr\"></div></div>\n"; //Top
					$output .= "<div class=\"mid\">\n"; //Middle
					$output .= "<div class=\"ml\"></div>\n"; //Middle left
				}
				$output .= '<div class="childcontent-inner clearfix" style="width:' . $subwidth . 'px;">'; //Open Inner
			}
			$output .= $children;
			if ($group) {
				$output .= '</div>'; //Close group content
				$output .= '</div>'; //Close Group
			} else {
				$output .= "</div>\n"; //Close Inner
				if ($this->style == 2) {
					$output .= "<div class=\"r\">"; //Right border
				}
				if ($this->style == 3) {
					$output .= "<div class=\"mr\"></div>\n"; //Middle right
					$output .= "</div>"; //Close Middle
					$output .= "<div class=\"bot\" ><div class=\"bl\"></div><div class=\"br\"></div></div>\n"; //Bottom
				}
				$output .= "</div>\n"; //Close Inner wrap
				$output .= "</div>\n"; //Close Childcontent	
			}
			$output .= "</li>\n";
		} else {
			$output .= $this->render_mega_menu_item ( $link, $param );
		}
		return $output;
	}
	function render_mega_child_content(&$menu_item, $param) {
		if (! $menu_item ['below']) {
			return;
		}
		$colswidth = $param ['colxw'];
		$link = $menu_item ['link'];
		$column = $param ['column'] ? $param ['column'] : 1;
		$count = count ( $menu_item ['below'] );
		$num = round ( $count / $column ); // number of menus for each column
		$newtree = array_chunk ( $menu_item ['below'], $num, true ); //split $tree to parts	
		foreach ( $newtree as $k => $tree ) {
			$children = $this->render_mega_item ( $tree, 1, $link ['depth'], $trail );
			$class = '';
			if ($k == 0) {
				$class = 'first';
			} elseif ($k == count ( $newtree ) - 1) {
				$class = 'last';
			}
			$sub .= '<div class="megacol column' . $k . ' ' . $class . '" style="width:' . $colswidth ['colw' . ($k + 1)] . 'px;">' . $children . '</div>';
		}
		$children = $sub;
		return $children;
	}
	function render_mega_child_blocks(&$menu_item, $param) {
		$blocks = $param ['blocks'];
		if (! count ( $blocks ) && $menu_item ['below']) {
			$output = $this->render_mega_child_content ( $menu_item, $param );
		} else
			$output = $this->getBlockData ( $blocks );
		return $output;
	}
	function render_mega_child_region(&$menu_item, $param) {
		$regions = $param ['regions'];
		if (! count ( $regions ) && $menu_item ['below'])
			$output = $this->render_mega_child_content ( $menu_item, $param );
		else {
			global $theme_key;
			init_theme ();
			foreach ( $regions as $region ) {
				$query = db_query ( "SELECT module,delta FROM {blocks} WHERE region='%s' AND theme='%s'", $region, $theme_key );
				while ( $row = db_fetch_array ( $query ) ) {
					$blocks [] = $row ['module'] . '|' . $row ['delta'];
				}
				if (count ( $blocks ))
					$output .= $this->getBlockData ( $blocks );
			}
			if (! $output && $menu_item ['below'])
				$output = $this->render_mega_child_content ( $menu_item, $param );
		}
		if (! $output)
			$menu_item ['link'] ['has_children'] = 0;
		return $output;
	}
	function getBlockData($blocks) {
		foreach ( $blocks as $block ) {
			$block = explode ( '|', $block );
			$module = $block [0];
			$delta = $block [1];
			$content = module_invoke ( $module, 'block', 'view', $delta );
			if ($content) {
				$output .= '<div class="ja-block block block-' . $module . ' clearfix">';
				$output .= '<div class="block-content clearfix">';
				$output .= $content ['content'];
				$output .= '</div>';
				$output .= '</div>';
			}
		}
		return $output;
	}
	function render_mega_menu_item($link, $param) {
		$group = $param ['group'];
		$image = $param ['image'];
		$output .= '<li class="mega ' . $link ['class'] . '" id="menu' . $this->menu . '">';
		$output .= '<a href="' . url ( $link ['link_path'] ) . '" class="mega ' . $link ['class'] . '" title="' . $link ['title'] . '">';
		if ($image) {
			global $base_url;
			$output .= '<span class="has-image" style="background-image: url(\'' . $base_url . '/' . $image . '\');">';
		}
		$output .= '<span class="menu-title">' . $link ['title'] . '</span>';
		if ($link ['options'] ['attributes'] ['title'])
			$output .= '<span class="menu-desc">' . $link ['options'] ['attributes'] ['title'] . '</span>';
		elseif ($this->showdesc && $link ['depth'] == 1)
			$output .= '<span class="menu-desc">&nbsp;</span>';
		if ($image) {
			$output .= '</span>';
		}
		$output .= '</a>';
		$output .= '</li>';
		return $output;
	}
	function render_width(&$param) {
		$subwidth = &$param ['subwidth'];
		$colwidth = &$param ['columnwidth'];
		$colwidth = $colwidth ? $colwidth : 200;
		$colswidth = &$param ['colxw'];
		$column = $param ['column'] ? $param ['column'] : 1;
		if (! $subwidth && ! $colswidth && ! $colwidth) {
			$colwidth = 200;
			$subwidth = $colwidth * $column;
			for($i = 1; $i <= $column; $i ++) {
				$tmp2 ['colw' . $i] = $colwidth;
			}
			$colswidth = $tmp2;
			return;
		}
		if ($subwidth && $column && ! $colwidth) {
			$colwidth = $subwidth / $column;
		}
		if ($colswidth) {
			$colswidth = explode ( ' ', $colswidth );
			if (is_array ( $colswidth )) {
				foreach ( $colswidth as $k => $val ) {
					$val = explode ( '=', $val );
					$tmp [$val [0]] = $val [1];
				}
			}
			$subwidth = 0;
			for($i = 1; $i <= $column; $i ++) {
				$w = $tmp ['colw' . $i];
				$w = $w ? $w : $colwidth;
				$tmp2 ['colw' . $i] = $w;
				$subwidth += $w;
			}
			$colswidth = $tmp2;
		} else {
			$subwidth = 0;
			for($i = 1; $i <= $column; $i ++) {
				$tmp2 ['colw' . $i] = $colwidth;
				$subwidth += $colwidth;
			}
			$colswidth = $tmp2;
		}
	}
	function editItem($itemid) {
		$data = variable_get ( 'jd-megamenu-' . $itemid, NULL );
		$form ['column'] = array ('#type' => 'textfield', '#title' => t ( 'Columns' ), '#description' => t ( 'Number of colums to display sub-menus' ), '#default_value' => $data ['column'] ? $data ['column'] : 1 );
		$form ['group'] = array ('#type' => 'radios', '#title' => t ( 'Group' ), '#description' => t ( 'Group sub-menus' ), '#options' => array (1 => t ( 'Yes' ), 0 => t ( 'No' ) ), '#default_value' => $data ['group'] ? $data ['group'] : 0 );
		$form ['subwidth'] = array ('#type' => 'textfield', '#title' => t ( 'Submenu Width' ), '#description' => t ( 'Width of Submenu' ), '#default_value' => $data ['subwidth'] ? $data ['subwidth'] : '' );
		$form ['columnwidth'] = array ('#type' => 'textfield', '#title' => t ( 'Column Width' ), '#description' => t ( 'Width of Submenu Column' ), '#default_value' => $data ['columnwidth'] ? $data ['columnwidth'] : '' );
		$form ['colxw'] = array ('#type' => 'textarea', '#title' => t ( 'Column[i] Width' ), '#description' => t ( 'Set width for each column. Ex: Enter "colw1=200 colw2=300" to set width for column 1 & column 2' ), '#default_value' => $data ['colxw'] ? $data ['colxw'] : '', '#rows' => 1 );
		$form ['subcontent'] = array ('#type' => 'radios', '#title' => t ( 'Submenu Content' ), '#description' => t ( 'Choose content to show in submenus. Multiple blocks based on name or regions can be selected and loaded. Use Ctrl + Click to select Multiple Items.' ), '#options' => array (1 => t ( 'Child menu items OR None (if has no child)' ), 2 => t ( 'Blocks' ), 3 => t ( 'Regions' ) ), '#default_value' => $data ['subcontent'] ? $data ['subcontent'] : 1 );
		$form ['blocks'] = array ('#type' => 'select', '#title' => t ( 'Blocks' ), '#description' => t ( 'Select blocks to list as submenu' ), '#options' => $this->getBlockList (), '#multiple' => TRUE, '#size' => 10, '#default_value' => $data ['blocks'] ? $data ['blocks'] : '' );
		$form ['regions'] = array ('#type' => 'select', '#title' => t ( 'Regions' ), '#description' => t ( 'Select regions to list as submenu' ), '#options' => $this->getRegionList (), '#multiple' => TRUE, '#size' => 10, '#default_value' => $data ['regions'] ? $data ['regions'] : '' );
		$form ['image'] = array ('#type' => 'select', '#title' => t ( 'Menu Image' ), '#description' => t ( 'A small image to be placed beside your Menu Item. Images must be stored in "sites/all/images/". Image type: png' ), '#options' => $this->getImageList (), '#default_value' => $data ['image'] ? $data ['image'] : '' );
		$form ['class'] = array ('#type' => 'textfield', '#title' => t ( 'Additional class(es)' ), '#description' => t ( 'Additional CSS class(es) to apply to menu item.' ), '#default_value' => $data ['class'] ? $data ['class'] : '' );
		$form ['id'] = array ('#type' => 'hidden', '#value' => $itemid );
		$form ['t3_task'] = array ('#type' => 'hidden', '#value' => 'save_menu' );
		$form ['style'] = array ('#type' => 'hidden', '#value' => 'mega' );
		global $theme_key;
		$form ['url'] = array ('#type' => 'hidden', '#value' => url ( 'admin/build/themes/settings/' . $theme_key ) );
		return $form;
	}
	function saveItems($itemid) {
		$column = &$_POST ['column'];
		$subwidth = &$_POST ['subwidth'];
		$columnwidth = &$_POST ['columnwidth'];
		$column = preg_replace ( '/[^0-9]+/', '', $column );
		$subwidth = preg_replace ( '/[^0-9]+/', '', $subwidth );
		$columnwidth = preg_replace ( '/[^0-9]+/', '', $columnwidth );
		variable_del ( 'jd-megamenu-' . $itemid );
		variable_set ( 'jd-megamenu-' . $itemid, $_POST );
		echo 'ok';
	}
	function getBlockList() {
		global $theme_key, $base_url;
		init_theme ();
		$query = db_query ( "SELECT * FROM {blocks} WHERE theme = '%s'", $theme_key );
		$list = array ();
		$k = 0;
		while ( $row = db_fetch_array ( $query ) ) {
			$list [$k] ['delta'] = $row ['delta'];
			$list [$k] ['module'] = $row ['module'];
			$k ++;
		}
		$result = array ();
		foreach ( $list as $k => $val ) {
			$tmp = module_invoke ( $val ['module'], 'block', 'list', $val ['delta'] );
			$result [$val ['module'] . '|' . $val ['delta']] = $tmp [$val ['delta']] ['info'] . " (Module: " . $val ['module'] . ')';
		}
		return $result;
	}
	function getRegionList() {
		global $theme_key;
		init_theme ();
		return system_region_list ( $theme_key );
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
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
class JDCssMenu extends JDMenu
{
	private $menu = 0;
	private $showdesc = 0;
	function __construct($style = NULL,$name = NULL)
	{
		parent::__construct($style, $name);
		$this->extra_menu_param = true;
		$this->title = t('JD CSS Menu Parameters');
		$this->description = t('Extended parameters for JD CSS Menu.');
		$ie = T3Util::getIE();
		if($ie == 6 || $ie == 7){
			t3_add_js('js/menus/css.js');
		}
		
	}
	function render_css($tree,$menu_name,$root = true)
	{
		$output = '';
		//echo count($tree).' ';
		$count = 0;
		foreach ($tree as $item) {
			if (! $item['link']['hidden']) {
				if ($item['link']['options']['attributes']['title'] && $item['link']['depth'] == 1) {
					$this->showdesc ++;
				}
				$data[ ] = $item;
				$count ++;
			}
		}
		$tree = $data;
		if(!is_array($tree))return;
		foreach ($tree as $k => $menu_item) {
			$menu_item['link']['class'] = 'css';
			if ($k == 0)
				$menu_item['link']['class'] .= ' first-item';
			elseif ($k == $count - 1)
				$menu_item['link']['class'] .= ' last-item';
			if ($menu_item['link']['in_active_trail'])
				$menu_item['link']['class'] .= ' active';
			$link = $menu_item['link'];
			$mlid = $menu_item['link']['mlid'];
			$param = variable_get('jd-megamenu-' . $mlid, NULL);
			$image = $param['image'];
			// Check to see if it is a visible menu item.
			if ($link['has_children']) {
				if ($link['depth'] == 1) {
					$link['class'] .= ' havechild';
				} else {
					$link['class'] .= ' havesubchild';
				}
			}
			$link['class'] .= ' menu-item' . $this->menu;
			// If it has children build a nice little tree under it.
			if ($link['has_children'] && $menu_item['below']) {
				$children = $this->render_css($menu_item['below'], $menu_name, false);
				$children = $children['mainnav'];
				$output .= '<li class="' . $link['class'] . '">';
				$output .= '<a href="' . url($link['link_path']) . '" class="' . $link['class'] . '" title="' . $link['title'] . '">';
				if ($image) {
					global $base_url;
					$output .= '<span class="has-image" style="background-image: url(\'' . $base_url . '/' . $image . '\');">';
				}
				$output .= '<span class="menu-title">' . $link['title'] . '</span>';
				if ($link['options']['attributes']['title'])
					$output .= '<span class="menu-desc">' . $link['options']['attributes']['title'] . '</span>';
				elseif ($this->showdesc && $link['depth'] == 1)
					$output .= '<span class="menu-desc">&nbsp;</span>';
				if ($image) {
					$output .= '</span>';
				}
				$output .= '</a>';
				// Build the child UL only if children are displayed for the user.
				if ($children) {
					$output .= '<ul id="subnav' . $mlid . '">';
					$output .= $children;
					$output .= "</ul>\n";
				}
				$output .= "</li>\n";
			} else {
				if ($this->render_item($link)) {
					$output .= '<li class="' . $link['class'] . '">';
					$output .= '<a href="' . url($link['link_path']) . '" class="' . $link['class'] . '" title="' . $link['title'] . '">';
					if ($image) {
						global $base_url;
						$output .= '<span class="has-image" style="background-image: url(\'' . $base_url . '/' . $image . '\');">';
					}
					$output .= '<span class="menu-title">' . $link['title'] . '</span>';
					if ($link['options']['attributes']['title'])
						$output .= '<span class="menu-desc">' . $link['options']['attributes']['title'] . '</span>';
					elseif ($this->showdesc && $link['depth'] == 1)
						$output .= '<span class="menu-desc">&nbsp;</span>';
					if ($image) {
						$output .= '</span>';
					}
					$output .= '</a>';
					$output .= '</li>';
				}
			}
			$this->menu ++;
		}
		if ($root) {
			$output = '<ul id="ja-cssmenu" class="clearfix">' . $output . '</ul>';
		}
		return array('mainnav' => $output);
	}
	function editItem($itemid)
	{
		$data = variable_get('jd-megamenu-' . $itemid, NULL);
		$form['image'] = array(
		'#type' => 'select', 
		'#title' => t('Menu Image'), 
		'#description' => t('A small image to be placed beside your Menu Item. Images must be stored in "sites/all/images/". Image type: png'), 
		'#options' => $this->getImageList(), 
		'#default_value' => $data['image'] ? $data['image'] : '');
		$form['id'] = array('#type' => 'hidden', '#value' => $itemid);
		$form['t3_task'] = array('#type' => 'hidden', '#value' => 'save_menu');
		$form['style'] = array('#type' => 'hidden', '#value' => 'mega');
		global $theme_key;
		$form['url'] = array('#type' => 'hidden', '#value' => url('admin/build/themes/settings/' . $theme_key));
		return $form;
	}
	function saveItems($itemid)
	{
		$subwidth = &$_POST['subwidth'];
		$subwidth = preg_replace('/[^0-9]+/', '', $subwidth);
		variable_del('jd-megamenu-' . $itemid);
		variable_set('jd-megamenu-' . $itemid, $_POST);
		echo 'ok';
	}
	function getImageList()
	{
		$path = 'sites/all/images';
		$files = file_scan_directory($path, '.png');
		$result[0] = t('None');
		foreach ($files as $file) {
			$result[$file->filename] = $file->basename;
		}
		return $result;
	}
}
?>
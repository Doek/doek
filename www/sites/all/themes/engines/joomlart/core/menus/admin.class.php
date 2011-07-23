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
class JDAdminMenu extends JDMenu
{
	function __construct($style = NULL,$name = NULL)
	{
		parent::__construct($style, $name);
		t3_add_js('js/menus/jquery.highlight-3.js');
		t3_add_js('js/menus/admin.js');
		t3_add_css('css/menus/admin.css');
		$this->extra_menu_param = false;
	}
	function render_admin()
	{
		$tree = $this->jd_menu_tree_page_data();
		$output = '';
		$output .= '<div class="jd-admin-menu-wrap">'; //Open Wrap
		$output .= '<div class="jd-admin-menu-caption" title="' . t('Ctrl-Shift-S') . '">'; //Open Active
		$output .= '<a href="#" onclick="return false;" class="close"><span class="jd-admin-menu-title">' . t('Quick Menu') . '</span></a>';
		$output .= '</div>'; //Close Active
		$output .= '<div class="jd-admin-menu-main" style="display:none">'; //Open Main
		$output .= '<div class="search"><span>' . t('Filter:') . '</span><input id="jd-admin-menu-search" type="text" size="12" value="" /></div>';
		$output .= '<div class="jd-admin-menu-content"><ul class="jd-admin-menu">';
		$output .= $this->render_admin_item($tree);
		$output .= '</ul></div>';
		$output .= '</div>'; //Close Main
		$output .= '</div>'; //Close Wrap
		return array('mainnav' => $output);
	}
	function render_admin_item($tree)
	{
		$output = '';
		foreach ($tree as $item) {
			if (! $item['link']['hidden']) {
				$data[ ] = $item;
			}
		}
		$tree = $data;
		if (! is_array($tree))
			return;
		foreach ($tree as $item) {
			$link = $item['link'];
			$below = $item['below'];
			$depth = $link['depth'];
			if ($link['in_active_trail']) {
				$link['class'] .= ' active';
			}
			$link['class'] .= ' show lv' . $link['depth'];
			if ($below) {
				$output .= '<li class="jd-admin-menu-item ' . $link['class'] . '">';
				$output .= '<a href="' . url($link['link_path']) . '">' . $link['link_title'] . '</a>';
				$output .= '</li>';
				$output .= $this->render_admin_item($below);
			} else {
				$output .= '<li class="jd-admin-menu-item ' . $link['class'] . '">';
				$output .= '<a href="' . url($link['link_path']) . '">' . $link['link_title'] . '</a>';
				$output .= '</li>';
			}
		}
		return $output;
	}
}
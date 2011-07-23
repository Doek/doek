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
class JDIphoneMenu extends JDMenu {
	function __construct($style = NULL, $name = NULL){
		parent::__construct($style, $name);
	
		t3_add_js('js/menus/iphone.js');	
		t3_add_js("$(document).ready(function(){
              var iphoneMenu = jQuery('#ja-iphonemenu').JAIToolbox();
            });", 'inline');
	}
	
	
	function render_iphone($tree, $menu_name){
		$items = array();
	  	$items = $this->iphone_inside($tree, $menu_name);
	  	$output = '';
	  
	  	if(is_array($items)) {
		    foreach ($items as $plid => $aSub) {
		      if (!$plid) {
						$output .= "<ul id=\"ja-iphonemenu\" title=\"Menu\" class=\"toolbox\">";
					} else {
						$output .= "<ul id=\"nav{$plid}\" title=\"\" class=\"toolbox\">";
					}
		      $output .= implode("", $aSub);
		      $output .= "</ul>";
		    }
	  	}
	  	return array('mainnav'=>$output);
	}
	
	function iphone_inside($tree, $menu_name){
		// Backup active menu trail and set a new one
		
		$active_menu_name = menu_get_active_menu_name();
		menu_set_active_menu_name($menu_name);
		
		// Build table of mlid in the active trail
		
		foreach (menu_set_active_trail() as $value) {
		    if ($value['mlid']) {
		      $trail[] = $value['mlid'];
		  	}
		}
		//print_r($trail);
		// Restore active menu trail
		
		menu_set_active_menu_name($active_menu_name);
		
		$output = array();
		foreach ($tree as $menu_item) {
		    $link = $menu_item['link'];
		    $plid = $menu_item['link']['plid'];
		    $mlid = $menu_item['link']['mlid'];
		
		    // Check to see if it is a visible menu item.
		    if ($menu_item['link']['hidden'] == 0) {
		      	if($link['has_children']) {
			        if($link['depth']==1){
			          $link['class'] .= 'havechild';
			        }else{
			          $link['class'] .= 'havesubchild';
			        }
		      	}
		
		      	if (is_array($trail) && in_array($link['mlid'], $trail)) {
			        $link['in_active_trail'] = TRUE;
			        $link['class'] .= ' active';
		      	}
		
		      	// If it has children build a nice little tree under it.
		      	if ((!empty($link['has_children'])) && (!empty($menu_item['below']))) {
			        $output[$plid][$mlid] = '<li class="'.$link['class'].'">'. theme('menu_item_link', $link) .
			                                '<a class="ja-folder" href="#nav'.$mlid.'" title="'.$link['title'].'">&nbsp;</a></li>'."\n";
			        $children = $this->iphone_inside($menu_item['below'], $menu_name);
			
			        // Build the child UL only if children are displayed for the user.
			        if ($children) {
			          	foreach ($children as $back_plid => $subMenu) {
			            	$output[$back_plid] = $children[$back_plid];
			          	}
			        }
		      	} else {
		        	$output[$plid][$mlid] = '<li class="'.$link['class'].'">'. theme('menu_item_link', $link) .'</li>'."\n";
		      	}
		    }
		}
		return $output;
	}
}
?>
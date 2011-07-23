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
class JDHandheldMenu extends JDMenu {
	function __construct($style = NULL, $name=NULL){
		
		parent::__construct($style, $name);
		
	}
	
	function render_handheld($tree, $menu_name){
		 $output = $this->inside_render($tree, $menu_name);
		 return array('mainnav'=>"<select id=\"handheld-nav\" onchange=\"window.location.href=this.value;\">".$output."</select>");
	}
	
	function inside_render($tree, $menu_name){
		// Backup active menu trail and set a new one
	  	$active_menu_name = menu_get_active_menu_name();
	  	menu_set_active_menu_name($menu_name);
	
	  	foreach (menu_set_active_trail() as $value) {
		    if ($value['mlid']) {
		      $trail[] = $value['mlid'];
		    }
	  	}
	
	  	menu_set_active_menu_name($active_menu_name);
	
	  	$output = '';
	  	foreach ($tree as $menu_item) {
		    $link = $menu_item['link'];
		    $mlid = $menu_item['link']['mlid'];
		
		    // Check to see if it is a visible menu item.
		    if ($menu_item['link']['hidden'] == 0) {
		
		      $link['selected'] = "";
		      if (is_array($trail) && in_array($link['mlid'], $trail)) {
		        $link['in_active_trail'] = TRUE;
		        $link['selected'] .= "selected=\"selected\"";
		      }
		      
					$indent = '&nbsp;&nbsp;&nbsp;&nbsp;|';
					$space = '---';
					$prespace = '';
					for ($i=0;$i<$link['depth']-1; $i++) 
					  $prespace .= $indent;
					if($link['depth'] > 1)
					  $prespace .= $space;
					
					$href = check_url(url($link['href'], $link['localized_options']));
		      
		      // If it has children build a nice little tree under it.
		      if ((!empty($link['has_children'])) && (!empty($menu_item['below']))) {
		        $children = $this->inside_render($menu_item['below'], $menu_name);
		        $output .= "<option ".$link['selected']." value=\"{$href}\">{$prespace}{$link['title']}</option>"."\n";
		        if ($children) {
		          $output .= $children;
		        }
		      }
		      else {
		        $output .= "<option ".$link['selected']." value=\"{$href}\">{$prespace}{$link['title']}</option>"."\n";
		      }
		    }
	
	  	}
	  	return $output;
	}
}
?>
<?php
// $Id: page.tpl.php,v 1.34 2011/01/01 13:20:14 jarek Exp $

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['navbar']: Items for the navbar region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar']: Items for the sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
?>
<div id="ja-wrapper">
	<a id="Top" name="Top"></a>
		<div id="ja-header" class="wrap">		
			<div class="main">
				<div class="main-inner1 clearfix">
					<h1 class="logo"><a title="DØK INTRO 2011" href="/intro2011/index.php"><span>DØK INTRO 2011</span></a></h1>
					<div id="ja-search">
						<p><a href="/intro2011/index.php?option=com_comprofiler&amp;task=registers&amp;Itemid=66"><img border="0" src="/intro2011/templates/ja_events/images/register.png" width="370" height="50"></a></p>
					</div> 
				</div>
		 	</div>
		</div>		
		<div id="ja-mainnav" class="wrap">
			<div class="main">
				<div class="main-inner1">
					<div class="main-inner2 clearfix">
						<div id="ja-megamenu" class="ja-megamenu clearfix">
						<?php print render($page['navigation']); ?>
						</div>	
						<div class="ja-mainnav-mask">&nbsp;</div>
					</div>
				</div>
			</div>
		</div>		
		<ul class="no-display">
			<li><a title="Skip to content" href="#ja-content">Skip to content</a></li>
		</ul>
		<!-- MAIN CONTAINER -->
		<div id="ja-container" class="wrap ja-r1">
			<div class="main">
				<div class="main-inner1 clearfix">
					<div style="width: 73.99%;" id="ja-mainbody">
						<!-- CONTENT -->
						<div style="width: 99.99%; min-height: 973px;" id="ja-main">
							<div class="inner clearfix">
							<?php print render($page['content']); ?>
							</div>
						</div>
						<!-- //CONTENT -->
					</div>
					<!-- RIGHT COLUMN--> 
					<div style="width: 26%; min-height: 973px;" id="ja-right" class="column sidebar">
						<div class="ja-colswrap clearfix ja-r1">
							<div style="width: 100%; min-height: 973px;" id="ja-right1" class="ja-col  column">
							<?php print render($page['sidebar']); ?>
							</div>
						</div>
					</div>
					<!-- //RIGHT COLUMN--> 
				</div>
			</div>
		</div>
		<!-- //MAIN CONTAINER -->
		<div id="ja-bot-banner" class="wrap clearfix">		
		</div>		
		<div id="ja-navhelper" class="wrap">		
			<div class="main">
				<div class="main-inner1 clearfix">
					<div class="ja-breadcrums">
					<!-- Breadcrumbs -->
					</div>
					<ul class="ja-links">
						<li class="layout-switcher">&nbsp;</li>
						<li class="top"><a title="Back to Top" href="#Top">Top</a></li>
					</ul>
					
					<ul class="no-display">
						<li><a title="Skip to content" href="#ja-content">Skip to content</a></li>
					</ul>
				</div>
			</div>
		</div>		
		<div id="ja-footer" class="wrap">		
			<div class="main clearfix">
				<div class="ja-copyright">
				</div>
			</div>
		</div>		
	</div>
</div>

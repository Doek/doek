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
<div id="wrapper">
	<div id="header">
		<h1 id="logo">
			<a href="/intro2011/index.php" title="DØK INTRO 2011"></a>
		</h1>

		<div id="search">
			<?php print render($page['header']); ?>
		</div>
	</div>

	<!-- Main Nav -->
	<div id="mainnav">
	<div id="mainnav-inner">
		<div class="megamenu clearfix" id="megamenu">
			<?php print render($page['navbar']); ?>
		</div>
		<div class="mainnav-mask">&nbsp;</div>
	</div>
	</div>
	<!-- //Main Nav --> 
             

	<!-- MAIN CONTAINER -->
	<div id="container">
	<div id="container-inner">
		<!-- RIGHT COLUMN-->
		<div id="sidebar" class="column sidebar">
			<div id="colswrap clearfix">
				<?php print render($page['sidebar']); ?>
			</div>
		</div>
		<!-- //RIGHT COLUMN-->     
		<!-- CONTENT -->
		<div id="mainbody">
			<div id="main" style="width:100%">
				<div class="inner clearfix">
					<h2>Velkommen til Ha(it) Intro 2011!</h2>
					<p>Intro 2011 byder dig velkommen til dette års introforløb og starten på dit nye studie HA(it.),
					der i daglig tale kaldes DØK af de studerende. Vi ser allerede nu frem til at dele din første tid
					på studiet med dig og vi har forberedt en masse spændende og sjove aktiviteter som vil gøre det
					sjovt for alle!<br /><br />På denne side vil der kunne findes oplysninger om mødetid og sted,
					ligesom et samlet program for intro-ugerne vil være at finde efterhånden som vi nærmer os.<br />
					<br />Introforløbet i august er opbygget således:<ul><li>Fredag d. 12.: Introdag på CBS</li>
					<li>Søndag d. 14. - torsdag d. 18.: Introtur</li><li>Mandag d. 22. - torsdag d. 25.: Introdage på CBS</li></ul><br /><br />Stay tuned!</p>
				</div>
			</div>
		</div>
		<!-- //CONTENT -->
	</div>
	</div>
	<!-- //Main Container -->
</div>

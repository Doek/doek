<?php

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
 *   or themes/bartik.
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
 * - $main_menu_links (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu_links (array): An array containing the Secondary menu links for
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
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
?>

  <div id="page" class="mast">

    <header role="banner" class="masthead">
      <?php if ($logo): ?>
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" class="logo grid-inner">
          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
        </a>
      <?php endif; ?>
      <?php if ($site_name): ?>
        <h1 class="site-name grid-inner">
          <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
        </h1>
      <?php endif; ?>
      <?php if ($site_slogan): ?>
        <h2 class="site-slogan grid-inner"><?php print $site_slogan; ?></h2>
      <?php endif; ?>
      <?php print render($page['header']); ?>

      <?php if ($main_menu_links): ?>
        <nav class="primary-menu grid-inner" role="navigation">
          <?php print $main_menu_links; ?>
        </nav>
      <?php endif; ?>

      <?php if ($secondary_menu_links): ?>
        <nav class="secondary-menu grid-inner" role="navigation">
          <?php print $secondary_menu_links; ?>
        </nav>
      <?php endif; ?>
    </header>

    <?php print render($page['featured']); ?>

    <?php print render($page['preblocks']); ?>

    <?php if ($breadcrumb): ?>
      <nav class="breadcrumb grid-inner"><?php print $breadcrumb; ?></nav>
    <?php endif; ?>

    <?php print $messages; ?>

    <div class="wrap-columns clearfix">

      <div class="content-column">
        <div role="main" class="main">
          <?php print render($page['highlighted']); ?>
          <a class="main-content"></a>
          <?php print render($title_prefix); ?>
          <?php if ($title): ?><h1 class="title page-title grid-inner"><?php print $title; ?></h1><?php endif; ?>
          <?php print render($title_suffix); ?>
          <?php if ($tabs = render($tabs)): ?><div class="tabs grid-inner clearfix"><?php print $tabs; ?></div><?php endif; ?>
          <?php print render($page['help']); ?>
          <?php if ($action_links = render($action_links)): ?><ul class="action-links grid-inner"><?php print $action_links; ?></ul><?php endif; ?>
          <?php print render($page['content_top']); ?>
          <?php print render($page['content']); ?>
          <?php print render($page['content_bottom']); ?>
          <?php if ($feed_icons): ?><div class="grid-inner wrap-feed-icons"><?php print $feed_icons; ?></div><?php endif; ?>
        </div> <!-- end .main -->
      </div> <!-- end .content-column -->

      <?php print render($page['sidebar_first']); ?>
      <?php print render($page['sidebar_second']); ?>

    </div> <!-- end .wrap-columns -->

    <?php print render($page['postblocks']); ?>

    <?php print render($page['footer']); ?>
    <?php if (isset($arctica_attr_link)): print $arctica_attr_link; endif; ?>

  </div> <!-- end #page -->

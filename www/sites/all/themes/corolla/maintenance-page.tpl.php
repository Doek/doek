<?php
// $Id: maintenance-page.tpl.php,v 1.13 2011/01/01 13:20:14 jarek Exp $

/**
 * @file
 * Default theme implementation to display a single Drupal page while offline.
 *
 * All the available variables are mirrored in page.tpl.php. Some may be left
 * blank but they are provided for consistency.
 *
 * @see template_preprocess()
 * @see template_preprocess_maintenance_page()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>

<body class="<?php print $classes; ?>">
  <div id="header-wrapper">
    <div id="header" class="clearfix">

      <div id="branding-wrapper" class="clearfix">
        <div id="branding">
          <?php if ($logo): ?>
            <div id="logo"><a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home">
              <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
            </a></div>
          <?php endif; ?>

          <?php if ($site_name || $site_slogan): ?>
            <div id="name-and-slogan">
              <?php if ($site_name): ?>
                <?php if ($title): ?>
                  <div id="site-name">
                    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
                  </div>
                <?php else: /* Use h1 when the content title is empty */ ?>
                  <h1 id="site-name">
                    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
                  </h1>
                <?php endif; ?>
              <?php endif; ?>

              <?php if ($site_slogan): ?>
                <div id="site-slogan"><?php print $site_slogan; ?></div>
              <?php endif; ?>
            </div> <!-- /#name-and-slogan -->
          <?php endif; ?>

        </div> <!-- /#branding -->
      </div> <!-- /#branding-wrapper -->

    </div> <!-- /#header -->
  </div>  <!-- /#header-wrapper -->

  <div id="main-columns-wrapper">
    <div id="main-columns">
      <div id="main">
        <div id="page" class="clearfix">
          <?php if ($messages): ?><div id="messages"><?php print $messages; ?></div><?php endif; ?>
          <div id="main-content"></div>
          <?php if ($title): ?>
            <h1 class="page-title"><?php print $title; ?></h1>
          <?php endif; ?>
          <?php if ($content): ?>
            <div id="content">
              <?php print $content; ?>
            </div>
          <?php endif; ?>
        </div> <!-- /#page -->
      </div> <!-- /#main -->

    </div> <!-- /#main-columns -->
  </div> <!-- /#main-columns-wrapper -->

</body>
</html>

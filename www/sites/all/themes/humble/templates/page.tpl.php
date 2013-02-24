
<div id="wrapper" class="container_12">

  <div id="header-holder">

    <div id="header">

      <?php if ($navigation): ?>
        <div id="nav" class="grid_8">
          <?php print $navigation; ?>

        </div>
      <?php endif; ?>


      <?php if ($logo): ?>
        <div id="logo" class="grid_4">

          <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home">
            <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
          </a>

        </div>
      <?php endif; ?>

    </div><!-- #header -->

  </div><!-- #header-holder -->



  <div id="main-holder">

    <div id="main-holder">

      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1 id="page-title" class="big-title"><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>


      <?php if ($breadcrumb): ?>
        <div id="breadcrumb" class="grid_12 omega alpha">
          <?php print $breadcrumb; ?>
        </div>
      <?php endif; ?>


      <?php
      $content_class = 'grid_12';
      if ($page['sidebar_second'] || $page['sidebar_first']) {
        $content_class = 'grid_8';
      }
      if ($page['sidebar_second'] && $page['sidebar_first']) {
        $content_class = 'grid_4';
      }
      ?>

      <?php
      if ($slider_output):
        ?>
        <div id="slide">
          <div id="sleft" class="grid_4 alpha omega">
            <?php print $slider_left_text; ?>
          </div>
          <div id="sright" class="grid_8 alpha omega" style="position:relative;">
            <?php print $slider_output; ?>

          </div>
        </div> <!-- slide -->

        <div class="clear"></div>
      <?php endif; ?>


      <?php if ($page['sidebar_first']): ?>
        <div id="sidebar" class="grid_4">
          <?php print render($page['sidebar_first']); ?>
        </div><!-- #sidebar -->		
      <?php endif; ?>

      <div id="main" class="<?php print $content_class; ?>">

        <div id="page_content">
          <?php if ($messages): ?>
            <div id="messages" class="message"><div class="message_box_content section clearfix">
                <?php print $messages; ?>
              </div></div> <!-- /.section, /#messages -->
          <?php endif; ?>
            <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
            <?php print render($page['help']); ?>
          <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
          <?php print render($page['content']); ?>
          <?php print $feed_icons; ?>
        </div>

      </div>



      <?php if ($page['sidebar_second']): ?>
        <div id="sidebar" class="grid_4">
          <?php print render($page['sidebar_second']); ?>
        </div><!-- #sidebar -->		

      <?php endif; ?>
      <div class="clear"></div>

    </div>

  </div><!-- #main-holder-->


  <div id="footer">
    <?php if ($page['footer_firstcolumn'] || $page['footer_secondcolumn'] || $page['footer_thirdcolumn'] || $page['footer_fourthcolumn']): ?>
      <div id="footer-top">
        <div class="section clearfix">
          <?php if ($page['footer_firstcolumn']): ?>
            <div class="grid_3">
              <?php print render($page['footer_firstcolumn']); ?>
            </div>
          <?php endif; ?>



          <?php if ($page['footer_secondcolumn']): ?>
            <div class="grid_3">
              <?php print render($page['footer_secondcolumn']); ?>
            </div>
          <?php endif; ?>


          <?php if ($page['footer_thirdcolumn']): ?>
            <div class="grid_3">
              <?php print render($page['footer_thirdcolumn']); ?>
            </div>
          <?php endif; ?>


          <?php if ($page['footer_fourthcolumn']): ?>
            <div class="grid_3">
              <?php print render($page['footer_fourthcolumn']); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>


    <div class="clear"></div>
    <?php if ($page['footer']): ?>
      <div id="footer-bottom">
        <div class="section clearfix">
          <?php print render($page['footer']); ?>  
        </div>
      </div>
    <?php endif; ?>


  </div><!-- #footer -->

</div><!-- #wrapper -->

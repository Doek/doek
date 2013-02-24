<!-- Google Code for Galla-forside Remarketing List -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 953781785;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "Y3gpCK-U8QMQmZzmxgM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/953781785/?label=Y3gpCK-U8QMQmZzmxgM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<div id="page">

<!--header-top-->
<div id="header-top">
    <div id="header-top-inside" class="clearfix">
    	
        <!--header-top-inside-left-->
        <div id="header-top-inside-left"><?php print render($page['header']); ?></div>
        <!--EOF:header-top-inside-left-->
        
        <!--header-top-inside-left-right-->
        <div id="header-top-inside-right"><?php print render($page['search_area']);?></div> 
        <!--EOF:header-top-inside-left-right-->
         
    </div>
</div>
<!--EOF:header-top-->
    
<div id="wrapper">
	
    <!--header-->
    <div id="header" class="clearfix">
    	
        <!--logo-floater-->
        <div id="logo-floater"> 
        <?php if ($logo): ?>
            <a href="<?php print check_url($front_page); ?>" title="<?php print t('Home'); ?>">
            <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
            </a>
        <?php endif; ?>
        </div> <!--EOF:logo-floater-->
        
        <!--navigation-->
        <div id="navigation">
			<?php $menu_name = variable_get('menu_main_links_source', 'main-menu');
            $main_menu_tree = menu_tree($menu_name); 
            print drupal_render($main_menu_tree); ?>
        </div><!--EOF:navigation-->
       
    </div><!--EOF:header-->

    <div id="banner">
    <?php print render($page['banner']); ?>
    
        <?php
      $url1 = theme_get_setting('slide1_url','bluemasters'); $cap1 = theme_get_setting('slide1_desc','bluemasters'); $img1 = file_create_url(theme_get_setting('slide1_image','bluemasters'));
      $url2 = theme_get_setting('slide2_url','bluemasters'); $cap2 = theme_get_setting('slide2_desc','bluemasters'); $img2 = file_create_url(theme_get_setting('slide2_image','bluemasters'));
      $url3 = theme_get_setting('slide3_url','bluemasters'); $cap3 = theme_get_setting('slide3_desc','bluemasters'); $img3 = file_create_url(theme_get_setting('slide3_image','bluemasters'));
      
      $show1 = !empty($url1) && !empty($cap1) && !empty($img1);
      $show2 = !empty($url2) && !empty($cap2) && !empty($img2);
      $show3 = !empty($url3) && !empty($cap3) && !empty($img3);
          
        ?>
        <?php if (theme_get_setting('slideshow_display','bluemasters') && ($show1 || $show2 || $show3)) : ?>
        <div class="main_view">
            <div class="window">
                <div class="image_reel">
                    <?php if ($show1) : ?><a href="<?php print url($url1); ?>"><img src="<?php print $img1; ?>" /></a><?php endif; ?>
                    <?php if ($show2) : ?><a href="<?php print url($url2); ?>"><img src="<?php print $img2; ?>" /></a><?php endif; ?>
                    <?php if ($show3) : ?><a href="<?php print url($url3); ?>"><img src="<?php print $img3; ?>" /></a><?php endif; ?>
                </div>
                <div class="descriptions">
                    <?php if ($show1) : ?><div class="desc" style="display: none;"><?php print $cap1; ?></div><?php endif; ?>
                    <?php if ($show2) : ?><div class="desc" style="display: none;"><?php print $cap2; ?></div><?php endif; ?>
                    <?php if ($show3) : ?><div class="desc" style="display: none;"><?php print $cap3; ?></div><?php endif; ?>
                </div>
            </div>
        
            <div class="paging">
                <?php if ($show1) : ?><a rel="1" href="#">1</a><?php endif; ?>
                <?php if ($show1) : ?><a rel="2" href="#">2</a><?php endif; ?>
                <?php if ($show1) : ?><a rel="3" href="#">3</a><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div><!--EOF:banner-->

    <div id="home-blocks-area" class="clearfix">
    
		<?php if ($messages): ?>
        <div id="console" class="clearfix">
        <?php print $messages; ?>
        </div>
        <?php endif; ?>
    
        <div class="home-block-area first">
            <?php print render($page['home_area_1']);?> 		
        </div>
        <div class="home-block-area">
            <?php print render($page['home_area_2']);?> 
        </div>
        <div class="home-block-area last">
            <?php print render($page['home_area_3']);?> 
            <?php print render($page['home_area_3_b']);?> 
        </div>
    </div>

</div><!--EOF:wrapper-->

<!--footer-->
<div id="footer">
    <div id="footer-inside" class="clearfix">
    
    	<div id="footer-left">
    		<div id="footer-left-1">
    			<?php print render($page['footer_left_1']);?>
    		</div>
    		<div id="footer-left-2">
    			<?php print render($page['footer_left_2']);?>
    		</div>
        </div>
        
        <div id="footer-center">
        	<?php print render($page['footer_center']);?>
        </div>
        
        <div id="footer-right">
        	<?php print render($page['footer_right']);?>
        </div>
        
    </div>
</div>
<!--EOF:footer-->

<!--footer-bottom-->
<div id="footer-bottom">
    <div id="footer-bottom-inside" class="clearfix">
    	<div>
    		<?php print render($page['footer']);?>
    	</div>
</div>
<!--EOF:footer-bottom-->

</div><!--EOF:page-->

<div id="page">

<!--header-top-->
<div id="header-top">
    <div id="header-top-inside" class="clearfix">
    	
        <!--header-top-inside-left-->
        <div id="header-top-inside-left"><?php print render($page['header']); ?></div>
        <!--EOF:header-top-inside-left-->
        
        <!--header-top-inside-left-feed-->
        <div id="header-top-inside-left-feed">
            <div id="topSocial">
            <ul>									
            </ul>
            </div>
        </div>
        <!--EOF:header-top-inside-left-feed-->
        
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
            
            <?php if ($site_name || $site_slogan): ?>
            <div class="clearfix">
				<?php if ($site_name): ?>
                <span id="site-name"><a href="<?php print check_url($front_page); ?>" title="<?php print t('Home'); ?>"><?php print strtoupper($site_name); ?></a></span>
                <?php endif; ?>
                
                <?php if ($site_slogan): ?>
                <span id="slogan"><?php print $site_slogan; ?></span>
                <?php endif; ?>
            </div>
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
    
        <div class="main_view">
            <div class="window">
                <div class="image_reel">
                	<iframe src="http://player.vimeo.com/video/27286358?title=0&amp;byline=0&amp;portrait=0" width="930" height="320" frameborder="0"></iframe><p><a href="http://vimeo.com/27286358">DØK Galla 2011 Promo</a></p>
		</div>
                <div class="descriptions">
                    <div class="desc" style="display: none;">DØK Galla 2011</div>
                </div>
            </div>
        
            <div class="paging">
            </div>
        </div>
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
    	<div id="footer-bottom-inside-left">
    		<?php print render($page['footer']);?>
    	</div>
</div>
<!--EOF:footer-bottom-->

</div><!--EOF:page-->

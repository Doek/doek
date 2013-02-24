(function ($) {
  $(document).ready(function() {
    
    $('.portfolio #filters li:first a').addClass('selected first');
    $('.portfolio #filters li:last a').addClass('last');
    //Superfish menu
    $('ul.sf-menu').superfish({
      delay: 400,
      animation: {
        opacity:'show', 
        height:'show'
      }, 
      speed: 'normal',
      autoArrows: false
    });
	
    $("#nav ul li").each(function(){
      if($(this).children('ul').length>0){
        $(this).find('a:first').append('<span class="drop-arrow">&raquo;</span>');
      }
    });

    //Hover photo from Flickr widget
    $('.flickr_badge_image img').hover(
      function () {
        $(this).animate({
          opacity: 1
        }, 200);
      },
      function () {
        $(this).animate({
          opacity: 0.6
        }, 200);
      }
      );
	
    //Hover Social links
    $('.hb_social_widget ul li').hover(
      function () {
        $('.hb_social_widget ul li').each(function(){
          $(this).removeClass('hover');
        });
        $(this).addClass('hover');
        $('.hb_social_widget ul li').each(function(){
          if ($(this).hasClass('hover')) {
            $(this).stop().animate({
              opacity: 1
            }, 200)
          } else {
            $(this).stop().animate({
              opacity: 0.4
            }, 200)
          }
				
        });
      }
      );
    $('.hb_social_widget').mouseleave(
      function () {
        $(this).find('ul li').each(function(){
          $(this).stop().animate({
            opacity: 1
          }, 200);
        });
      }
      );
	
    //Fancy hover	
    $('a.fancy .overlay_glass').hover(
      function () {
        $(this).animate({
          opacity: 0.5
        }, 200);
      },
      function () {
        $(this).animate({
          opacity: 0
        }, 200);
      }
      );
	
    //Fancy hover	
    $('.portfolio .thumbnail').mouseenter(function () {
      $(this).find('a.overlay').animate({
        opacity: 0.4
      }, 200);
      $(this).find('h3').animate({
        opacity: 0.8, 
        top: '0px'
      }, 250);
      $(this).find('.meta').animate({
        opacity: 0.8, 
        bottom: '0px'
      }, 250);
    });
    $('.portfolio .thumbnail, .portfolio.shortcode .thumbnail').mouseleave(function () {
		
      $(this).find('a.overlay').animate({
        opacity: 0
      }, 200);
      var pos = $(this).height() / 2 - 20;
      $(this).find('h3').animate({
        opacity: 0, 
        top: pos + 'px'
      }, 250, function(){
        $(this).css('top', pos + 'px');
      });
      $(this).find('.meta').animate({
        opacity: 0, 
        bottom: pos + 'px'
      }, 250, function(){
        $(this).css('bottom', pos + 'px');
      });
    });
	
    //Hover home boxes
    /*
    $('#boxes .one_third').hover(
      function () {
        var idBox = $(this).attr('id');
        $('#boxes').find('.one_third').each(function(){
          if ($(this).attr('id') != idBox) $(this).stop().animate({
            opacity: 0.5
          }, 200);
          else $(this).stop().animate({
            opacity: 1
          }, 100);
        });
      }
      );
        
        
    $('#boxes').mouseleave(
      function () {
        if ($('#boxes_content').height() == 0) {
          $('#boxes').find('.one_third').each(function(){
            $(this).stop().animate({
              opacity: 1
            }, 200);
          });
        } else {
          $('#boxes').find('.one_third').each(function(){
            if ($(this).hasClass('active')) $(this).stop().animate({
              opacity: 1
            }, 200);
            else $(this).stop().animate({
              opacity: 0.5
            }, 200);
          });
        }
      }
      );
	
    //Click home boxes
    $('#boxes .one_third').click(
      function () {
        idBox = $(this).attr('id');
        var active = ($(this).hasClass('active')) ? true : false;
        //remove active on box
        $('#boxes .one_third.active').removeClass('active');
        //hide current box content
        $('#boxes_content').find('.box_content').each(function(){
          if ($(this).css('display') == 'block') {
            $(this).fadeOut(50);
          }
        });
        if (active == false) {
          //show new box content
          $('#'+idBox+'_content').fadeIn('slow');
          heightBox = $('#'+idBox+'_content').height();
          $(this).addClass('active');
          $('#boxes_content').animate({
            'height': heightBox, 
            'marginBottom': 30
          }, 400);
        } else {
          $('#boxes_content').animate({
            'height': 0, 
            'marginBottom': 0
          }, 400);				
          $('#boxes').find('.one_third').each(function(){
            $(this).stop().animate({
              opacity: 1
            }, 200);
          });
        }
      }
      );
      
      */
	
    //Table style
    $('table tr:even').addClass('even');
	
    //Minitabs
    jQuery(".minitabs_container").each(function(){
      var $history = jQuery(this).attr('data-history');
      if($history!=undefined && $history == 'true'){
        $history = true;
      }else {
        $history = false;
      }
      var $initialIndex = jQuery(this).attr('data-initialIndex');
      if($initialIndex==undefined){
        $initialIndex = 0;
      }
      jQuery("ul.minitabs",this).tabs("div.panes > div", {
        tabs:'a', 
        effect: 'fade', 
        fadeOutSpeed: -400, 
        history: $history, 
        initialIndex: $initialIndex
      });
    });
    jQuery.tools.tabs.addEffect("slide", function(i, done) {
      this.getPanes().slideUp();
      this.getPanes().eq(i).slideDown(function()  {
        done.call();
      });
    });
	
    //Toggle
    jQuery(".toggle_title").toggle(
      function(){
        jQuery(this).addClass('active');
        jQuery(this).siblings('.toggle_content').slideDown("fast");
      },
      function(){
        jQuery(this).removeClass('active');
        jQuery(this).siblings('.toggle_content').slideUp("fast");
      }
      );
		
  });
})(jQuery);
/*
 * jQuery Polaroid v1.0 - http://karimhossenbux.com/lab/polaroid-slider/
 *
 * TERMS OF USE :
 * 
 * Copyright © 2011 Karim Hossenbux
 * All rights reserved.
 * 
 * Polaroid Slider by Karim Hossenbux is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License.
 * Based on a work at karimhossenbux.com
 * Permissions beyond the scope of this license may be available at http://www.karimhossenbux.com/contact/
 *
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
*/

(function($) {

	jQuery.fn.polaroid = function(options) {
		var defaults = {
			width: 650,
			pause: 5000			
		};
		
		var o = $.extend(defaults, options);
		
		return this.each(function() {
	
			var i = 0;
			var current = false;
			var start = false;
			var nb_thumbs = $('#thumbs').find('.thumb').length;
			var e = $(this);
			
			e.find('#thumbs').fadeIn(500);
			e.prepend('<div id="loading"></div><div id="big"><div id="altern"></div></div>');
			e.append('<div class="clearfix"></div>');
			e.find('.thumb').each(function(){
				i++;
				var space = o.width / (nb_thumbs + 1);
				var left = (space * i) - 50; //50 = half image width
				$(this).attr('id', 't'+i);
				$(this).stop().animate({'left':left+'px'}, 700, function(){
					$(this).unbind('click')
						   .bind('click', showImage)
						   .unbind('mouseenter')
						   .bind('mouseenter', upThumb)
						   .unbind('mouseleave')
						   .bind('mouseleave', downThumb);
				}).find('img').stop().animate({'rotate': getdeg()}, 300);
				$.preLoadImages($(this).find('img').attr('alt'));
			});
			
			function getdeg() {
				return deg = Math.floor(Math.random()* 41)-20 + 'deg';
			}
			
			function upThumb(){
				$(this).stop().animate({
					'marginTop'	: '-50px'
				}, 400, 'easeOutBack').find('img').stop().animate({'rotate': '0deg'}, 400);
			}
			
			function downThumb(){
				$(this).stop().animate({
					'marginTop' : '0px'
				}, 400, 'easeOutBack').find('img').stop().animate({'rotate': getdeg()}, 400);
			}
			
			function hideThumb(id){
		        if (current != false) $('#thumbs #'+current).stop(true, true).animate({'top': '0px'}, 400, 'easeOutBack');
		        $('#thumbs #'+id).stop(true, true).animate({'top': '120px'}, 400, 'easeInBack');
		    }
			
			function showImage() {
				var img = $(this).find('img').attr('alt');
				var goto = $(this).find('img').attr('title');
				hideThumb($(this).attr('id'));
				current = $(this).attr('id');
				var big = $('#big').css('backgroundImage');
				
				$('#altern').css('opacity', 1);		
				$('#altern').css('backgroundImage', big);
				$('#big').css('backgroundImage', 'url('+img+')');
				$('#altern').animate({'opacity': 0}, 1500);
				if (goto == '') {
					$('#goto').removeAttr('href');
				} else {
					$('#goto').attr('href', goto);
				}
		
			}
			
			$('#thumbs .thumb img').click(function () { //reset timer
				clearInterval(start);
				start = setInterval(function(){autoPlay();}, o.pause);
			});
			
			start = setTimeout(function(){
				autoPlay();
				start = setInterval(function(){autoPlay();}, o.pause);
				return start;
			}, 1000);
			
			function autoPlay() {
				if (current == false)
					var tmp = 't0';
				else 
					var tmp = current;
				tmp = tmp.replace('t', '');
				tmp = parseInt(tmp);
				if (tmp == nb_thumbs) {
					tmp = 0;
				}
				tmp++;
				$('.thumb#t'+tmp).click();
			}
	
		});
	};
	
	
}) (jQuery)
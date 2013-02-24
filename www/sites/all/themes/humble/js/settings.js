
(function ($) {
  

  Drupal.behaviors.humble = {
    attach: function (context, settings) {
      
      if(settings.humble.slider_use == 0){
     
        var dnav = true;
        var cnav = true;
        var knav = true;
        var on_hover = true;
        if(settings.nivo_slider.directionNav == 'false'){
          dnav = false;
        }
        if(settings.nivo_slider.controlNav == 'false'){
          cnav = false;
        }
        if(settings.nivo_slider.pauseOnHover == 'false'){
          on_hover = false;
        }
        if(settings.nivo_slider.keyboardNav){
          knav = false;
        }
        $(window).load(function() {
          $('#nivo').nivoSlider({
            effect			: settings.nivo_slider.effect,
            slices			: settings.nivo_slider.slices, 
            boxCols			: settings.nivo_slider.boxCols,
            boxRows			: settings.nivo_slider.boxRows,
            pauseTime		: settings.nivo_slider.pauseTime,
            directionNav	: dnav,
            controlNav		: cnav,
            keyboardNav		: knav,
            pauseOnHover	: on_hover,
            captionOpacity	: settings.nivo_slider.captionOpacity
          });
        
        });
      }else{
        $(document).ready(function() { 
          $('#polaroid').polaroid({
            pause: 5000
          });
        });
      }
    }
  };

})(jQuery);
// $Id: equalize-gpanels.js,v 1.2.4.1 2010/06/10 23:00:08 jmburnz Exp $
(function ($) {
  Drupal.behaviors.adaptivetheme_equalizegpanels = {
    attach: function(context) {
      $('.gpanel .block-inner').equalHeight();
    }
  };
})(jQuery);
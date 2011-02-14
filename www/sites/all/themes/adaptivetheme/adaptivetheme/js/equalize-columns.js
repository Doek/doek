// $Id: equalize-columns.js,v 1.2.4.1 2010/06/10 23:00:08 jmburnz Exp $
(function ($) {
  Drupal.behaviors.adaptivetheme_equalizecolumns = {
    attach: function(context) {
      $('#content-column, .sidebar').equalHeight();
    }
  };
})(jQuery);
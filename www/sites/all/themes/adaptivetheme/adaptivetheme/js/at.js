// $Id: at.js,v 1.1.2.3 2010/12/01 13:48:24 jmburnz Exp $
(function ($) {
  /**
   * Insert WAI-ARIA Landmark Roles (Roles for Accessible Rich Internet Applications)
   * http://www.w3.org/TR/2006/WD-aria-role-20060926/
   */
  Drupal.behaviors.adaptivetheme = {
    attach: function(context) {

      // Set role="banner" on the header element.
      $("#page > header").attr("role", "banner");

      // Set role="main" on #main-content div.
      $("#main-content").attr("role", "main");

      // Set role="search" on search forms.
      $("#search-theme-form").attr("role", "search");

      // Set role="contentinfo" on the page footer.
      $("footer").attr("role", "contentinfo");

      // Set role=article on nodes.
      $(".article").attr("role", "article");

      // Set role="nav" on navigation-like blocks.
      $("nav, .admin-panel, #breadcrumb").attr("role", "navigation");
      
      // Set role="complementary" on navigation-like blocks.
      $("aside").attr("role", "complementary");

      // Set role="region" on section elements.
      $("section").attr("role", "region");

      // Set role="region" on section elements.
      $("#search-block-form, #search-form").attr("role", "search");

    }
  };

  /**
   * In most instances this will be called using the built in theme settings.
   * However, if you want to use this manually you can call this file
   * in the info file and user the ready function e.g.:
   * 
   * This will set sidebars and the main content column all to equal height:
   *  (function ($) {
   *    Drupal.behaviors.adaptivetheme = {
   *      attach: function(context) {
   *        $('#content-column, .sidebar').equalHeight();
   *      }
   *    };
   *  })(jQuery);
   */
  jQuery.fn.equalHeight = function () {
    var height = 0;
    var maxHeight = 0;

    // Store the tallest element's height
    this.each(function () {
      height = jQuery(this).outerHeight();
      maxHeight = (height > maxHeight) ? height : maxHeight;
    });

    // Set element's min-height to tallest element's height
    return this.each(function () {
      var t = jQuery(this);
      var minHeight = maxHeight - (t.outerHeight() - t.height());
      var property = jQuery.browser.msie && jQuery.browser.version < 7 ? 'height' : 'min-height';

      t.css(property, minHeight + 'px');
   });
  };

})(jQuery);
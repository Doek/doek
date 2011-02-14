/* $Id: mayo-fontsize.js,v 1.1 2011/01/06 03:58:38 pixture Exp $ */
/**
 * @file
 * Adds javascript functions for font resizing
 */
jQuery(document).ready(function() {
  var originalFontSize = jQuery('body').css('font-size');

  // Reset font size
  jQuery(".resetFont").click(function() {
    jQuery('body').css('font-size', originalFontSize);
    return false;
  });
    
  // Increase font size
  jQuery(".increaseFont").click(function() {
    var currentFontSize = jQuery('body').css('font-size');
    var currentFontSizeNum = parseFloat(currentFontSize, 10);
    var newFontSizeNum = currentFontSizeNum + 1;
    if (20 >= newFontSizeNum) { /* max 20px */
      var newFontSize = newFontSizeNum + 'px';
      jQuery('body').css('font-size', newFontSize);
    }
    return false;
  });

  // Decrease font size
  jQuery(".decreaseFont").click(function() {
    var currentFontSize = jQuery('body').css('font-size');
    var currentFontSizeNum = parseFloat(currentFontSize, 10);
    var newFontSizeNum = currentFontSizeNum - 1;
    if (10 <= newFontSizeNum) { /* min 10px */
      var newFontSize = newFontSizeNum + 'px';
      jQuery('body').css('font-size', newFontSize);
    }
    return false;
  });
});

/* $Id: mayo-columns.js,v 1.1 2011/01/06 03:58:38 pixture Exp $ */
/**
 * @file
 * Equolize the column height in a specified group
 */
jQuery(document).ready(function() {
  mayoEqualHeight(jQuery("#top-columns .column-block"));
  mayoEqualHeight(jQuery("#bottom-columns .column-block"));
});

function mayoEqualHeight(group) {
  var tallest = 0;
  group.each(function() {
    var thisHeight = jQuery(this).height();
    if (thisHeight > tallest) {
      tallest = thisHeight;
    }
  });
  group.height(tallest);
}

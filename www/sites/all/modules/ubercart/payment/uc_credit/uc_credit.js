// $Id: uc_credit.js,v 1.7 2010/04/06 14:43:54 islandusurper Exp $

jQuery(document).ready(
  function () {
    jQuery('#cc_details_title').show(0);
    jQuery('#cc_details').hide(0);
  }
);

/**
 * Toggle credit card details on the order view screen.
 */
function toggle_card_details() {
  jQuery('#cc_details').toggle();
  jQuery('#cc_details_title').toggle();
}


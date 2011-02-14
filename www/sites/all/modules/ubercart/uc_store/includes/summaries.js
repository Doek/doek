// $Id: summaries.js,v 1.7 2010/07/15 20:32:39 islandusurper Exp $

/**
 * @file
 *   Adds some helper JS to summaries.
 */

/**
 * Modify the summary overviews to have onclick functionality.
 */
Drupal.behaviors.summaryOnclick = {
  attach: function(context, settings) {
    jQuery('.summary-overview:not(.summaryOnclick-processed)', context).prepend('<img src="' + settings.editIconPath + '" class="summary-edit-icon" />');

    jQuery('.summary-overview:not(.summaryOnclick-processed)', context).addClass('summaryOnclick-processed').click(function() {
      window.location = this.id;
    });
  }
}

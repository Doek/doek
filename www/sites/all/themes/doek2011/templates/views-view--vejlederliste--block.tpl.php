<?php
// $Id: views-view.tpl.php,v 1.13.4.4 2010/07/04 10:04:51 dereine Exp $
/**
 * @file views-view.tpl.php
 * Main view template
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 * - $admin_links: A rendered list of administrative links
 * - $admin_links_raw: A list of administrative links suitable for theme('links')
 *
 * @ingroup views_templates
 */
 drupal_add_css(drupal_get_path('theme', 'doek2011') . '/css/views-view--vejledere--block.css', array('group' => CSS_DEFAULT, 'every_page' => TRUE));
?>
<div class="vejledere-block">
  <?php if ($admin_links): ?>
    <?php print $admin_links; ?>
  <?php endif; ?>
  <h3>INTRO Vejledere 2011</h3>
    <div class="view-content clearfix">
	<div class="vejledere-inner clearfix">
	    <?php print $rows; ?>
	    <div class="vejledere-more">
	    	<a href="/vejledere" title="Se alle dine vejledere"><img src="/<?php print drupal_get_path('theme', 'doek2011') ?>/images/flere-vejledere.png" /></a>
	    </div>
      	</div>
    </div>

</div> <?php /* class view */ ?>

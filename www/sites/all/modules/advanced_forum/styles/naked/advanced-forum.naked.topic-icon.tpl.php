<?php
// $Id: advanced-forum.naked.topic-icon.tpl.php,v 1.1.2.1 2011/01/05 16:02:32 michellec Exp $

/**
 * @file
 * Display an appropriate icon for a forum post.
 *
 * Available variables:
 * - $new_posts: Indicates whether or not the topic contains new posts.
 * - $icon: The icon to display. May be one of 'hot', 'hot-new', 'new',
 *   'default', 'closed', or 'sticky'.
 *
 * @see template_preprocess_forum_icon()
 * @see advanced_forum_preprocess_forum_icon()
 */
?>
<?php if ($new_posts): ?>
  <a name="new">
<?php endif; ?>

<?php if (!empty($icon_class)): ?>
<span class="<?php print "topic-icon topic-icon-$icon_class"; ?>"><?php print "$icon_title"; ?></span>
<?php endif; ?>

<?php if ($new_posts): ?>
  </a>
<?php endif; ?>


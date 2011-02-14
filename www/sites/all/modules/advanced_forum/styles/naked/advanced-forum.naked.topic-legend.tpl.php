<?php
// $Id: advanced-forum.naked.topic-legend.tpl.php,v 1.1.2.1 2011/01/05 16:02:32 michellec Exp $

/**
 * @file
 * Theme implementation to show forum legend.
 *
 */
?>

<div class="forum-topic-legend clearfix">
  <div class="topic-icon-new"><?php print t('New posts'); ?></div>
  <div class="topic-icon-default"><?php print t('No new posts'); ?></div>
  <div class="topic-icon-hot-new"><?php print t('Hot topic with new posts'); ?></div>
  <div class="topic-icon-hot"><?php print t('Hot topic without new posts'); ?></div>
  <div class="topic-icon-sticky"><?php print t('Sticky topic'); ?></div>
  <div class="topic-icon-closed"><?php print t('Locked topic'); ?></div>
</div>
 
 
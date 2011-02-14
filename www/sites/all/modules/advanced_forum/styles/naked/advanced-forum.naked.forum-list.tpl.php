<?php
// $Id: advanced-forum.naked.forum-list.tpl.php,v 1.1.2.4 2011/02/06 12:14:14 troky Exp $

/**
 * @file
 * Default theme implementation to display a list of forums and containers.
 *
 * Available variables:
 * - $forums: An array of forums and containers to display. It is keyed to the
 *   numeric id's of all child forums and containers.
 * - $forum_id: Forum id for the current forum. Parent to all items within
 *   the $forums array.
 *
 * Each $forum in $forums contains:
 * - $forum->is_container: Is TRUE if the forum can contain other forums. Is
 *   FALSE if the forum can contain only topics.
 * - $forum->depth: How deep the forum is in the current hierarchy.
 * - $forum->zebra: 'even' or 'odd' string used for row class.
 * - $forum->name: The name of the forum.
 * - $forum->link: The URL to link to this forum.
 * - $forum->description: The description of this forum.
 * - $forum->new_topics: True if the forum contains unread posts.
 * - $forum->new_url: A URL to the forum's unread posts.
 * - $forum->new_text: Text for the above URL which tells how many new posts.
 * - $forum->old_topics: A count of posts that have already been read.
 * - $forum->num_posts: The total number of posts in the forum.
 * - $forum->last_reply: Text representing the last time a forum was posted or
 *   commented in.
 *
 * @see template_preprocess_forum_list()
 * @see theme_forum_list()
 */
?>


<?php $container_number = 0 ?>

<?php foreach ($forums as $forum_id => $forum): ?>

  <?php if ($forum->is_container): ?> <?php // *** Start container row *** ?>
    <?php $container_number++ ?>
    <?php if ($container_number > 1): ?>
      </tbody></table>
    <?php endif; ?>

    <table id="container-<?php print $container_number; ?>" class="forum-table forum-table-forums">
      <thead class="forum-header">
        <tr>
          <th class="forum-icon"></th>

          <?php if ($use_taxonomy_image): ?>
          <th class="forum-image"></th>
          <?php endif; ?>

          <th class="forum-name"><a href="<?php print $forum->link; ?>"><?php print $forum->name; ?></a></th>
          <th class="forum-number-topics"><?php print t('Topics');?></th>
          <th class="forum-posts"><?php print t('Posts'); ?></th>
          <th class="forum-last-post">
            <?php print t('Last post'); ?>
            <?php if (!empty($collapsible)): ?>
              <span id="forum-collapsible-<?php print $forum_id;?>" class="forum-collapsible">&nbsp;</span>
            <?php endif; ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <?php if ($forum->description): ?>
          <tr class="container-description">
            <td colspan="<?php print ($use_taxonomy_image ? 6 : 5) ?>">
              <?php print $forum->description; ?>
            </td>
          </tr>
        <?php endif; ?>

  <?php else: ?> <?php // *** Start forum row *** ?>
    <?php if ($forum->depth == 1): ?>
      <tr id="forum-<?php print $forum_id; ?>" class="forum-row <?php print $forum->zebra; ?>  container-<?php print $container_number; ?>-child">
        <td class="<?php print $forum->icon_classes ?>">
          <span class="forum-list-icon-wrapper"><span><?php print $forum->icon_text ?></span></span>
        </td>

        <?php if ($use_taxonomy_image): ?>
          <td class="forum-image-<?php print $forum_id; ?>">
            <?php print $forum->forum_image; ?>
          </td>
        <?php endif; ?>

        <td class="forum-details">
          <div class="forum-name">
            <a href="<?php print $forum->link; ?>"><?php print $forum->name; ?></a>
          </div>
          <?php if (!empty($forum->description)): ?>
            <div class="forum-description">
              <?php print $forum->description; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($forum->subforums)): ?>
            <div class="forum-subforums"><span class="forum-subforums-label"><?php print t("Subforums") ?>:</span> <?php print $forum->subforums; ?></div>
          <?php endif; ?>
        </td>

        <td class="forum-number-topics">
          <div class="forum-number-topics"><?php print $forum->num_topics ?>
            <?php if ($forum->new_topics): ?>
              <div class="forum-number-new-topics">
                <a href="<?php print $forum->new_url; ?>"><?php print $forum->new_text; ?></a>
              </div>
            <?php endif; ?>
          </div>
        </td>

        <td class="forum-number-posts">
          <?php print $forum->num_posts ?>
          <?php if ($forum->new_posts): ?>
              <br />
              <a href="<?php print $forum->new_url_posts; ?>"><?php print $forum->new_text_posts; ?></a>
          <?php endif; ?>
        </td>

        <td class="forum-last-reply">
          <?php print $forum->last_reply ?>
        </td>
      </tr>
    <?php endif; ?>
  <?php endif; ?>
<?php endforeach; ?>
  </tbody>
</table>

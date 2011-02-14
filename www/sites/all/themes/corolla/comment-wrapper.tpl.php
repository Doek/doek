<?php
// $Id: comment-wrapper.tpl.php,v 1.11 2010/07/06 20:25:21 jarek Exp $

/**
 * @file
 * Default theme implementation to wrap comments.
 *
 * Available variables:
 * - $content: The array of content-related elements for the node. Use
 *   render($content) to print them all, or print a subset such as
 *   render($content['comment_form']).
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default value has the following:
 *   - comment-wrapper: The current template type, i.e., "theming hook".
 *
 * The following variables are provided for contextual information.
 * - $node: Node object the comments are attached to.
 * The constants below the variables show the possible values and should be
 * used for comparison.
 * - $display_mode
 *   - COMMENT_MODE_FLAT
 *   - COMMENT_MODE_THREADED
 *
 * Other variables:
 * - $classes_array: Array of HTML class attribute values. It is flattened
 *   into a string within the variable $classes.
 *
 * @see template_preprocess_comment_wrapper()
 * @see theme_comment_wrapper()
 */
?>
<div id="comments-wrapper" <?php if ($node->comment_count == 0): ?>class="no-comments"<?php endif; ?>>
  <div id="comments" class="<?php print $classes; ?>"<?php print $attributes; ?>>
    <?php if ($node->type != 'forum' && !empty($content['comments'])): ?>

      <h2 class="comments">
        <?php print format_plural($node->comment_count, '1 comment', '@count comments'); ?>
      </h2>
    <?php endif; ?>

    <?php print render($content['comments']); ?>

    <?php if ($content['comment_form']): ?>
      <h2 class="title" id="add-comment-form"><?php print t('Post new comment'); ?></h2>
      <div id="comment-form-wrapper">
        <?php print render($content['comment_form']); ?>
      </div>
    <?php endif; ?>
  </div>
</div>

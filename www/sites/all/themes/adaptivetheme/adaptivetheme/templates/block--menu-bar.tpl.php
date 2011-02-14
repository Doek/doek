<?php
// $Id: block--menu-bar.tpl.php,v 1.1.2.2 2010/10/19 22:41:25 jmburnz Exp $
// add classes/attributes back in if you need them...
/* id="<?php print $block_html_id; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?> */
?>
<?php $tag = $block->subject ? 'nav' : 'div'; ?>
<<?php print $tag; ?>>
  <?php print render($title_prefix); ?>
  <?php if ($block->subject): ?>
    <h2<?php print $title_attributes; ?>><?php print $block->subject; ?></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php print $content ?>
</<?php print $tag; ?>>

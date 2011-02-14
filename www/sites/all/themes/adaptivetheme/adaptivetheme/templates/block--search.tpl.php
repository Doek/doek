<?php
// $Id: block--search.tpl.php,v 1.1.2.2 2010/10/15 19:54:17 jmburnz Exp $
?>
<div id="<?php print $block_html_id; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <div class="block-inner clearfix">
    <?php print render($title_prefix); ?>
    <?php if ($block->subject): ?>
      <h2<?php print $title_attributes; ?>><?php print $block->subject; ?></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>
    <div<?php print $content_attributes; ?>>
      <?php print $content ?>
    </div>
  </div>
</div>

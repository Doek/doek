<?php
// $Id: field.tpl.php,v 1.1.2.6 2010/10/19 22:41:25 jmburnz Exp $
?>
<?php $tag = $label_hidden ? 'div' : 'section'; ?>
<<?php print $tag; ?> class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php if (!$label_hidden) : ?>
    <h2 class="field-label"<?php print $title_attributes; ?>><?php print $label ?>:&nbsp;</h2>
  <?php endif; ?>
  <?php foreach ($items as $delta => $item) : ?>
    <div class="field-item <?php print $delta % 2 ? 'odd' : 'even'; ?>"<?php print $item_attributes[$delta]; ?>><?php print render($item); ?></div>
  <?php endforeach; ?>
</<?php print $tag; ?>>

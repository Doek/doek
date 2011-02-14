<?php
// $Id: node.tpl.php,v 1.2.4.13 2010/12/03 06:15:05 jmburnz Exp $
?>
<article id="article-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print $unpublished; ?>

  <?php if ($title && !$page): ?>
    <header>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1<?php print $title_attributes; ?>>
          <a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a>
        </h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
    </header>
  <?php endif; ?>

  <?php if ($display_submitted): ?>
    <footer class="submitted">
      <?php print $user_picture; ?>
      <?php print $submitted; ?>
    </footer>
  <?php endif; ?>

  <div<?php print $content_attributes; ?>>
  <?php
    hide($content['comments']);
    hide($content['links']);
    print render($content);
  ?>
  </div>

  <?php if (!empty($content['links'])): ?>
    <nav class="clearfix"><?php print render($content['links']); ?></nav>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

</article>

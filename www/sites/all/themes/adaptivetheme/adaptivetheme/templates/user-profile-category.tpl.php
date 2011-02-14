<?php
// $Id: user-profile-category.tpl.php,v 1.1.2.2 2010/10/14 05:36:19 jmburnz Exp $
?>
<section class="<?php print drupal_html_class($title); ?>">
  <?php if ($title) : ?>
    <h3><?php print $title; ?></h3>
  <?php endif; ?>
  <dl<?php print $attributes; ?>>
    <?php print $profile_items; ?>
  </dl>
</section>

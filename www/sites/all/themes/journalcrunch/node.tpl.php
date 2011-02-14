<?php if ($is_front || arg(0)=='taxonomy') {

if (!variable_get('isFirstNoStickyNode', $default = NULL)){

	if (!$node->sticky) {
	
		print '<div class="clear-block" style="clear:both;"></div>';
		variable_set('isFirstNoStickyNode', true);
		
	}
} ?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> node-front <?php if (arg(0)=='taxonomy') { print ' node-taxonomy'; } ?>"<?php print $attributes; ?>>

    <div class="nodeInner">

		<?php print $user_picture; ?>
        
        <?php print render($title_prefix); ?>
        <?php if (!$page): ?>
        <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
        <?php endif; ?>
        <?php print render($title_suffix); ?>

      <div class="content clearfix"<?php print $content_attributes; ?>>
        <?php
          // We hide the comments and links now so that we can render them later.
          hide($content['comments']);
          hide($content['links']);
          print render($content);
        ?>
      </div>
    
      <div class="clearfix" style="color: #888888; font-size: 11px; margin: 0; text-transform: uppercase;">
        <?php if (!empty($content['links'])): ?>
          <div class="links"><?php print render($content['links']); ?></div>
        <?php endif; ?>
    
        <?php print render($content['comments']); ?>
      </div>
            
   </div>  

</div>

<?php } else { ?>

<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

	<?php print $user_picture; ?>
    
    <?php print render($title_prefix); ?>
    <h1<?php print $title_attributes; ?>><?php print $title; ?></h1>
    <?php print render($title_suffix); ?>
        
  <?php if ($display_submitted): ?>
    <div class="submitted"><?php print $submitted ?></div>
  <?php endif; ?>

  <div class="content clearfix"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    ?>
  </div>

  <div class="clearfix" style="color: #888888; font-size: 11px; margin: 0; text-transform: uppercase;">
    <?php if (!empty($content['links'])): ?>
      <div class="links"><?php print render($content['links']); ?></div>
    <?php endif; ?>

    <?php print render($content['comments']); ?>
  </div>

</div>

<?php } ?>

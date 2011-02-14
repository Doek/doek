<div id="node-<?php //print $node->nid; ?>" class="<?php //print $classes; ?> node-front <?php if (arg(0)=='taxonomy') { print ' node-taxonomy'; } ?>"<?php //print $attributes; ?>>

    <div class="nodeInner">

        <?php //print render($title_prefix); ?>
        <?php if (!$page): ?>
        <h2<?php //print $title_attributes; ?>><?php //print $fields['view_node']->content; ?><?php print $fields['title']->content; ?></h2>
        <?php endif; ?>
        <?php //print render($title_suffix); ?>

      <div class="content clearfix"<?php //print $content_attributes; ?>>
        <?php
          // We hide the comments and links now so that we can render them later.


	print $fields['entity_id_1']->content."<br />";  
	print $fields['entity_id_2']->content."<br />";  
	print $fields['entity_id']->content;        
	?>
      </div>

      <div class="clearfix" style="color: #888888; font-size: 11px; margin: 0; text-transform: uppercase;">
          <div class="links">
		<ul class="links inline">
		<li class="node-readmore">
			<?php print $fields['view_node']->content; ?>
		</li>
		</ul>
   	  </div>
      </div>

   </div>

</div>


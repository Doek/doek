
<div class="node_front">
<?php
<<<<<<< HEAD
echo "<h2><span class='site'>".$fields['sitename']->content."</span> | <span class='node-title'>".$fields['title']->content."</span></h2>";
=======
echo "<h2><span class='site'>".$fields['domain_name']->content."</span>|<span class='node-title'>".$fields['title']->content."</span></h2>";
>>>>>>> joelving/master
echo "<span class='date-time'>".$fields['entity_id_2']->content."</span>";
?>
	<div class='picture'>
		<?php echo $fields['entity_id_1']->content; ?>
	</div>
	<div class='description'>
		<?php echo $fields['entity_id']->content; ?>
	</div>
<div class="clearfix"></div>

<p class="read_more"><?php echo $fields['view_node']->content; ?></p>
<br />
</div>

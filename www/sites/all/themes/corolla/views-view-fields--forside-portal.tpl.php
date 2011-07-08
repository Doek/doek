
<div class="node_front">
<?php

$domain = domain_lookup($fields['domain_id']->content);

echo "<h2><span class='site'><a href='http://".$domain['subdomain']."'>".$domain['sitename']."</a></span> | <span class='node-title'>".$fields['title']->content."</span></h2>";
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

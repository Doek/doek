
<div class="node_front">
<?php
echo "<h2>".$fields['title']->content."</h2>";
if(isset($fields['entity_id_2'])){
   echo "<span class='date-time'>".$fields['entity_id_2']->content."</span>";
}
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

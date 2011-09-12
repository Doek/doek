<div class="node_front">
<?php

$domain = domain_lookup($fields['domain_id']->content);

echo "<h2><span class='site'><a href='http://".$domain['subdomain']."'>".$domain['sitename']."</a></span> | <span class='node-title'>".$fields['title']->content."</span></h2>";
   
echo "<span class='date-time'>";

if(isset($fields['entity_id_2'])){
  echo $fields['entity_id_2']->content;
}
if (isset($fields['comment_count'])){
  echo " - ".$fields['comment_count']->content;
}
if(isset($fields['nid']) && $fields['status']->content == 'Open'){
	$result = db_query('SELECT COUNT(*) as result FROM {signup_log} sl WHERE sl.nid = :nid', array('nid' => $fields['nid']->content));
	$signcount = $result->fetchAssoc();
	
	echo ' - Tilmeldinger: '.$signcount['result'];
}
   echo "</span>";
?>
	<div class='picture'>
		<?php echo $fields['entity_id_1']->content; ?>
	</div>
	<div class='description'>
		<?php echo $fields['entity_id']->content; ?>
	</div>
<div class="clearfix"></div>

<p class="read_more"><?php echo $fields['view_node']->content; ?></p><p class="signup"><?php if(isset($fields['node_link']->content)){echo $fields['node_link']->content;} ?></p>
<br />
</div>

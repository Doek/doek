<?php
// $Id: views-view-fields.tpl.php,v 1.6.6.1 2010/12/04 07:39:35 dereine Exp $
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
 drupal_add_css(drupal_get_path('theme', 'doek2011') . '/css/views-view--vejledere--page.css', array('group' => CSS_DEFAULT, 'every_page' => TRUE));
?>

<div class="vejledere-page-item" >
	<div class="vejledere-inner">

		<h2><?php echo $fields['entity_id']->content; ?></h2>

		<div class="vejledere-content">
			<div class="vejledere-content-inner clearfix">
				<div class="vejledere-picture clearfix">
					<?php echo $fields['picture']->content; ?>
					<span class="caption"><?php echo $fields['entity_id']->content; ?></span>
					<span class="corner"> </span>
				</div>
				<ul>
					<?php if (!empty($fields['entity_id_5']->content)) { ?><li><strong>Alder:</strong> <?php echo $fields['entity_id_5']->content; ?></li><?php } ?>
					<?php if (!empty($fields['entity_id_3']->content) || !empty($fields['entity_id_2']->content)) { ?><li><strong>Studiejob:</strong>  <?php echo $fields['entity_id_3']->content; if (!empty($fields['entity_id_2']->content)) { ?> hos <?php echo $fields['entity_id_2']->content; ?></li><?php } } ?>
					<?php if (!empty($fields['entity_id_4']->content)) { ?><li><strong>Årgang:</strong> <?php echo $fields['entity_id_4']->content; ?></li><?php } ?>
					<?php if (!empty($fields['entity_id_1']->content)) { ?><li><strong>Om vejlederen:</strong> <?php echo $fields['entity_id_1']->content; ?></li><?php } ?>
				</ul>
			</div>
		</div>
		<p class="vejleder-more"><a href="/users/<?php echo $fields['name']->content; ?>" title="Læs mere">Læs mere...</a></p>
	</div>
</div>

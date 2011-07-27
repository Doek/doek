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
 $names = explode(" ", $fields['field_name']->content);
?>

<div class="vejledere-page-item" >
	<div class="vejledere-inner">

		<h2><?php echo $fields['entity_id']->content; ?></h2>

		<div class="vejledere-content">
			<div class="vejledere-content-inner clearfix">
				<div class="vejledere-picture clearfix">
					<?php echo $fields['picture']->content; ?>
					<span class="caption"><?php echo $names[0]; ?></span>
					<span class="corner"> </span>
				</div>
				<ul>
					<li><strong>Fulde navn:</strong> <?php echo $fields['field_name']->content; ?></li>
					<?php if (!empty($fields['field_aka']->content)) { ?><li><strong>AKA:</strong> <?php echo $fields['field_aka']->content; ?></li><?php } ?>
					<?php if (!empty($fields['field_motto']->content)) { ?><li><strong>Citat:</strong> <?php echo $fields['field_motto']->content; ?></li><?php } ?>
					<?php if (!empty($fields['field_interesser']->content)) { ?><li><strong>Interesser:</strong> <?php echo $fields['field_interesser']->content; ?></li><?php } ?>
					<?php if (!empty($fields['field_study_year']->content)) { ?><li><strong>Årgang:</strong> <?php echo $fields['field_study_year']->content; ?></li><?php } ?>
					<?php if (!empty($fields['field_job_title']->content) || !empty($fields['field_job_title']->content)) { ?><li><strong>Studiejob:</strong>  <?php echo $fields['field_job_title']->content; if (!empty($fields['field_company']->content)) { ?> hos <?php echo $fields['field_company']->content; ?></li><?php } } ?>
					<?php if (!empty($fields['field_phone']->content)) { ?><li><strong>Telefon:</strong> <?php echo $fields['field_phone']->content; ?></li><?php } ?>
					<?php if (!empty($fields['mail']->content)) { ?><li><strong>Mail:</strong> <?php echo $fields['mail']->content; ?></li><?php } ?>
					<?php if (!empty($fields['field_facebook']->content)) { ?><li><strong>Facebook:</strong> <a href="<?php if (substr($fields['field_facebook']->content, 0, 4) != 'http') { echo 'http://'; } ?><?php echo $fields['field_facebook']->content; ?>" title="Besøg <?php echo $fields['field_name']->content; ?>'s facebook-profil"><?php echo $fields['field_facebook']->content; ?></a></li><?php } ?>
				</ul>
				<?php if (!empty($fields['field_beskrivelse']->content)) { ?>
				<div class="blaabog"><?php echo $fields['field_beskrivelse']->content; ?></div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php
// $Id: domain_views_handler_field_domain_id.inc,v 1.2 2010/09/30 15:32:17 agentken Exp $
/**
 * @file
 * Interface between domain_views.module and views.module.
 *
 * @ingroup domain_views
 */

/**
 * Field handler to provide simple renderer that allows linking to a domain.
 *
 * @ingroup domain_views
 */
class domain_views_handler_field_domain_name extends views_handler_field {
  /**
   * Constructor to provide additional field to add.
   */
  function construct() {
    parent::construct();
    $this->additional_fields['domain_id'] = 'domain_id';
  }

  function option_definition() {
    $options = parent::option_definition();
    $options['field'] = 'domain_id';
    $this->definition['real field'] = 'domain_id';
    $options['link_to_site'] = array('default' => TRUE);
    return $options;
  }

  /**
   * Provide link to default domain option
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['link_to_site'] = array(
      '#title' => t('Link this field to its domain'),
      '#description' => t('This will override any other link you have set.'),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['link_to_site']),
    );
  }


  function query() {
    parent::query();
    // The {domain_access} table uses GID instead of DOMAIN_ID.
    if (isset($this->query->fields['domain_access_gid'])) {
      $this->query->fields['domain_access_domain_id']['field'] = 'gid';
    }
  }

  function render($values) {
    $domain_id = $values->{$this->field_alias};
    $domain = domain_lookup($domain_id);
    if (empty($domain)) {
      return;
    }
    $path = $domain['path'];
    $text = $domain['sitename'];
    if (empty($this->options['link_to_site'])) {
    	return t($text);
    }
    else {
    	return l($text, $path);
    }
  }
}

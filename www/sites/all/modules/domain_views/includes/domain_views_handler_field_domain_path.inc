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
class domain_views_handler_field_domain_path extends views_handler_field {
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
    return $options;
  }

  /**
   * Provide link to default domain option
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
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
    return t(substr($path, 0, strlen($path) - 1));
  }
}

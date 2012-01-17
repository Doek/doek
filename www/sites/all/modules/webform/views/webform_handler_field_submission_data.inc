<?php

/**
 * @file
 * Views handler to display data value of a webform submission component.
 */

/**
 * Field handler to show if submission is draft or not.
 *
 * @ingroup views_field_handlers
 */
class webform_handler_field_submission_data extends views_handler_field {

  protected $component_instances;
  protected $component_type;


  /**
   * Field constructor to provide default data.
   */
  function construct() {
    parent::construct();
    $this->additional_fields['nid'] = 'nid';
    $this->additional_fields['cid'] = 'cid';
    $this->additional_fields['no'] = 'no';
  }

  /**
   * Definition and defaults for the option form/values.
   */
  function option_definition() {
    $options = parent::option_definition();
    $options['format'] = array('default' => 'html');
    $options['display_label'] = array('default' => FALSE);
    return $options;
  }


  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    // Provide the selection for the display format.
    $options['format'] = array(
      '#type' => 'select',
      '#title' => t('Display format'),
      '#options' => array(
        'html' => 'html',
        'plain' => 'plain',
        '#required' => TRUE,
      ),
      '#default_value' => $this->options['format'],
    );

    // Title display settings.
    $form['display_label'] = array(
      '#type' => 'checkbox',
      '#title' => t('Display label'),
      '#default_value' => $this->options['display_label'],
      '#description' => t('Show the label of the component as it is provied by webform.'),
    );
  }

  /**
   * Pre render the query, to get the
   */
  function pre_render($values) {
    $this->component_instances = webform_component_instances();
    // @TODO: maybe we add a multi value pre query here?
  }

  /**
   * Render the field.
   *
   * @param $row
   *   The row retrieved
   */
  function render($row) {
    $value = $this->get_value($row);
    if (isset($value)) {
      $nid = $this->get_value($row, 'nid');
      $cid = $this->get_value($row, 'cid');
      // @TODO: use a delta = 0 on every item?
      $delta = $this->get_value($row, 'no');
      $component = $this->component_instances["$nid-$cid"];

      // Remove display label.
      if (empty($this->options['display_label'])) {
        $component['name'] = '';
      }

      $format = $this->options['format'];
      $render = webform_component_invoke($component['type'], 'display', $component, array($delta => $value), $format);
      // @TODO: add a theme layer?
      return render($render);
    }
  }
}
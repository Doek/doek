<?php

/**
 * @file
 * Holds views_plugin_style_chart class which implements the chart plugin.
 */

/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function theme_views_view_chart() {
  // TODO: Should this theme views_view_chart be declared in hook_theme()?
  return 'opa';
}

class views_plugin_style_chart extends views_plugin_style {
  /**
   * Set default options.
   */
  function option_definition() {
    $options = parent::option_definition();

    $options['width'] = array('default' => '500');
    $options['height'] = array('default' => '600');
    $options['type'] = array('default' => array('bluff' => 'line'));
    $options['wmode'] = 'window';
    $options['showlegend'] = TRUE;
    $options['zoom'] = FALSE;

    return $options;
  }

  /**
   * Options form.
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    $curr_disp = $this->view->current_display;
    $views_fields = $this->_get_fields();

    $def_val_series = $this->options['views_charts_series_fields'];
    $def_val_series = (empty($def_val_series) || !is_array($def_val_series)) ?
      array(t('-- None --')) :
      $def_val_series;

    $form['views_charts_series_fields'] = array(
      '#title' => t('Fields to be used in Chart Series'),
      '#type' => 'select',
      '#options' => $views_fields,
      '#multiple' => TRUE,
      '#required' => TRUE,
      '#description' => t('These fields will be used as data fields for chart series. Fields must contain numeric data!'),
      '#default_value' => $def_val_series,
    );

    $views_fields = $this->_get_fields();
    $def_val_labels = $this->options['views_charts_x_labels'];
    $def_val_labels = (empty($def_val_labels)) ?
      array(t('-- None --')) :
      $def_val_labels;

    $form['views_charts_x_labels'] = array(
      '#title' => t('Fields to be used as X axis labels'),
      '#type' => 'select',
      '#options' => $views_fields,
      '#multiple' => FALSE,
      '#required' => TRUE,
      '#default_value' => $def_val_labels,
    );

    $form['width'] = array(
      '#type' => 'textfield',
      '#title' => t('Canvas width'),
      '#description' => t('Canvas width, for libraries that support it you can supply a percentage, otherwise, provide a number for pixels. Defaults to 600px when input is invalid.'),
      '#default_value' => $this->options['width'],
    );

    $form['height'] = array(
      '#type' => 'textfield',
      '#title' => t('Canvas height'),
      '#description' => t('Canvas height in pixels only.  Defaults to 500px when input is invalid.'),
      '#default_value' => $this->options['height'],
    );

    $engines = array();
    $types = array();
    $api_names = array();

    foreach ($apis = $this->charts_graphs_apis() as $api) {
      $engines[$api->name] = $api->nice_name;
      $types[$api->name] = $api->chart_types;
      $api_names[] = $api->name;
    }

    sort($api_names);

    $form['engine'] = array(
      '#type' => 'select',
      '#title' => t('Charting Engine'),
      '#options' => $engines,
      '#default_value' => $this->options['engine'],
    );

    $current_engine = empty($this->options['engine']) ?
      $api_names[0] :
      $this->options['engine'];

    foreach ($types as $engine => $type) {
      $default = !empty($this->options['type'][$engine]) ?
        $this->options['type'][$engine] :
        array_shift(array_keys($type)
      );
      $hidden = NULL;

      if ($engine != $current_engine) {
        $hidden = 'views_charts_chart_types_hidden';
      }

      $form['type'][$engine] = array(
        '#type' => 'radios',
        '#title' => t('Chart Type'),
        '#options' => $type,
        '#required' => TRUE,
        '#default_value' => $default,
        '#prefix' => sprintf(
          '<div class="views_charts_chart_types views_charts_%s_chart_types %s">',
          $engine,
          $hidden
        ),
        '#suffix' => '</div>',
      );
    }

    $form['y_min'] = array(
      '#type' => 'textfield',
      '#title' => t('Minimun value of Y Axis'),
      '#default_value' => ($this->options['y_min']) ? $this->options['y_min'] : '',
    );

    $form['y_max'] = array(
      '#type' => 'textfield',
      '#title' => t('Maximun value of Y Axis'),
      '#default_value' => ($this->options['y_max']) ? $this->options['y_max'] : '',
    );

    $form['y_step'] = array(
      '#type' => 'textfield',
      '#title' => t('Step value of Y Axis'),
      '#default_value' => ($this->options['y_step']) ? $this->options['y_step'] : '',
    );

    $form['y_legend'] = array(
      '#type' => 'textfield',
      '#title' => t('Y Legend'),
      '#default_value' => $this->options['y_legend'],
    );

    $form['background_colour'] = array(
      '#type' => 'textfield',
      '#title' => t('Graph background colour'),
      '#description' => t('Define the colour in hexadecimal: #RRGGBB'),
      '#default_value' => $this->options['background_colour'],
    );

    $form['series_colours'] = array(
      '#type' => 'textfield',
      '#title' => t('Series colours'),
      '#description' => t('Define the colour of each series as a comma separated list of hexadecimal colour definitions. Ex: #123456,#654321,#1177ff'),
      '#default_value' => $this->options['series_colours'],
    );

    $form['showlegend'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show Legend'),
      '#description' => t('Show or hide the legend, if the library supports it.'),
      '#default_value' => $this->options['showlegend'],
    );

    $form['zoom'] = array(
      '#type' => 'checkbox',
      '#title' => t('Zoom'),
      '#description' => t('Flot only! Adds a second smaller version of the graps that allows you to zoom in and out.'),
      '#default_value' => $this->options['zoom'],
    );

    $form['wmode'] = array(
      '#type' => 'select',
      '#title' => t('Window Mode (for flash charts only)'),
      '#options' => $this->charts_graphs_get_wmode_values(),
      '#default_value' => $this->options['wmode'],
    );

  }

  /**
   * Render the display in this style.
   */
  function render() {
    // Group the rows according to the grouping field, if specified.
    $sets = $this->render_grouping($this->view->result, $this->options['grouping']);

    $output = '';
    foreach ($sets as $title => $rows) {
      $output .= $this->_render($rows, $title);
    }
    unset($this->view->row_index);
    return $output;
  }

  function _render($rows, $title) {
    $canvas = $this->charts_graphs_get_graph($this->options['engine']);

    $cgp_data = $this->_transform_data($rows);
    $canvas->set_data($cgp_data->rows, $cgp_data->x_labels);
    $canvas->x_type = $cgp_data->x_type;

    $curr_disp = $this->view->current_display;
    $canvas->title = $this->view->display[$curr_disp]->handler->get_option('title');
    $canvas->type = $this->options['type'][$this->options['engine']];
    $canvas->y_legend = $this->options['y_legend'];
    if (isset($this->options['y_min']) && !empty($this->options['y_min'])) {
      $canvas->y_min = $this->options['y_min'];
    }
    if (isset($this->options['y_max']) && !empty($this->options['y_max'])) {
      $canvas->y_max = $this->options['y_max'];
    }
    if (isset($this->options['y_step']) && !empty($this->options['y_step'])) {
      $canvas->y_step = $this->options['y_step'];
    }
    if (isset($this->options['background_colour'])) {
      $background_colour = trim($this->options['background_colour']);
      if (!empty($background_colour)) {
        $canvas->colour = $background_colour;
      }
    }
    if (isset($this->options['series_colours'])) {
      $series_colours = explode(',', $this->options['series_colours']);
      if (count($series_colours)) {
        $canvas->series_colours = $series_colours;
      }
    }
    // make the user input a bit more resilient;
    $width = str_replace("px", "", $this->options['width']); // just for people who are confused and still added this
    $width = ((is_numeric($width)) || (is_numeric(str_replace("%", "", $width)))) ? $width : "600";

    $height = str_replace("px", "", $this->options['height']);
    $height = (is_numeric($height)) ? $height : "500";

    $canvas->width = $width;
    $canvas->height = $height;
    $canvas->wmode = $this->options['wmode'];
    $canvas->showlegend = $this->options['showlegend'];
    $canvas->zoom = $this->options['zoom']; // Flot only



    //'admin/structure/views' - issues with javascript etc. Do not try to render
    if (arg(0) == 'admin' && arg(1) == 'build' && arg(2) == 'views') {
      $msg = t("Preview not available for Charts display style. Please view on a full page");
      $msg = "<div class=\"messages error\"><b>$msg</b></div>";
      return $msg;
    }

    views_charts_invoke_all('views_charts_graph_alter', array(&$canvas));

    $element = $canvas->get_chart();
    return render($element);
  }

  /**
   * Transform data from Views-centric representation into standard Charts and
   * Graphs input format.
   *
   * @return <stdClass>
   */
  function _transform_data($db_rows) {
    $series_column_names = $this->options['views_charts_series_fields'];
    $x_label_column = $this->options['views_charts_x_labels'];

    $views_fields = $this->view->field;

    $fields_x_names = array();
    $series_full_names = $this->_get_fields(TRUE);
    $x_label_alias_found = FALSE;
    $aliases = array();

    foreach ($views_fields as $idx => $f) {
      $db_alias = $idx;//$f->real_field;
      if (in_array($idx, $this->options['views_charts_series_fields'])) {
        $fields_x_names[$idx] = $series_full_names[$idx];
      }

      $aliases[$idx] = $idx;

      if ((trim($x_label_column) == trim($idx)) && ($x_label_alias_found == FALSE)) {
        $x_label_column = $idx;
        $x_isdate = (((get_class($views_fields[$idx]) == 'views_handler_field_date') &&
                      ($views_fields[$idx]->options['date_format'] == 'custom') &&
                      ($views_fields[$idx]->options['custom_date_format'] == 'U')) ||
                     ((get_class($views_fields[$idx]) == 'views_handler_field_field') &&
                      ($views_fields[$idx]->field_info['type'] == 'date') &&
                      (date_format_type_format($views_fields[$idx]->options['settings']['format_type']) == 'U')));
        $x_isnum = ((isset($views_fields[$idx]->field_info)) && ($views_fields[$idx]->field_info['module'] == 'number'));

        $x_label_alias_found = TRUE;
      }
    }

    // We need to re-map the db results array so that labels are indexes.
    $rows = array();
    $labels = array();
    foreach ($db_rows as $rowid => $row) {
      $cols = (array) $row;
      if (!$x_isnum) {
        // This works for all Drupal core fields
        if ((get_class($views_fields[$aliases[$x_label_column]]) == 'views_handler_field_date') || (isset($this->view->result[$rowid]->_field_data[$x_label_column]['entity']))) {
          $labels[] = $views_fields[$aliases[$x_label_column]]->render($this->view->result[$rowid]);
        }
        // This is for date fields
        elseif ((get_class($views_fields[$aliases[$x_label_column]])== 'views_handler_field_field') &&
        $views_fields[$aliases[$x_label_column]]->field_info['type'] == 'date') {
          $fieldname = "field_" .$x_label_column;
          $field = $this->view->result[$rowid]->$fieldname;
          $labels[] = strip_tags($field[0]['rendered']['#markup']);
        }
        // This is when we have no clue
        else {
          $labels[] = 'unknown';
        }
      }
      else {
        $labels[] = $views_fields[$aliases[$x_label_column]]->original_value; // no rendering for numbers
      }
      foreach ($fields_x_names as $key => $label) {
        $rows[$label][] = (float) $this->view->field[$key]->theme($row);
      }
    }

    $ret = new stdClass();
    $ret->rows = $rows;
    $ret->x_labels = $labels;
    if ($x_isdate) {
      $ret->x_type = 'date';
    }
    elseif ($x_isnum) {
      $ret->x_type = 'number';
    }
    else {
      $ret->x_type = 'text';
    }

    return $ret;
  }

  /**
   *
   * @param <type> $return_pretty_name Return only the label, so we can use it
   * on chart.
   * @return <type>
   */
  function _get_fields($return_pretty_name = FALSE) {
    $handlers = $this->display->handler->get_handlers('field');

    $avail_fields = array();
    if (is_array($handlers)) {
      foreach ($handlers as $field => $handler) {
        $field_alias = $handler->options['table'] . '_' . $handler->options['field'];
        $relationship = '';
        $all_relationships = $this->_get_relationships();
        $rel = $handler->options['relationship'];
        $rel = isset($all_relationships[$rel]) ?
          $all_relationships[$rel] :
          NULL;

        if (!empty($rel)) {
//          $field_name = $rel->fieldprefix . '.' . $val->options['field']; // reserved, not used
//          $field_alias = $rel->fieldprefix . '_' . $val->options['field'];
          $relationship = (empty($rel)) ? '' : '[' . $rel->label . '] ';
        }

        $label = ($handler->label()) ? $handler->label() : $handler->ui_name();

        $field_name = $relationship . $handler->definition['group'] . ': ' .
          $handler->definition['title'] . ' (' . $label . ')';

        $avail_fields[$field] = $return_pretty_name ? $label : $field_name;
      }
    }

    return $avail_fields;
  }

  function _get_relationships() {
    $default_rels = array();
    $curr_disp_rels = array();

    $curr_disp = $this->view->current_display;

    $default_rels = $this->view->display['default']->handler->options['relationships'];
    $curr_displ_rels = isset($this->view->display[$curr_disp]->handler->options['relationships']) ?
      $this->view->display[$curr_disp]->handler->options['relationships'] :
      NULL;

    $default_rels = (is_array($default_rels)) ? $default_rels : array();
    $curr_displ_rels = (is_array($curr_displ_rels)) ? $curr_displ_rels : array();

    $relationships = array_merge($default_rels, $curr_displ_rels);

    $all_rels = array();
    $base_table = $this->view->base_table;

    if (is_array($relationships)) {
      foreach ($relationships as $key => $val) {
        $obj = new stdClass();
        $obj->fieldprefix = $base_table . '_' . $val['table'];
        $obj->label = $val['label'];
        $obj->table = $val['table'];
        $obj->field = $val['field'];
        $all_rels[$key] = $obj;
      }
    }

    return $all_rels;
  }

  function charts_graphs_apis($library = NULL) {
    $function = function_exists('chart_graphs_apis') ?
      'chart_graphs_apis' :
      'charts_graphs_apis';
    if ($library === NULL) {
      return call_user_func($function);
    }
    else {
      return call_user_func($function, $library);
    }
  }

  function charts_graphs_get_graph($library) {
    $function = function_exists('chart_graphs_get_graph') ?
      'chart_graphs_get_graph' :
      'charts_graphs_get_graph';
    return call_user_func($function, $library);
  }

  function charts_graphs_get_wmode_values() {
    if (class_exists('ChartCanvas')) {
      $class_name = 'ChartCanvas';
    }
    else {
      $class_name = 'ChartsGraphsFlashCanvas';
      require_once DRUPAL_ROOT . '/' . drupal_get_path('module', 'charts_graphs') .
        '/charts_graphs_flash_canvas.class.inc';
    }
    return call_user_func(array($class_name, 'wmode_values'));
  }

}

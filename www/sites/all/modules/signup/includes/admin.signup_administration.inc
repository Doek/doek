<?php
// $Id: admin.signup_administration.inc,v 1.1.2.3 2010/12/28 19:59:57 ezrag Exp $


/**
 * @file
 * Code related to the Signup administration page (admin/content/signup).
 */

/**
 * Print the admin signup overview page located at admin/content/signup.
 */
function signup_admin_page() {
  $filter_status_form = drupal_get_form('signup_filter_status_form');
  $signup_admin_form = drupal_get_form('signup_admin_form');
  return array(
    '#theme' => 'signup_admin_page',
    '#attached' => array(
      'css' => array(drupal_get_path('module', 'signup') . '/signup.css'),
    ),
    '#filter_status_form' => $filter_status_form,
    '#signup_admin_form' => $signup_admin_form,
  );
}

/**
 * Form builder for the signup status filter on the signup administration page.
 */
function signup_filter_status_form($form, &$form_state) {
  $options = array(
    'all' => t('All'),
    'open' => t('Open'),
    'closed' => t('Closed'),
  );
  if (empty($_SESSION['signup_status_filter'])) {
    $_SESSION['signup_status_filter'] = 'all';
  }
  $form['filter'] = array(
    '#type' => 'select',
    '#title' => t('Filter by signup status'),
    '#options' => $options,
    '#default_value' => $_SESSION['signup_status_filter'],
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Filter'),
  );
  //  $form_state['#redirect'] = FALSE;
  return $form;
}

/**
 * Submit handler for the status filter on the signup administration page.
 */
function signup_filter_status_form_submit($form, &$form_state) {
  $_SESSION['signup_status_filter'] = $form_state['values']['filter'];
}

/**
 * Form builder for the main form on the signup administration page.
 */
function signup_admin_form($form, &$form_state) {
  // Figure out if the current user has permission to use signup broadcast.
  $access_broadcast = user_access('email all signed up users');

  $header = array(
    'title' => array(
      'data' => t('Title'),
      'field' => 'n.title',
      'sort' => 'asc',
    ),
    'total' => array(
      'data' => t('Signups'),
      'field' => 'signup_total',
    ),
    'limit' => array(
      'data' => t('Limit'),
      'field' => 'signup_close_signup_limit',
    ),
    'status' => array(
      'data' => t('Status'),
      'field' => 'signup_status',
    ),
    'operations' => array('data' => t('Operations')),
  );

  $start_column = signup_admin_form_header();
  if (!empty($start_column)) {
    array_unshift($header, $start_column);
  }

  list($sql, $sql_count) = signup_admin_form_sql();

  $result = db_query_range($sql, 0, 25);

  // Loop through the signup nodes, and generate our form elements
  $rows = array();
  foreach ($result as $signup_node) {
    $row = array();
    if (!empty($start_column)) {
      $row['start'] = signup_admin_form_extra($signup_node);
    }
    // Instead of duplicating the logic from the node/N/signups admin
    // form, we just call that form builder here and lift the elements
    // we need directly from that.
    module_load_include('inc', 'signup', 'includes/node_admin_summary');
    $new_form_state = array();
    $node_admin_form = signup_node_admin_summary_form(array(), $new_form_state, $signup_node);
    $row['title'] = array(
      'data' => array(
        '#type' => 'link',
        '#title' => $signup_node->title,
        '#href' => "node/$signup_node->nid",
      ),
    );
    $row['status']['data'] = $node_admin_form['status'];
    unset($row['status']['data']['#title']);
    $row['total'] = $signup_node->signup_total;
    $row['limit']['data'] = $node_admin_form['limit'];
    unset($row['limit']['data']['#title']);
    $op_links = l(t('View signups'), "node/$signup_node->nid/signups");
    if ($access_broadcast) {
      $op_links .= '<br />';
      $options['attributes']['title'] = t('Send an email message to all users who signed up.');
      $op_links .= l(t('Signup broadcast'), "node/$signup_node->nid/signups/broadcast", $options);
    }
    $row['operations'] = $op_links;
    $rows[$signup_node->nid] = $row;
  }
  $form['#tree'] = TRUE;
  $form['nids'] = array(
    '#type' => 'tableselect',
    '#header' => $header,
    '#options' => $rows,
    '#empty' => t('No signups available.'),
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Update'),
  );
  $form['pager'] = array('#markup' => theme('pager'));
  return $form;
}

/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function signup_admin_form_header() {
  if (module_exists('date')) {
    // If we're using CCK date, we can't sort since the date field used for
    // each content type can come from different tables.
    return array('data' => t('Start'), 'field' => NULL);
  }
  elseif (module_exists('event')) {
    // If we've got event, but not date, we can sort by e.event_start.
    return array('data' => t('Start'), 'field' => 'e.event_start');
  }
  // If we've got no scheduling backend at all, there's no start time column.
  return array();
}

/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function signup_admin_form_extra($signup_node) {
  return array(
    '#markup' => signup_format_date($signup_node),
  );
}

/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function signup_admin_form_sql() {
  $admin_common_sql = array(
    'primary' => '{node} n',
    'fields' => array(
      'n.nid',
      'n.title',
      'n.type',
      's.status AS signup_status',
      'COUNT(s_l.nid) AS signup_total',
      's.close_signup_limit AS signup_close_signup_limit',
    ),
    'group_by' => array(
      'n.nid',
      'n.title',
      'n.type',
      'signup_status',
      'signup_close_signup_limit',
    ),
    'joins' => array(
      'INNER JOIN {signup} s ON s.nid = n.nid',
      'LEFT JOIN {signup_log} s_l ON s.nid = s_l.nid',
    ),
  );

  $type = $_SESSION['signup_status_filter'];
  if ($type == 'open') {
    $filter_status = 1;
  }
  elseif ($type == 'closed') {
    $filter_status = 0;
  }
  if (isset($filter_status)) {
    $admin_common_sql['where'] = array("s.status = $filter_status");
  }

  // Get the right query elements from the currently installed backend
  $admin_sql = array();
  foreach (signup_content_types() as $type) {
    $admin_sql = array_merge_recursive($admin_sql, signup_admin_sql($type));
  }

  // Build the main query.
  $sql = _signup_build_query($admin_common_sql, $admin_sql);

  // Construct the proper pager query using just the WHERE clauses (if any).
  $all_fragments = array_merge_recursive($admin_common_sql, $admin_sql);
  $sql_count = "SELECT COUNT(s.nid) FROM {signup} s";
  if (!empty($all_fragments['where'])) {
    $sql_count .= ' WHERE ' . implode(' AND ', $all_fragments['where']);
  }

  // TODO: This should be a dynamic query tagged 'node_access'.
  return array($sql, $sql_count);
  // return array(db_rewrite_sql($sql), db_rewrite_sql($sql_count, 's'));
}

/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function signup_admin_form_submit($form, &$form_state) {
  module_load_include('inc', 'signup', 'includes/node_admin_summary');
  foreach ($form_state['values']['nids'] as $nid => $values) {
    $values['nid'] = $nid;
    $temp_state['values'] = $values;
    signup_node_admin_summary_form_submit($form, $temp_state);
  }
}


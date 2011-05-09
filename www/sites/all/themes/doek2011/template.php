<?php
function doek2011_menu_tree($variables)
{
  return '<ul class="megamenu">' . $variables['tree'] . '</ul>';
}

function doek2011_menu_link(array $variables)
{
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
 if (($element['#href'] == $_GET['q'] || ($element['#href'] == '<front>' && drupal_is_front_page()))) {
  	$element['#attributes']['class'][] = 'active';
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
?>

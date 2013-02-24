<?php

$media_total = theme_get_setting('media_queries');
if (theme_get_setting('responsive_enable')) {
  $media_total = theme_get_setting('media_queries');
}
else {
  $media_total = 1;
}

// Loop over all the media queries
for ($i = 1; $i <= $media_total; $i++) {
  if ($media_total > 1) {
    $CSS .= theme_get_setting("layout_query{$i}") . " {\n";
  }

  $CSS .= "  .mast { width: " . theme_get_setting("layout_width{$i}") . "; }\n";
  if (strpos(theme_get_setting("layout_width{$i}"), '%')) {
    $CSS .= "  .mast { max-width: " . theme_get_setting("layout_max_width{$i}") . "; }\n";
  }
  $CSS .= "  .grid-inner { margin: " . theme_get_setting("gutter_vertical{$i}") . " " . theme_get_setting("gutter_horizontal{$i}") . "; }\n";

  $first = theme_get_setting("sidebar_first_width{$i}");
  $second = theme_get_setting("sidebar_second_width{$i}");
  switch (theme_get_setting("layout_type{$i}")) {
    case 1:
      $CSS .= "  .two-sidebars .main {width:" . (100-$first-$second) . "%; margin-left:" . $first . "%; margin-right:" . $second . "%; } \n";
      $CSS .= "  .sidebar-first .main {width:" . (100-$first) . "%; margin-left:" . $first . "%; margin-right:0; } \n";
      $CSS .= "  .sidebar-second .main {width:" . (100-$second) . "%; margin-right:" . $second . "%; margin-left:0; } \n";
      $CSS .= "  .region-sidebar-first {width: " . $first . "%; margin-left: -100%; margin-right:0; } \n";
      $CSS .= "  .region-sidebar-second {width: " . $second . "%; margin-left: -" . $second . "%; } \n";
      break;
    case 2:
      $CSS .= "  .two-sidebars .main {width:" . (100-$first-$second) . "%; margin-right: " . ($first+$second) . "%; margin-left: 0;} \n";
      $CSS .= "  .sidebar-first .main {width:" . (100-$first) . "%; margin-right: " . $first . "%; margin-left: 0;} \n";
      $CSS .= "  .sidebar-second .main {width:" . (100-$second) . "%; margin-right: " . $second . "%; margin-left: 0;} \n";
      $CSS .= "  .region-sidebar-first {width: " . $first . "%; margin-left: -" . ($first+$second) . "%;} \n";
      $CSS .= "  .region-sidebar-second {width: " . $second . "%; margin-left: -" . $second . "%; clear: none;} \n";
      $CSS .= "  .sidebar-first .region-sidebar-first {width: " . $first . "%; margin-left: -" . $first . "%;} \n";
      break;
    case 3:
      $CSS .= "  .two-sidebars .main {width:" . (100-$first-$second) . "%; margin-left: " . ($first+$second) . "%; margin-right: 0;} \n";
      $CSS .= "  .sidebar-first .main {width:" . (100-$first) . "%; margin-left: " . $first . "%; margin-right: 0;} \n";
      $CSS .= "  .sidebar-second .main {width:" . (100-$second) . "%; margin-left: " . $second . "%; margin-right: 0;} \n";
      $CSS .= "  .region-sidebar-first {width: " . $first . "%; margin-left: -100%;} \n";
      $CSS .= "  .region-sidebar-second {width: " . $second . "%; margin-left: -100%; clear: none;} \n";
      $CSS .= "  .two-sidebars .region-sidebar-second {width: " . $second . "%; position: relative; left: " . $first . "%; } \n";
      break;
    case 4:
      $CSS .= "  .mast .main, .mast .region-sidebar-first, .mast .region-sidebar-second {width: 100%; float:none; margin-left: 0; margin-right: 0; left:0; right:0; clear:both; } \n";
      break;
    case 5:
      $CSS .= "  .two-sidebars .region-sidebar-second {width: 100%; float:none; margin-left: 0; margin-right: 0; left:0; right:0; clear:both; } \n";
      $CSS .= "  .sidebar-first .main, .two-sidebar .main {width:" . (100-$first) . "%; margin-right: " . $first . "%; margin-left: 0;} \n";
      $CSS .= "  .sidebar-second .main {width:" . (100-$second) . "%; margin-right: " . $second . "%; margin-left: 0;} \n";
      $CSS .= "  .region-sidebar-first {width: " . $first . "%; margin-left: -" . ($first+$second) . "%;} \n";
      $CSS .= "  .region-sidebar-second {width: " . $second . "%; margin-left: -" . $second . "%; clear: none;} \n";
      $CSS .= "  .sidebar-first .region-sidebar-first {width: " . $first . "%; margin-left: -" . $first . "%;} \n";
      break;
  }

  /**
   * These are media query specific grid styles. This allows you to target devices or other contexts with skinr.
   * In other words it makes the skinr LayoutKit grid skins @media-context aware.
   */

  $CSS .= <<<EOT

  /**
   * Grid Widths
   */

  .media-{$i}-grid-100 {width:100%; float:left;}
  .media-{$i}-grid-3-4 {width:75%; float:left;}
  .media-{$i}-grid-2-3 {width:66.65%; float:left;}
  .media-{$i}-grid-1-2 {width:50%; float:left;}
  .media-{$i}-grid-1-3 {width:33.32%; float:left;}
  .media-{$i}-grid-1-4 {width:25%; float:left;}
  .media-{$i}-grid-1-5 {width:20%; float:left;}
  .media-{$i}-grid-1-6 {width:16.65%; float:left;}
  .media-{$i}-grid-1-8 {width:12.5%; float:left;}
  .media-{$i}-grid-phi {width:61.8%; float:left;}
  .media-{$i}-grid-phi2 {width:38.2%; float:left;}
  .media-{$i}-grid-phi3 {width:23.6%; float:left;}

  /**
   * Clear Styles
   */

  .media-{$i}-clear-left {clear:left;}
  .media-{$i}-clear-right {clear:right;}
  .media-{$i}-clear-none {clear:none;}

  /**
   * Prefill Styles
   */

  .media-{$i}-grid-prefill-3-4 {padding-left:75%; float:left;}
  .media-{$i}-grid-prefill-2-3 {padding-left:66.65%; float:left;}
  .media-{$i}-grid-prefill-1-2 {padding-left:50%; float:left;}
  .media-{$i}-grid-prefill-1-3 {padding-left:33.32%; float:left;}
  .media-{$i}-grid-prefill-1-4 {padding-left:25%; float:left;}
  .media-{$i}-grid-prefill-1-5 {padding-left:20%; float:left;}
  .media-{$i}-grid-prefill-1-6 {padding-left:16.65%; float:left;}
  .media-{$i}-grid-prefill-1-8 {padding-left:12.5%; float:left;}
  .media-{$i}-grid-prefill-phi {padding-left:61.8%; float:left;}
  .media-{$i}-grid-prefill-phi2 {padding-left:38.2%; float:left;}
  .media-{$i}-grid-prefill-phi3 {padding-left:23.6%; float:left;}

  /**
   * Postfill Styles
   */

  .media-{$i}-grid-postfill-3-4 {padding-right:75%; float:left;}
  .media-{$i}-grid-postfill-2-3 {padding-right:66.65%; float:left;}
  .media-{$i}-grid-postfill-1-2 {padding-right:50%; float:left;}
  .media-{$i}-grid-postfill-1-3 {padding-right:33.32%; float:left;}
  .media-{$i}-grid-postfill-1-4 {padding-right:25%; float:left;}
  .media-{$i}-grid-postfill-1-5 {padding-right:20%; float:left;}
  .media-{$i}-grid-postfill-1-6 {padding-right:16.65%; float:left;}
  .media-{$i}-grid-postfill-1-8 {padding-right:12.5%; float:left;}
  .media-{$i}-grid-postfill-phi {padding-right:61.8%; float:left;}
  .media-{$i}-grid-postfill-phi2 {padding-right:38.2%; float:left;}
  .media-{$i}-grid-postfill-phi3 {padding-right:23.6%; float:left;}

EOT;

  if ($media_total > 1) {
    $CSS .= "}\n";
  }
}

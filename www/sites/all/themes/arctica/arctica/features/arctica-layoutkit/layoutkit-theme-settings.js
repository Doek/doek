$ = jQuery.noConflict(); // Make sure jQuery owns $ here
/*
 * Reduce complexity and clutter of form
 */
$(function() {
  arcticaLayoutkitForm();


  $('#edit-responsive-enable, #edit-layout .form-radios').click( function() {
    arcticaLayoutkitForm();
  });

  /**
   * Use formUpdated event from Drupal core form.js
   */
  $('input[id^=edit-layout-width]').bind('formUpdated', function() {
    arcticaLayoutkitForm();
  });
});



function arcticaLayoutkitForm() {
  // Limit/expand sidebar positions options
  $('#edit-layout .form-radios').each( function() {
    if ($(this).find('input.form-radio[value=4]').attr('checked')) {
      $(this).parent().parent().find('div[class^="form-item form-type-select form-item-sidebar"]').hide();
    }
    if ($(this).find('input.form-radio[value=5]').attr('checked')) {
      $(this).parent().parent().find('div[class^="form-item form-type-select form-item-sidebar-sec"]').hide();
    }
  });

  // Hide/show responsive stuff
  if ($('#edit-responsive-enable').attr('checked')) {
    $("#edit-layout.ui-tabs .ui-tabs-nav li:not(:first-child), .form-item-layout-query1, .form-item-meta, .form-item-hide-address-bar, .form-item-ori-scale, .form-item-media-queries").fadeIn();
  } else {
    $("#edit-layout.ui-tabs .ui-tabs-nav li:not(:first-child), .form-item-layout-query1, .form-item-meta, .form-item-hide-address-bar, .form-item-ori-scale, .form-item-media-queries").fadeOut();
  }

  // Toggle sidebar width settings visibility
  var $layoutPositions = $('#edit-layout .form-radios');
  if ($layoutPositions.find('input.form-radio[value=4]').attr('checked')) {
    $layoutPositions.parent().parent().find('div[class^="form-item form-type-select form-item-sidebar"]').fadeOut();
  }
  if ($layoutPositions.find('input.form-radio[value=5]').attr('checked')) {
    $layoutPositions.parent().parent().find('div[class^="form-item form-type-select form-item-sidebar-sec"]').fadeOut();
    $layoutPositions.parent().parent().find('div[class^="form-item form-type-select form-item-sidebar-fir"]').fadeIn();
  }
  if ($layoutPositions.find('input.form-radio[value|=1]').attr('checked')) {
    $layoutPositions.parent().parent().find('div[class^="form-item form-type-select form-item-sidebar"]').fadeIn();
  }
  if ($layoutPositions.find('input.form-radio[value|=2]').attr('checked')) {
    $layoutPositions.parent().parent().find('div[class^="form-item form-type-select form-item-sidebar"]').fadeIn();
  }
  if ($layoutPositions.find('input.form-radio[value|=3]').attr('checked')) {
    $layoutPositions.parent().parent().find('div[class^="form-item form-type-select form-item-sidebar"]').fadeIn();
  }

  // Toggle max-width setting visibility
  var width = $('input[id^=edit-layout-width]').val();
  if (/^[0-9]+%$/.test(width)) {
    $('input[id^=edit-layout-width]').parent().parent().find('div[class^="form-item form-type-textfield form-item-layout-max-width"]').fadeIn();
  } else {
    $('input[id^=edit-layout-width]').parent().parent().find('div[class^="form-item form-type-textfield form-item-layout-max-width"]').fadeOut();
  }
}

<?php


class CommerceOptionEntityController extends DrupalDefaultEntityController {
  public function create($set_id = '') {
    return (object) array(
      'option_id' => '',
      'is_new' => TRUE,
      'set_id' => $set_id,
      'line_item_id' => '',
      'status' => 1,
      'created' => '',
      'changed' => '',
    );
  }

}

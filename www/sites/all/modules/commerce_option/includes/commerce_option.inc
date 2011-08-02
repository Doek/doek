<?php

class CommerceOption extends Entity {

  public $option_id;
  public $set_id;
  public $line_item_id;
  public $created;
  public $changed;
  
  public function __construct($values = array()) {
    parent::__construct($values, 'commerce_option');
  }
  
}
  
   

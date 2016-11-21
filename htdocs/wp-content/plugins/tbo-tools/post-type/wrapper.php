<?php

class TBO_Wrapper {
  
  protected $entity;
  
  protected $data = [];
  
  public function __construct($entity) {
    $this->entity = $entity;
  }
  
  // TODO: have filters to grab the field data
  public function get($field) {
    if(!isset($this->data[$field])) {
      
      if($field == 'title') {
        $this->data[$field] = get_the_title($this->getID());
      } else if($filed == 'ID') {
        $this->data[$field] = $this->getID();
      } else {
        $this->data[$field] = get_field($field, $this->entity->ID);
        // TODO: pull from default wordpress when no get_field plugin
      }
      
    }
    return $this->data[$field];
  }
  
  public function getID() {
    return $this->entity ? $this->entity->ID : 0;
  }
  
  public function getTaxonomy($taxonomy, $format='list') {
    
    $output = [];
    $terms = wp_get_object_terms($this->getID(), $taxonomy);
    if(!empty($terms)) {
      foreach($terms as $term) {
        $output[] = $term->slug;
      }
    }
    
    return join(', ', $output);
    
  }
  
  // set
  
  public function set($field, $value) {
    
    $this->data[$field] = $value;
    
    return $this;
    
  }
  
  // find by
  
  public static function findById($ID) {
    return new self(get_post($ID));
  }
  
  // ORM
  
  public function save() {
    
    foreach($this->data as $field => $value) {
      
      update_field($field, $value, $this->getID());
      
    }
    
  }
  
  // output
  
  public function format($format='default') {
    
    return [
      'ID' => $this->getID(),
    ];
    
  }
  
}
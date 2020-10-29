<?php

class Option implements Options{

  private $db;

  public function __construct(){

    $this->db = new Database;

  }

}
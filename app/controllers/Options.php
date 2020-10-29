<?php

class Options extends Controller {

  public function __construct(){

    $this->userModel = $this->model('Option');

  }

}
<?php

class Core {

  protected $currentController = 'Users';
  protected $currentMethod = 'index';
  protected $params = [];

  public function __construct(){

    $url = $this->getUrl();

    if(!empty($url) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){

      $this->currentController = ucwords($url[0]);

      unset($url[0]);

    }

    // Require the controller and initiate it
    require_once '../app/controllers/' . $this->currentController . '.php';
    $this->currentController = new $this->currentController;

    //Check the second part of url
    if(isset($url[1])){

      //check to see if method exists in controller
      if(method_exists($this->currentController, $url[1])){

        $this->currentMethod = $url[1];

        unset($url[1]);

      }

    }

    // Get params
    $this->params = $url ? array_values($url) : [];

    // Call a callback with array of params
    call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

  }

  public function getUrl(){

    if(isset($_GET['url'])){

      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);

      return $url;

    }

  }

}
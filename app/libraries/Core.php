<?php
/* App Core class 
Creates url and loads core controller
URL FORMAT - /controller/method/params */

class Core{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        //print_r($this->getURL());
        $url = $this->getURL();

        //Look in controllers for first value
        if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            //if exists, set as controller
            $this->currentController = ucwords($url[0]);
            //unset 0 index
            unset($url[0]);
            
        }

        //Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        //instantiate controller class
        $this->currentController = new $this->currentController;

        //check for second part of url
        if(isset($url[1])){
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        //get parameters
        $this->params = $url ? array_values($url) : [];

        //call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

        // error_reporting(E_ALL ^ E_NOTICE);
    }

    public function getURL(){
         if (isset($_GET['url'])) {
             $url = rtrim($_GET['url'], '/');
             $url = filter_var($url, FILTER_SANITIZE_URL);
             $url = explode('/', $url);

             return $url;
         }
    }

    
}
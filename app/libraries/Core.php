<?php
   /*
    * App core Class
    * create url & load core controller
    * url format -/controller/method/params
    */
   class Core {
   protected $currentController='Pages';
   protected $currentMethod='index';
   protected $params=[];

   public function  __construct(){
      // print_r($this->getUrl());
       $url=$this->getUrl();

       // look in controllers folder for a file which match the first value of our array
       // path from index php because everything is rendering throught it
       //ucwords capitalize first letter to match class name
       if(isset($url)) {
           if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
               $this->currentController = ucwords($url[0]);
               unset($url[0]);
           }
       }
        // require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';
        //instantiate
       $this->currentController=new $this->currentController;
       //check if a method passed in the url
       if (isset($url[1])){
           //check if method actually exist in the controller
        if(method_exists($this->currentController,$url[1])){
            $this->currentMethod= $url[1];
            unset($url[1]);
        }
       }
       //get params
       $this->params=$url ? array_values($url) :[];

       call_user_func_array([$this->currentController,$this->currentMethod],$this->params);



   }

      // take the url and return it as array
       public function getUrl(){
       if(isset($_GET['url'])){
           $url=rtrim($_GET['url'],'/');
           $url=filter_var($url,FILTER_SANITIZE_URL);
           $url=explode('/',$url);
           return $url;
       }
   }

   }
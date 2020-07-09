<?
    class App {
        protected $controller = 'Layout';
        protected $action = 'index';
        protected $params = [];

        function __construct() {
            $arr_url = $this->getUrl();
            
            // Handle controller
            if( isset($arr_url[0]) ) {
                if( file_exists('./mvc/controllers/'.$arr_url[0].'.php') ){
                   $this->controller = $arr_url[0];
                   unset($arr_url[0]);
                }   
            }
            require_once './mvc/controllers/'.$this->controller.'.php';
            $this->controller = new $this->controller;
            
            // Handle action(function)
            if( isset($arr_url[1]) ) {
                if( method_exists($this->controller, $arr_url[1]) ) {
                    $this->action = $arr_url[1];
                    unset($arr_url[1]);
                }
            }

            // Handle params
            if( isset($arr_url) ) {
                $this->params = array_values($arr_url);
            }

            call_user_func_array([$this->controller, $this->action], $this->params);
        }

        function getUrl() {
            if(isset( $_GET['url'] )) {
                return explode('/', trim( $_GET['url'] ));
            }
        }
    }
?>
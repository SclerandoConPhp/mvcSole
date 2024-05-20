<?php

    class dispatcher {
        protected $params;
        protected $controller;

        public function __construct() {
            $this -> params = $this->parse($_SERVER['REQUEST_URI']);
            $this -> controller = $this -> chooseController();
        }
        public function handle() {
            $action = $this->params['method'];
            $args = $this->params['args'];

            if(method_exists($this->controller, $action)){ //controlla l'esistenza dell'azione se esiste elabora altrimenti manda all'index
                if(!is_null($args)) {
                    $this->controller->$action($args);
                } else {
                    $this->controller->$action();
                }
            } else {
                header('Location: /'.$this -> params['controller'].'/index');//manda all'index modificando l'header
            }

        }
        protected function parse($path) {
            /*
            se controller esiste vai ed elabora se no redirect a museo
            se metodo esiste vai ed elabora se no redirect a index
            */ 
            $parts = explode('/', $this->removeQueryStringVariables($path));
            $str_controller = $parts[1];
            $method = $parts[2];
            $args = array_slice($parts, 3);


            if(file_exists('app/controller/'.$str_controller.'.php')){ //controlla se esiste controller
                return [
                    'controller' => $str_controller,
                    'method' => $method,
                    'args' => (count($args) > 0 ) ? $args : null
                ];
            } else {//se controller non esiste manda alla homepage
                header('Location: /museo/homepage');
                die();
            }
        }


        protected function removeQueryStringVariables($url) {
            $parts = explode('?', $url);
            return $parts[0];
        }

        protected function chooseController(){ //va aggionto un caso alla volta manualmente per ogni controller
            switch ($this -> params['controller']){
                case 'museo':
                    require_once 'app/controller/museo.php';
                    return $this -> controller = new museo();
                case 'utente':
                    require_once 'app/controller/utente.php';
                    return $this -> controller = new utente();
                case 'eventi':
                    require_once 'app/controller/eventi.php';
                    return $this -> controller = new eventi();
                //case ''
            }
        }
    }
<?php  
    class museo{ //controller

        //qui la parte view
        public function index(){//funzione che verra' richiamata se non e' specificata o e' sbagliato il nome della funzione
            header('Location: /museo/homepage');//rimanda alla homepage
            die();
        }
        public function homepage(){ //view dell'index
            require_once "app/template/museo/homepage.php";
        }

        public function aboutUs(){
            require_once "app/template/museo/aboutUs.php";
        }

        //cambio controller
        public function login(){ 
            header("Location: /utente/login"); //rimanda ad un'altro controller
            die();
        }
    }
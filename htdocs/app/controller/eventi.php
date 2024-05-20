<?php  
    class eventi{ //controller
        

        //  qui ci sono tutte le view
        public function index(){//funzione che verra' richiamata se non e' specificata o e' sbagliato il nome della funzione
            header('Location: /eventi/eventiFuturi');//rimanda agli eventi futuri
            die();
        }
        public function eventiFuturi(){ 

            /*
            la classe eventoModel ha un metodo statico che ritorna tutti gli eventi futuri
            passa gli eventi alla view e li mostra
            */

            require_once "app/model/eventoModel.php";
            $modello = new eventoModel() ;
            $eventi = $modello -> eventiFuturi();
            require_once "app/template/eventi/eventiFuturi.php";
        }

        public function eventiPassati(){ 
            
            /*
            la classe eventoModel ha un metodo statico che ritorna tutti gli eventi passati
            passa gli eventi alla view e li mostra
            */

            $modello = new eventoModel() ;
            $eventi = $modello -> eventiPassati();
            require_once "app/template/eventi/eventiPassati.php";
        }

        public function dettagliEvento($idEvento){ 
            /*
            prende in input un url tipo: eventi/dettaglievento/nomeEvento
            controlla se esiste l'evento
            trova l'evento, le categorie ammesse e gli accessori disponibili nel db e li mostra 
            */
            $evento = new eventoModel($idEvento);
            if($evento->exists()){
                $evento -> caricaDati();
                $accessiori = $evento -> getAccessori();
                $categorie = $evento -> getCategorie();
                require_once "app/template/eventi/dettagliEvento.php";
            }
        }

        public function acquistaBiglietto($idEvento){// 

            /*
            controlla se l'utente e' loggato e 
            se si fa vedere tutte
            altrimenti manda alla form di signin
            */
            session_start();
            if(isset($_SESSION["user"]) && $_SESSION["user"] -> login()){ //se il tipo e' loggato
                $user = $_SESSION["user"];
                $evento = new eventoModel($idEvento);
                $evento -> caricaDati();
                $accessiori = $evento -> getAccessori();
                $categorie = $evento -> getCategorie();
                require_once "app/template/eventi/acquistaBiglietto.php";
            }else{ //se non e' loggato manda alla pagina signin per verificare gli errori
                header("Location: /utente/login");
                die();
            }

            
        }

        //parte logica

        public function elaboraAcquistaBiglietto($idEvento){
            
            /*controlla se l'utente e' loggato e se ci sono accessori e categorie dentro post in input 
            se si fa vedere tutte
            altrimenti manda alla form di signin
            */
            session_start();
            if(isset($_SESSION["user"]) && $_SESSION["user"] -> login()){ //se il tipo e' loggato
                $user = $_SESSION["user"];
                $evento = new eventoModel($idEvento);
                $evento -> caricaDati();
                $accessiori = $evento -> getAccessori();
                $categorie = $evento -> getCategorie();
                require_once "app/template/eventi/acquistaBiglietto.php";
            }else{ //se non e' loggato manda alla pagina signin per verificare gli errori
                header("Location: /utente/login");
                die();
            }
        }

        public function logout(){ // manda alla homepage
            session_destroy();
            header("Location: /museo/homepage"); 
            die(); 
        }
        
        

    }
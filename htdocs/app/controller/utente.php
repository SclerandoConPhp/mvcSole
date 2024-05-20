<?php  
    require_once "app/model/userModel.php";
    class utente{ //controller
        

        //  qui ci sono tutte le view
        public function index(){//funzione che verra' richiamata se non e' specificata o e' sbagliato il nome della funzione
            header('Location: /utente/login');//rimanda alla homepage
            die();
        }
        public function login(){ //manda il forma
            require_once "app/template/utente/login.php";
            session_start();
        }

        public function logout(){ // manda alla homepage
            session_destroy();
            header("Location: /museo/homepage"); 
            die(); 
        }
        
        public function signin(){ //manda il forma
            require_once "app/template/utente/signin.php";
        }

        
        public function confermaRegistrazione(){ 
            /*controlla se l'utente e' loggato e 
            se si lo gli conferma con questa pagina 
            altrimenti manda alla form di signin
            */
            
            session_start();
            if(isset($_SESSION["user"]) && $_SESSION["user"] -> login()){ //se il tipo e' loggato
                $user = $_SESSION["user"];
                require_once "app/template/utente/confermaRegistrazione.php";
            }else{ //se non e' loggato manda alla pagina signin per verificare gli errori
                header("Location: /utente/signin");
                die();
            }
        }
        public function profilo(){ //se' loggato manda a profilo se no a login

            session_start();
            if(isset($_SESSION["user"]) && $_SESSION["user"] -> login()){ //se il tipo e' loggato mostra profilo
                $user = $_SESSION["user"];
                require_once "app/template/utente/profilo.php";
            }else{ //se non e' loggato manda alla login
                header("Location: /utente/login");
                die();
            }
        }

        public function modificaProfilo(){ // se e' loggato manda alla form di modifica profilo
            session_start();
            if(isset($_SESSION["user"]) && $_SESSION["user"] -> login()){ //se il tipo e' loggato mostra form
                $user = $_SESSION["user"];
                require_once "app/template/utente/modificaProfilo.php";
            }else{ //se non e' loggato manda alla pagina login per verificare gli errori
                header("Location: /utente/login");
                die();
            }
        }

        public function modificaPassword(){ // se e' loggato manda alla form di modifica password
            session_start();
            if(isset($_SESSION["user"]) && $_SESSION["user"] -> login()){ //se il tipo e' loggato mostra form
                $user = $_SESSION["user"];
                require_once "app/template/utente/modificaPassword.php";
            }else{ //se non e' loggato manda alla pagina login per verificare gli errori
                header("Location: /utente/login");
                die();
            }
        }

        public function iMieiBiglietti(){ 
            /* controlla se l'utente e' loggato,
            se si manda alla pagina iMieiBiglietti -> nel tmplate si visualizzeranno gli eventi grazie all'array eventi[]
            altrimanti manda alla login*/
            
            session_start();
            if(isset($_SESSION["user"]) && $_SESSION["user"] -> login()){ //se il tipo e' loggato mostra form
                $user = $_SESSION["user"];
                $biglietti =  $user -> getBiglietti();
                require_once "app/template/utente/iMieiBiglietti.php";
            }else{ //se non e' loggato manda alla pagina login per verificare gli errori
                header("Location: /utente/login");
                die();
            }
        }



        //cambio di controller
        public function aboutUs(){ //cambio controller
            header("Location: /museo/aboutUs"); //rimanda ad un'altro controller
            die();
        }

        public function eventi(){ 
            header("Location: /eventi/index"); //rimanda ad un'altro controller
            die();
        }

        public function homepage(){ 
            header("Location: /museo/homepage"); //rimanda ad un'altro controller
            die();
        }



        
        // dopo c'e' la parte logica del sito
        public function elaboraLogin(){ 

            /*controlla i post in input,
            prova a loggare 
            se si allora logga l'utente e lo manda a eventi*/ 

            if(isset($_POST["username"]) && isset($_POST["password"])){ 

                session_start();
                $username = $_POST["username"];
                $password = $_POST["password"];
                $user = new userModel($username, $password);
                if($user -> login()){
                    $user -> caricaDati();
                    $_SESSION["user"] = $user;
                    header("Location: utente/profilo"); //redirect alla pagina di login con la form
                    die();
                } else { //se valori errati rimanda alla login
                    $_SESSION["error"] = $user -> loginError;
                    header("Location: utente/login"); //redirect alla pagina di login con la form
                    die();
                }
            } else {//se non ci sono stati i post inviati dal form  rimanda alla login
                header("Location: utente/login"); //redirect alla pagina di login con la form
                die();
            }
        }


        public function elaboraSignin(){ 
            /* controlla se sono arrivati input post, 
            si prova a inserirli nel db, 
            se fallisce manda alla pagina di signin specificando l'errore, 
            altrimenti logga l'utente mandandolo agli eventi
            */
            if( isset($_POST["username"]) &&  
                isset($_POST["nome"]) &&
                isset($_POST["cognome"]) &&
                isset($_POST["mail"]) &&
                isset($_POST["password"]) ){ //controllo se c'e' stata un vera richiesta per questa pagina
                
                session_start();
                $username = $_POST["username"];
                $nome = $_POST["nome"];
                $mail = $_POST["mail"];
                $cognome = $_POST["cognome"];
                $password = $_POST["password"];
                $user = new userModel($username, $password); //crea un ipotetico utente

                if($user -> signin($username, $nome, $cognome, $mail, $password)){ //controlla se si puo' mettere e se lo fa lo logga
                    $_SESSION["user"] = $user;
                    header("Location: eventi/index"); //redirect alla pagina di login con la form
                    die();
                } else { //se valori errati rimanda alla login
                    $_SESSION["error"] = $user -> signinError;
                    header("Location: utente/signin"); //redirect alla pagina di login con la form
                    die();
                }
            } else {//se non ci sono stati i post inviati dal form  rimanda alla login
                header("Location: utente/signin"); //redirect alla pagina di login con la form
                die();
            }
        }

        public function elaboraModificaProfilo(){ 
            
            /*se ci sono gli input tramite post e il login 
            cambio i dati  dell'utente, aggiorno pure l'user di sessione e mando alla pagina di profilo 
            altrimenti mando a login*/
            
            session_start();

            if( isset($_POST["nome"]) &&//controlli post
                isset($_POST["cognome"]) &&
                isset($_POST["mail"]) &&
                isset($_SESSION["user"]) && //controllo login
                $_SESSION["user"] -> login() ) 
            {

                $nome = $_POST["nome"];
                $cognome = $_POST["cognome"];
                $email = $_POST["email"];

                $user = $_SESSION["user"];

                $user -> update($nome, $cognome, $email); //modifica profilo

                $_SESSION["user"]= $user;//aggiorno sessione

                header("Location: /utente/profilo");
                die();


            }else{ //se non e' loggato manda alla pagina login per verificare gli errori
                header("Location: /utente/login");
                die();
            }
        }

        public function elaboraModificaPassword(){ 
            /*se ci sono gli input tramite post e il login 
            cambio dell'utente i dati e mando alla pagina di profilo 
            altrimenti mando a login*/
            
            session_start();

            if( isset($_POST["password"]) && //controlli post
                isset($_SESSION["user"]) && //controllo login
                $_SESSION["user"] -> login() ) 
            {
                $password = $_POST["password"];
                $user = $_SESSION["user"];
                $user -> updatePassword($password); //modifica password
                
                $_SESSION["user"]= $user;//aggiorno sessione

                header("Location: /utente/profilo");
                die();


            }else{ //se non e' loggato manda alla pagina login per verificare gli errori
                header("Location: /utente/login");
                die();
            }
        }

    }
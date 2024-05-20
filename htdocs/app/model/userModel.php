<?php

/*
        classe userModel
        campi: 
            username
            nome
            cognome
            mail 
            password: (campo calcolato cosi' = hash(passwordutente + salt) )
            salt
            tipoUtente
        
        metodi:
            __construct(username, password)
            login(): fa la query e se esiste l'account 
            signin(username, nome, cognome, mail, password): inserisce utente nel db
            update(nome, cognome, mail): cambia campi e aggiorna db
            updatePassword(password): cambia password usando anche il salt e aggiorna db
            getBiglietti(): ritorna un array di oggetti bliglietti e questi sono i biglietti comprati da quella persona
         

        classe bigliettiModel
        campi:
            id
            prezzo
            validita'
            visita(un tipo di oggetto) 
            categoria (un tipo di oggetto) 
        metodi:
            __contruct(id, prezzo, validita, visita(un tipo di oggetto), categoria (un tipo di oggetto) )
            setter e getter
        
        classe visitaModel
        campi:
            id
            titolo
            descrizione
            tariffa
            tipoVisita
            dataInizio
            dataFine
            bigliettiDisponibili
        metodi:
            _contruct(tutti i campi)

        
        
        classe categoriaModel
        campi:
            id
            tipoDocumento
            sconto
            descrizione
        metodi:
            __construct(id): imposta l'id ( se l'evento non esiste e va creato l'id lo crea automaticamente il db,
                                            se esiste l'id serve per caricare i dati da db -> oggetto) 
            
            caricaDati(): in base all'id carica i dati nell'oggetto
            
            creaEvento(id, titolo, descrizione, tariffa, tipoVisita, dataInizio, dataFine, bigliettiDisponibili): 
                - crea l'evento e lo inserisce nel db


        
        
        
        
        classe eventoModel
        campi:
            id
            titolo
            descrizione 
            tariffa
            tipoVisita
            dataInizio
            dataFine
            bigliettiDisponibili
        metodi:
            __construct(id): imposta l'id ( se l'evento non esiste e va creato l'id lo crea automaticamente il db,
                                            se esiste l'id serve per caricare i dati da db -> oggetto) 
            
            caricaDati(): in base all'id carica i dati nell'oggetto
            
            creaEvento(id, titolo, descrizione, tariffa, tipoVisita, dataInizio, dataFine, bigliettiDisponibili): 
                - crea l'evento e lo inserisce nel db
            
            getGallery(): restituisce un array di tipo galleryModel[] con tutte le foto legate a quell'evento

        classe galleryModel
            campi:
                id
                descrizione
            metodi:
                __construct(id): imposta l'id ( se l'evento non esiste e va creato l'id lo crea automaticamente il db,
                                            se esiste l'id serve per caricare i dati da db -> oggetto) 
            
                caricaDati(): in base all'id carica i dati nell'oggetto
                creaFoto(descrizione): fa insert into

*/


    require 'app/config/db_config.php';
    
	class userModel{
    	public $username, $nome, $cognome, $maill, $passw, $tipoUtente, $loginError, $signinError;

		public function __construct($username, $pass){
        	$this->username = $username;
            $this->passw = $pass;
        }
        
        public function login(){

            /*
            fa la query e cerca la password del utente non accertato
            ritorna true se la password esiste ed e' giusta
            */
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        	$stmt = $mysqli -> prepare("SELECT passw FROM UTENTE where username = ?");
            $stmt -> bind_param("s", $this->username);

            $stmt -> execute();
            $res = $stmt -> get_result();
            $psw = $res -> fetch_assoc();

            if($res -> num_rows < 1){
                $mysqli->close();
                $loginError = 'Username non trovato';
                return false;
            }else if (password_verify($this->passw, $psw['passw'])) {
                $mysqli->close();
                return true;
            }else{
                $mysqli->close();
                $loginError = 'Password errata';
                return false;
            }
        }
        
        /*
            cerca i dati rimanenti dell'utente tramite username da inserire nell'oggetto: nome, cognome, mail, tipoUtente
            fa il fetch e li associa all'oggetto
            */
        public function caricaDati(){
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        	$stmt = $mysqli -> prepare("SELECT nome, cognome, mail, tipoUtente FROM UTENTE where username = ?");
			$stmt -> bind_param("s", $this -> username);
            $stmt -> execute();
            $res = $stmt -> get_result();

            $gg = $res -> fetch_array();
        	$this ->nome = $gg[0];
            $this ->cognome = $gg[1];
            $this ->maill = $gg[2];
    		$this ->tipoUtente = $gg[3];
            $mysqli->close();
        }
        
        /**
             * esegue la query di insert into, 
             * se inserisce ritorna true
             * se fallisce ritorna false 
             */
        public function signin($username, $nome, $cognome, $mail, $passw){
            
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        	$stmt = $mysqli -> prepare('INSERT INTO `utente` (`username`, `nome`, `cognome`, `mail`, `passw`, `tipoUtente`) VALUES (?,?,?,?,?,"user")');

            /*INSERT INTO `utente` (`username`, `nome`, `cognome`, `mail`, `passw`, `tipoUtente`) VALUES ('qafdasdfasfg', 'adfasdfasdf', 'adsfasdfasdf', 'adsfasdf', 'asdfasdf', 'user'); */
			$stmt -> bind_param("sssss", $username, $nome, $cognome, $mail, password_hash($passw, PASSWORD_DEFAULT));
            $res = $stmt -> execute();
            $mysqli->close();
            return $res;   
        }
        /**
         * fa l'update dei dati dell'utente nel db
         *
         * @param [String] $nome
         * @param [String] $cognome
         * @param [String] $mail
         * @return boolean
         */
        public function update($nome, $cognome, $mail){

            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        	$stmt = $mysqli -> prepare("UPDATE UTENTE SET nome = ?, cognome = ?, mail = ? where username = ?");
			$stmt -> bind_param("ssss", $nome, $cognome, $mail, $this ->username);
            $res = $stmt -> execute();
            $mysqli->close();
            return $res;
        }
         /**
          * updata l'hash della password salvata nel db con la password nuova 
          *
          * @param [String] $password
          * @return boolean
          */
        public function updatePassword($password){
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $stmt = $mysqli -> prepare("UPDATE UTENTE SET passw = ? where username = ?");
            $stmt -> bind_param("ss", password_hash($password), $this ->username);
            $res = $stmt -> execute();
            $mysqli->close();
            return $res;
        }
        
        /**
         * ritorna la queri con tutti i biglietti dell'utente in un array di 2 dimensioni
         *
         * @return array
         */
        public function getBiglietti(){
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $stmt = $mysqli -> prepare("select * from BIGLIETTO B INNER JOIN VISITA V on V.idVisita = B.idVisita INNER JOIN CATEGORIA C on C.codCategoria = B.codCategoria  WHERE utente = ?");
            $stmt -> bind_param("s", $this ->username);
            $stmt -> execute();
            $result = $stmt -> get_result();
            $biglietti = $result -> fetch_all();
            $mysqli->close();
            return $biglietti;
        }
     
    }

                
                
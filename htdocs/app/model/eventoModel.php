<?php
    require 'app/config/db_config.php';

    class eventoModel{
        
    	public $id, $titolo, $descrizione, $tariffa, $tipoVisita, $dataInizio, $dataFine, $bigliettiDisponibili;
        
        // costruttore
        public function __costruct($id){
        	$this->id = $id;
            
        }
        
        // funzione che prende tutti gli altri campi della tabella se esistono
        public function caricaDati(){
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        	$stmt = $mysqli -> prepare("SELECT titolo, descrizione, tariffa, tipoVisita, dataInizio, dataFine, bigliettiDisponibili FROM VISITA where idVisita = ?");
          	$stmt -> bind_param("i", $this->id);
            $stmt -> execute();
            $res = $stmt -> get_result();
            $ris = $res -> fetch_array();
            $this ->titolo = $ris[0];
            $this ->descrizione = $ris[1];
            $this ->tariffa = $ris[2];
            $this ->tipoVisita = $ris[3];
            $this ->dataInizio = $ris[4];
            $this ->dataFine = $ris[5];
            $this ->bigliettiDisponibili = $ris[6];

            $mysqli -> close();
        }
        
        // funzione che ritorna gli eventi futuri (dopo la data odierna)
        public function eventiFuturi(){
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          	$stmt = $mysqli -> prepare("Select idVisita, dataInizio FROM VISITA where dataInizio > ?");
          	$stmt -> bind_param("s", date("Y-m-d",time()));
            $stmt -> execute();
            $res = $stmt -> get_result();

            $eventi = $res -> fetch_all();
            $mysqli -> close();
            return $eventi;
        } 
        
        // funzione che ritorna gli eventi passati (prima della data odierna)
        public function eventiPassati(){

            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

          	$stmt = $mysqli -> prepare("Select idVisita, dataInizio FROM VISITA where dataInizio < ?");
          	$stmt -> bind_param("s", date("Y-m-d",time()));
            $stmt -> execute();
            $res = $stmt -> get_result();
            $eventi = $res -> fetch_all();
            $mysqli -> close();
            return $eventi;	
        }
        
        // crea e inserisce evento nel db
        public function creaEvento($titolo, $descrizione, $traiffa, $tipoVisita, $saraInizio, $dataFine, $bigliettiDisponibili){
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        	$stmt = $mysqli -> prepare("INSERT INTO VISITA (titolo, descrizione, traiffa, tipoVisita, daraInizio, dataFine, bigliettiDisponibili) VALUES (?,?,?,?,?,?,?)");
          	$stmt -> bind_param("ssdsssi",$titolo, $descrizione, $traiffa, $tipoVisita, $saraInizio, $dataFine, $bigliettiDisponibili);
            $ris = $stmt -> execute();
            $mysqli -> close();
            return $ris;
        }
        
        // prende tutte le foto di un evento
        public function getGallery(){
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        	$stmt = $mysqli -> prepare("Select idFoto FROM GALLERY where idVisita = ?");
          	$stmt -> bind_param("i", $this->id);
            $stmt -> execute();
            $res = $stmt -> get_result();
            $foto = $res -> fetch_all();
            $mysqli -> close();
            return $foto;
        }	
    }

<?php

    require 'app/config/db_config.php'; //riferito all'index perche' la pagina viene richiamata nell'index

    class database {
        protected $conn;

        public function connect() {
            $this->conn = null;

            try {
                $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            } catch (mysqli_sql_exception $exception) {
                echo "Errore di connessione: " . $exception->getMessage();
            }

            return $this->conn;
        }

        public function query($sql) {
            return $this->conn->query($sql);
        }

        public function close() {//chiude la connessione al DB
            $this->conn->close();
        }
    }

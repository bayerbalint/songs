<?php

class Database{
    private $dbName = "music";
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $DB;

    function __construct(){
        $this->createDatabase($this->dbName);
    }

    function getConn($dbName)
    {
        try {
            // Kapcsolódás az adatbázishoz
            $mysqli = mysqli_connect($this->host, $this->user, $this->password, $dbName);

            // Ellenőrizzük a csatlakozás sikerességét
            if (!$mysqli) {
                throw new Exception("Kapcsolódási hiba az adatbázishoz: " . mysqli_connect_error());
            }

            return $mysqli;
        } catch (Exception $e) {
            // Hibás csatlakozás esetén `null`-t ad vissza
            return null;
        }
    }

    function dbExists()
    {
        try {
            $mysqli = $this->getConn('mysql');
            if (!$mysqli) {
                return false;
            }

            $query = sprintf("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '%s';", $this->dbName);
            $result = $mysqli->query($query);

            if (!$result) {
                throw new Exception('Lekérdezési hiba: ' . $mysqli->error);
            }
            $exists = $result->num_rows > 0;

            return $exists;

        }
        catch (Exception $e) {
            return false;
        }
        finally {
            // Ensure the database connection is always closed
            $mysqli?->close();
        }

    }

    function createDatabase(){
        if (!$this->dbExists()){
            $this->DB = $this->getConn("mysql");
            $this->DB->query("CREATE DATABASE {$this->dbName} CHARACTER SET utf8 COLLATE utf8_general_ci;");
            $this->DB = $this->getConn($this->dbName);
            $this->createTables();
        }
    }

    function createTables(){
        $this->DB->query("CREATE TABLE songs(id INT NOT NULL PRIMARY KEY, artist_id INT NOT NULL, album_id INT, genre VARCHAR(100), `language` VARCHAR(100));");
        $this->DB->query("CREATE TABLE albums(id INT NOT NULL PRIMARY KEY, name VARCHAR(100), instrument VARCHAR(100), image_location VARCHAR(100));");
        $this->DB->query("CREATE TABLE artists(id INT NOT NULL PRIMARY KEY, name VARCHAR(100) NOT NULL, album_cover VARCHAR(100), release_date DATE);");
    }

}
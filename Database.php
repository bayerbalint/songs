<?php

class Database{
    private $dbName = "music";
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $DB;

    // fields to upload files
    private $targetFile;
    private $imageFileType;

    function __construct(){
        $this->createDatabase($this->dbName);

        $this->checkFileUpload();

        $this->DB->close();
    }

    function checkFileUpload(){
        if (isset($_POST["submit"])){
            $this->targetFile = basename($_FILES["fileUpload"]["name"]);
            $this->imageFileType = strtolower(pathinfo($this->targetFile, PATHINFO_EXTENSION));
            echo $this->imageFileType;
        }
    }

    

    function getConn($dbName)
    {
        try {
            $mysqli = mysqli_connect($this->host, $this->user, $this->password, $dbName);

            if (!$mysqli) {
                throw new Exception("Kapcsolódási hiba az adatbázishoz: " . mysqli_connect_error());
            }

            return $mysqli;
        } catch (Exception $e) {
            return null;
        }
    }

    function dbExists()
    {
        try {
            $this->DB = $this->getConn('mysql');
            if (!$this->DB) {
                return false;
            }

            $query = sprintf("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '%s';", $this->dbName);
            $result = $this->DB->query($query);

            if (!$result) {
                throw new Exception('Lekérdezési hiba: ' . $this->DB->error);
            }
            $exists = $result->num_rows > 0;

            return $exists;

        }
        catch (Exception $e) {
            return false;
        }
    }

    function uploadToTable($tableName, $fields, $data){
        $this->DB->query("INSERT INTO `{$this->dbName}`{$tableName}({$fields}) VALUES($data)");
    }

    function createDatabase(){
        if (!$this->dbExists()){
            $this->DB->query("CREATE DATABASE {$this->dbName} CHARACTER SET utf8 COLLATE utf8_general_ci;");

            $this->DB->close();
            $this->DB = $this->getConn($this->dbName);

            $this->createTables();
            $this->fillTables();
        }
    }

    function createTables(){
        $this->DB->query("CREATE TABLE songs(id INT NOT NULL PRIMARY KEY, artist_id INT NOT NULL, album_id INT, genre VARCHAR(100), `language` VARCHAR(100));");
        $this->DB->query("CREATE TABLE artists(id INT NOT NULL PRIMARY KEY, name VARCHAR(100), instrument VARCHAR(100), image MEDIUMBLOB);");
        $this->DB->query("CREATE TABLE albums(id INT NOT NULL PRIMARY KEY, name VARCHAR(100) NOT NULL, album_cover MEDIUMBLOB, release_date DATE);");
    }

    function fillTables(){

    }
}
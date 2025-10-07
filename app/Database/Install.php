<?php

namespace App\Database;

use App\Models\RoomModel;
use App\Models\GuestModel;
use App\Views\Display;
use App\Database\Database;
use Exception;

class Install extends Database
{

    public function __construct($config){
        parent::__construct($config);
        if (!$this::dbExists()){
            $this->createDatabase();
            $this->createTables();
            $this->fillTables();
        }
        $this->setGlobalMaxAllowedPacket();
    }

    private function dbExists(): bool
    {
        try {
            $mysqli = $this->getConn('mysql');
            if (!$mysqli) {
                return false;
            }

            $query = sprintf("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '%s';", self::DEFAULT_CONFIG['database']);
            $result = $mysqli->query($query);

            if (!$result) {
                throw new Exception('Lekérdezési hiba: ' . $mysqli->error);
            }
            $exists = $result->num_rows > 0;

            return $exists;

        }
        catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            return false;
        }
        finally {
            // Ensure the database connection is always closed
            $mysqli?->close();
        }

    }

    private function getConn($dbName)
    {
        try {
            // Kapcsolódás az adatbázishoz
            $mysqli = mysqli_connect(self::DEFAULT_CONFIG["host"], self::DEFAULT_CONFIG["user"], self::DEFAULT_CONFIG["password"], $dbName);
    
            // Ellenőrizzük a csatlakozás sikerességét
            if (!$mysqli) {
                throw new Exception("Kapcsolódási hiba az adatbázishoz: " . mysqli_connect_error());
            }
    
            return $mysqli;
        } catch (Exception $e) {
            // Hibaüzenet megjelenítése a felhasználónak
            echo $e->getMessage();
        
            // Hibás csatlakozás esetén `null`-t ad vissza
            return null;
        }
    }

    private function setGlobalMaxAllowedPacket(){
        return $this->execSql("SET GLOBAL max_allowed_packet=1073741824;");
    }

    private function createDatabase(){
        return $this->execSql("CREATE DATABASE music CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }

    private function createTable(string $tableName, string $tableBody, string $dbName): bool
    {
        try {
            $sql = "
                CREATE TABLE `$dbName`.`$tableName`
                ($tableBody)
                ENGINE = InnoDB
                DEFAULT CHARACTER SET = utf8
                COLLATE = utf8_hungarian_ci;
            ";
            return (bool) $this->execSql($sql);

        } catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            return false;
        }
    }


    private function createTables($dbName = self::DEFAULT_CONFIG['database']){
        $this->createTableBands($dbName);
        $this->createTableAlbums($dbName);
        $this->createTableArtists($dbName);
        $this->createTableSongs($dbName);
    }

    private function createTableBands($dbName){
        $tableBody = "
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
            name VARCHAR(50) NOT NULL,  
            bandImage mediumblob NOT NULL";

        return $this->createTable('bands', $tableBody, $dbName);
    }
    
    private function createTableAlbums($dbName){
        $tableBody = "
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
            name VARCHAR(50) NOT NULL, 
            releaseDate INT(10), 
            albumCover mediumblob NOT NULL";

        return $this->createTable('albums', $tableBody, $dbName);
    }

    private function createTableArtists($dbName){
        $tableBody = "
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            bandId INT NOT NULL,
            name VARCHAR(50) NOT NULL,
            born INT(10) NOT NULL,
            instrument VARCHAR(50), 
            artistImage mediumblob NOT NULL,
            FOREIGN KEY (`bandId`) REFERENCES bands(`id`) ON DELETE CASCADE";

        return $this->createTable('artists', $tableBody, $dbName);
    }

    private function createTableSongs($dbName){
        $tableBody = "
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
            artistId INT NOT NULL, 
            albumId INT NOT NULL,
            bandId INT NOT NULL,
            song mediumblob NOT NULL, 
            title VARCHAR(50) NOT NULL, 
            genre VARCHAR(50) NOT NULL, 
            `language` VARCHAR(50) NOT NULL,
            FOREIGN KEY (`artistId`) REFERENCES artists(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`albumId`) REFERENCES albums(`id`) ON DELETE CASCADE";

        return $this->createTable('songs', $tableBody, $dbName);
    }    

    private function fillTableBands($dbName){
        $propertiesAndFiles = $this->getPropertiesAndFiles("bands");

        for ($i = 0; $i < count($propertiesAndFiles[1]) - 1; $i++){
            $currProperties = explode(";", $propertiesAndFiles[0][$i]);
            $sql = "INSERT INTO `$dbName`.`bands`(name,bandImage) VALUES('" . $currProperties[0] . "',LOAD_FILE('" . str_replace("\\","/",realpath("../files/bands/" . $propertiesAndFiles[1][$i])) . "'))";
            $this->execSql($sql);
        }
    }

    private function fillTableAlbums($dbName){
        $propertiesAndFiles = $this->getPropertiesAndFiles("albums");
        
        for ($i = 0; $i < count($propertiesAndFiles[1]) - 1; $i++){
            $currProperties = explode(";", $propertiesAndFiles[0][$i]);
            $sql = "INSERT INTO `$dbName`.`albums`(name,releaseDate,albumCover) VALUES('" . $currProperties[0] . "'," . $currProperties[1] . ",LOAD_FILE('" . str_replace("\\","/",realpath("../files/albums/" . $propertiesAndFiles[1][$i])) . "'))";
            $this->execSql($sql);
        }
    }

    private function fillTableArtists($dbName){
        $propertiesAndFiles = $this->getPropertiesAndFiles("artists");

        for ($i = 0; $i < count($propertiesAndFiles[1]) - 1; $i++){
            $currProperties = explode(";", $propertiesAndFiles[0][$i]);
            $sql = "INSERT INTO `$dbName`.`artists`(bandId,name,born,instrument,artistImage) VALUES(" . $currProperties[0] . ",'" . $currProperties[1] . "'," . $currProperties[2] . ",'" . $currProperties[3] . "',LOAD_FILE('" . str_replace("\\","/",realpath("../files/artists/" . $propertiesAndFiles[1][$i])) . "'))";
            $this->execSql($sql);
        }
    }

    private function fillTableSongs($dbName){
        $propertiesAndFiles = $this->getPropertiesAndFiles("songs");
        
        for ($i = 0; $i < count($propertiesAndFiles[1]) - 1; $i++){
            $currProperties = explode(";", $propertiesAndFiles[0][$i]);
            $sql = "INSERT INTO `$dbName`.`songs`(albumId,bandId,song,title,genre,`language`) VALUES(" . $currProperties[0] . "," . $currProperties[1] . "," . $currProperties[2] . ",LOAD_FILE('" . str_replace("\\","/",realpath("../files/songs/" . $propertiesAndFiles[1][$i])) . "'),'" . $currProperties[3] . "','" . $currProperties[4] . "','" . $currProperties[5] . "')";
            $this->execSql($sql);
        }
    }

    private function getPropertiesAndFiles($tableName){
        $files = array_diff(scandir("../files/$tableName/"), array('.', '..'));
        $propertiesFile = fopen("../files/$tableName/properties.txt", "r");
        $properties = explode("\n", fread($propertiesFile, filesize("../files/$tableName/properties.txt")));
        fclose($propertiesFile);
        sort($files);
        return [$properties, $files];
    }

    private function fillTables($dbName = self::DEFAULT_CONFIG['database']){
        $this->fillTableBands($dbName);
        $this->fillTableAlbums($dbName);
        $this->fillTableArtists($dbName);
        $this->fillTableSongs($dbName);
    }
}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <label for=""></label>
</body>
</html>

<?php

class Html{
    function __construct(){
        $this->echoHead();
        $this->echoBodyStart();
        $this->echoArtistForm();
        $this->echoFormEnd();
        $this->echoBodyEnd();
    }

    function echoHead(){
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Songs</title>
            <link rel="stylesheet" href="style.css">
        </head>
        ';
    }
    
    function echoBodyStart(){
        echo '
        <body>
        <nav>
            <ul>
                <li>Zenehallgató</li>
            </ul>
        </nav>
        <form action="index.php" method="post" enctype="multipart/form-data">';
    }

    function echoAlbumForm(){
        echo '
        <label>Album címe: <input type="text" placeholder="Nevermind"></label><br>
        <label>Album képe: <input type="file" name="fileUpload" id="fileUpload"></label><br>
        <label>Kiadás éve: <input type="number" min="1900" max="2025" value="1992"></label><br>';
    }

    function echoArtistForm(){
        echo '
        <label>Művész neve: <input type="text" placeholder="Nirvana"></label><br>
        <label>Hangszer: <input type="text" placeholder="vokál"></label><br>
        <label>Profilkép: <input type="file" name="fileUpload" id="fileUpload"></label><br>';
    }

    function echoSongForm(){
        echo '
        <label>Művész: <input type="text" placeholder="Nirvana"></label><br>
        <label>Album: <input type="text" placeholder="Nevermind"></label><br>
        <label>Műfaj: <input type="text" placeholder="Alternative Rock"></label><br>
        <label>Nyelv: <input type="text" placeholder="English"></label><br>';
    }

    function echoFormEnd(){
        echo '
        <input type="submit" name="submit">
        </form>';
    }
    
    function echoBodyEnd(){
        echo '
        </body>
        </html>
        ';
    }
}
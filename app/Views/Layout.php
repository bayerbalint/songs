<?php

namespace App\Views;

class Layout{
    public static function header($title = "Songs"){
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>$title</title>
            <script src="/js/script.js" type="text/javascript"></script>
            <link rel="stylesheet" href="/css/style.css" type="text/css">
            <link rel="stylesheet" href="/fontawesome/css/all.css" type="text/css">
        </head>
        <body>        
        HTML;
        self::navbar();
        self::handleMessages();
        echo '<div class="container">';
    }

    private static function handleMessages(){
        $messages = [
            'succes_message' => 'succes',
            'warning_message' => 'warning',
            'error_message' => 'error',
        ];

        foreach ($messages as $key => $type){
            if (isset($_SESSION[$key])){
                Display::message($_SESSION[$key], $type);
                unset($_SESSION[$key]);
            }
        }
    }

    private static function navbar(){
        echo <<<HTML
        <nav class="navbar">
            <ul class="nav-list">
                <li><a href="/" title="Zenék"><button class="nav-button">Zenék</button></a></li>
                <li><a href="/artists" title="Előadók"><button class="nav-button">Előadók</button></a></li>
                <li><a href="/albums" title="Albumok"><button class="nav-button">Albumok</button></a></li>
                <li><a href="/bands" title="Együttesek"><button class="nav-button">Együttesek</button></a></li>
            </ul>
        </nav>
        HTML;
    }

    public static function footer() {
        echo <<<HTML
        </div>
            <footer> 
                <hr>
                <p>2025 &copy; Bayer Bálint, György Zoltán Szilárd, Oszaczki Csaba</p>
            </footer>
        </body>
        </html>
        HTML;
    }
}
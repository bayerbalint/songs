<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav>
        <ul>
            <li>cigi</li>
            <li>cigi</li>
            <li>cigi</li>
            <li>cigi</li>
        </ul>
    </nav>
</body>
</html>

<?php

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
    ';
}

function echoBodyEnd(){
    echo '
    </body>
    </html>
    ';
}
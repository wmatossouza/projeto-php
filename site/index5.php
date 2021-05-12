<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    $dsn = "mysql:host=db;dbname=phprs";
    $dbuser = "root";
    $dbpass = "phprs";

    // $dsn = "mysql:host=fdb22.awardspace.net;port=3306;dbname=3491651_jogobd";
    // $dbuser = "3491651_jogobd";
    // $dbpass = "b^__!%^A8-J8]MoQ";


    try{
        $pdo = new PDO($dsn,$dbuser, $dbpass);
        echo "Conectado!";

    }catch (PDOException $e){
        echo $e->getMessage();
    }
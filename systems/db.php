<?php
    $dsn = "mysql:host=localhost;dbname=checkpoint";
    $user = 'root';
    $password = '';
    $options = [];

    try{
        $connection = new PDO($dsn, $user, $password, $options);
        //echo 'Connection success';
    }catch(PDOException $e){
        echo 'Connection failed';
    }

<?php

    ob_start(); //Turns on output buffering
    session_start();

    date_default_timezone_set("Asia/Taipei");

    try {
        $con = new PDO("mysql:dbname=VideoTube;host=localhost", "user", "test");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

?>
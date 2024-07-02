<?php

function dbConnect()
{
    try {
        $servername = "localhost";
        $database = "fieldlabhub";
        $dsn = "mysql:host=$servername;dbname=$database";
        $username = "root";
        $password = "";

        $conn = new PDO($dsn, $username, $password);
        return $conn;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

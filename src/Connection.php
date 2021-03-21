<?php

namespace App;

use \PDO;

//Conneciton a la BDD
class Connection
{

    public static function getPDO(): PDO
    {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";

        return new PDO("mysql:host=$servername;dbname=tutoblog", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}

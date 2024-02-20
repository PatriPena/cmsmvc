<?php

namespace App\helper;

class DBHelper
{
    var $db;
    function __construct(){
    
        //garantiza que la conexion sea siempre UTF8
    $opciones = [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
    
    //conexion mediante PDO
    try {
    $this->db = new \PDO(
     'myysql:host=localhost;dbname=cms-mvc',
     'root',
     '1234',
    $opciones);
    $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
  echo 'Falló la conexión:' . $e->getMessage();
    
}
}
}

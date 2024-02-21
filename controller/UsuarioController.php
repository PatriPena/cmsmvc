<?php
namespace App\Controller;

use App\Helper\DBHelper;
use App\Helper\ViewHelper;
use App\Model\Nova;

class UsuarioController
{
var $db;
    var $view;
    //ver view

    public function __construct()
    {
        //Conexion a la BBDD
        $DBHelper = new DBHelper();
        $this->db = $DBHelper->db;
       
        //Instanciar Viewhelper
        $viewhelper = new ViewHelper();
        $this->view = $ViewHelper;   

    }
}
<?php

namespace App\Controller; 

use App\helper\DBHelper;
use App\helper\ViewHelper;
use App\Model\Nova;
class Appcontroller
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
        $viewHelper = new ViewHelper();
        $this->view = $viewHelper;   

    }
    //instanciacion de noticias en el front end
    public function index()
    {
        //esto es una consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM novas WHERE activo=1 AND home=1 ORDER BY datat DESC");
        //asignacion de resultados a un array de instancias de un modelo
        $novas = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)) {
            array_push($novas, new Nova($row));
        }
        //llamar a la vista
        $this->view->vista("App", "index", $novas);
    }

    //instanciacion de noticias back-end
    public function novas()
    {
        //esto es una consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM novas WHERE activo=1 AND home=1 ORDER BY datat DESC");
        //asignacion de resultados a un array de instancias de un modelo
        $novas = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)) {
            array_push($novas, new Nova($row));
        }
        //llamar a la vista
        $this->view->vista("App", "novas", $novas);
    }
    public function nova($slug){
        //consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM novas WHERE activo=1 AND slug='$slug' LIMIT 1");
        //asignar el resultado a una instancia del modelo
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $nova = new Nova($row);

        //llamar a la vista
        $this ->view->vista("app","nova", $nova);
   
    }
}

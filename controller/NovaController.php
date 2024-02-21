<?php
namespace App\Controller;

use App\Helper\DBHelper;
use App\Helper\ViewHelper;
use App\Model\Nova;

class NovaController
{

public $db;
public $view;

public function __construct()
{
    //Conexion a la BBDD
    $DBHelper = new DBHelper();
    $this->db = $DBHelper->db;

    //Instanciar Viewhelper
    $ViewHelper = new ViewHelper();
    $this->view = $ViewHelper;   
}


public function index()
{
//permisos
$this->view->permisos ('novas');
//por defecto el orden de fecha seria ascendente
$rowset =$this->db->query('SELECT * FROM novas ORDER BY datat DESC');
// asigno resultados a un array de isntancias del modelo
$novas = [];
while ($row = $rowset->fetch(\PDO::FETCH_OBJ)) {
    array_push($novas, new Nova($row));
}
    $this->view->vista('admin', 'novas/index', $novas);
}
//para activar o desactivar
public function activar($id){
$this->view->permisos('novas');
//obtengo la noticia
$rowset =$this->db->query("SELECT * FROM novas WHERE id='$id' LIMIT 1");
$row = $rowset->fetch(\PDO::FETCH_OBJ);
$nova = new Nova($row);

if($nova->activo == 1){
    //desactivo la noticia
    $consulta = $this->db->exec("UPDATE novas SET activo=0 WHERE id='$id' ");

    //mensaje y redireccion
    ($consulta > 0) ? // compruebo la consulta para ver que no ha habido erores

    $this->view->redireccionConMensaje('admin/novas', 'green', "La noticia <strong>$nova->titulo</strong>se ha desactivado correctamente") :
    $this->view->redireccionConMensaje('admin/novas', 'red', 'Hubo un error al guardar en la base de datos'); 
    } else {
    //activo la noticia
    $consulta =$this->db->exec("UPDATE novas SET archivo=1");









}

public function home($id){
}

public function borrar($id){
}
public function crear(){
}
public function editar($id){
}

}

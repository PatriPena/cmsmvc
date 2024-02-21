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
        $this->view->permisos('novas');
        //por defecto el orden de fecha seria ascendente
        $rowset = $this->db->query('SELECT * FROM novas ORDER BY datat DESC');
        // asigno resultados a un array de isntancias del modelo
        $novas = [];
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)) {
            array_push($novas, new Nova($row));
        }
        $this->view->vista('admin', 'novas/index', $novas);
    }
    //para activar o desactivar
    public function activar($id)
    {
        $this->view->permisos('novas');
        //obtengo la noticia
        $rowset = $this->db->query("SELECT * FROM novas WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $nova = new Nova($row);

        if ($nova->activo == 1) {
            //desactivo la noticia
            $consulta = $this->db->exec("UPDATE novas SET activo=0 WHERE id='$id' ");

            //mensaje y redireccion
            ($consulta > 0) ? // compruebo la consulta para ver que no ha habido erores

                $this->view->redireccionConMensaje('admin/novas', 'green', "La noticia <strong>$nova->titulo</strong>se ha desactivado correctamente") :
                $this->view->redireccionConMensaje('admin/novas', 'red', 'Hubo un error al guardar en la base de datos');
        } else {
            //activo la noticia
            $consulta = $this->db->exec("UPDATE novas SET archivo=1 WHERE id='$id' ");

            //mensaje y redireccion
            ($consulta > 0) ? // compruebo la consulta para ver que no ha habido erores

                $this->view->redireccionConMensaje('admin/novas', 'green', "La noticia <strong>$nova->titulo</strong>se ha desactivado correctamente") :
                $this->view->redireccionConMensaje('admin/novas', 'red', 'Hubo un error al guardar en la base de datos');
        }
    }

    public function home($id)
    {

        $this->view->permisos('novas');
        //obtengo la noticia
        $rowset = $this->db->query("SELECT * FROM novas WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $nova = new Nova($row);

        if ($nova->home == 1) {
            //quito la noticia del home
            $consulta = $this->db->exec("UPDATE novas SET home=0 WHERE id='$id' ");

            //mensaje y redireccion
            ($consulta > 0) ? // compruebo la consulta para ver que no ha habido erores

                $this->view->redireccionConMensaje('admin/novas', 'green', "La noticia <strong>$nova->titulo</strong> ya no se muestra en home") :
                $this->view->redireccionConMensaje('admin/novas', 'red', 'Hubo un error al guardar en la base de datos');
        } else {
            //Muestro la noticia en home
            $consulta = $this->db->exec("UPDATE novas SET home=1 WHERE id='$id' ");

            //mensaje y redireccion
            ($consulta > 0) ? // compruebo la consulta para ver que no ha habido erores

                $this->view->redireccionConMensaje('admin/novas', 'green', "La noticia <strong>$nova->titulo</strong> ahora se muestra en home") :
                $this->view->redireccionConMensaje('admin/novas', 'red', 'Hubo un error al guardar en la base de datos');
        }
    }
    public function borrar($id)
    {
       $this->view->permisos('novas');
       //obtengo la noticia
       $rowset =$this->db->query("SELECT * FROM novas WHERE id='$id' LIMIT 1");
       $row = $rowset->fetch(\PDO::FETCH_OBJ);
       $nova = new Nova($row);

       //borro la noticia
        $consulta = $this->db->exec("DELETE FROM novas WERE id='$id'");

        //borro la imagen asociada
        $archivo =$_SESSION['public'] . 'img/' . $nova->imaxe;
        $texto_imaxe ='';
        if(is_file($archivo)){
        unlink ($archivo);
        $texto_imaxe = 'se ha borrado la imagen asociada;'
        
         //mensaje y redireccion
 
        ($consulta > 0) ? // compruebo la consulta para ver que no ha habido erores

        $this->view->redireccionConMensaje('admin/novas', 'green', "La noticia se borró correctamente $texto_imaxe.") :
        $this->view->redireccionConMensaje('admin/novas', 'red', 'Hubo un error al actualizar en la base de datos');
        }
    }
    public function crear()
    {
        //permisos
        $this->view->permisos('novas');

        //creo una nueva instancia
        $nova = new Nova();

        //llamo a la ventana de edicion
        $this->view->vista('admin', 'novas/editar', $nova);
    
    }
    
    public function editar($id)
    {
        // Permisos
        $this->view->permisos('novas');

        // Si ha pulsado el botón de guardar
        if (isset($_POST['guardar'])) {
            // Recupero los datos del formulario
            $titulo = filter_input(INPUT_POST, 'titulo', /* FILTER_SANITIZE_STRING */ FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
            $extracto = filter_input(INPUT_POST, 'extracto', /* FILTER_SANITIZE_STRING */ FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
            $autor = filter_input(INPUT_POST, 'autor', /* FILTER_SANITIZE_STRING */ FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
            $datat = filter_input(INPUT_POST, 'datat', /* FILTER_SANITIZE_STRING */ FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
            $texto = filter_input(INPUT_POST, 'texto', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Formato de data para SQL
            $datat = \DateTime::createFromFormat('d-m-Y', $datat)->format('Y-m-d H:i:s');

            // Xerar slug (url amigable)
            $slug = $this->view->getSlug($titulo);

            // Imaxe
            $imaxe_recibida = $_FILES['imaxe'];
            $imaxe = ($_FILES['imaxe']['name']) ? $_FILES['imaxe']['name'] : '';
            $imaxe_subida = ($_FILES['imaxe']['name']) ? '/var/www/html' . $_SESSION['public'] . 'img/' . $_FILES['imaxe']['name'] : '';
            $texto_img = ''; // Para a mensaxe

            if ($id == 'nuevo') {
                // Creo unha nova nova
                $consulta = $this->db->exec("INSERT INTO novas 
                    (titulo, extracto, autor, datat, texto, slug, imaxe) VALUES 
                    ('$titulo','$extracto','$autor','$datat','$texto','$slug','$imaxe')");

                // Subo la imaxe
                if ($imaxe) {
                    if (is_uploaded_file($imaxe_recibida['tmp_name']) && move_uploaded_file($imaxe_recibida['tmp_name'], $imaxe_subida)) {
                        $texto_img = ' La imaxe se ha subido correctamente.';
                    } else {
                        $texto_img = ' Hubo un problema al subir la imaxe.';
                    }
                }

                // Mensaje y redirección
                ($consulta > 0) ?
                    $this->view->redireccionConMensaje('admin/novas', 'green', "La nova <strong>$titulo</strong> se creado correctamente." . $texto_img) :
                    $this->view->redireccionConMensaje('admin/novas', 'red', 'Hubo un error al guardar en la base de datos.');
            } else {
                // Actualizo a nova
                $this->db->exec("UPDATE novas SET 
                    titulo='$titulo',extracto='$extracto',autor='$autor',
                    datat='$datat',texto='$texto',slug='$slug' WHERE id='$id'");

                // Subo e actualizo a imaxe
                if ($imaxe) {
                    if (is_uploaded_file($imaxe_recibida['tmp_name']) && move_uploaded_file($imaxe_recibida['tmp_name'], $imaxe_subida)) {
                        $texto_img = ' La imaxe se ha subido correctamente.';
                        $this->db->exec("UPDATE novas SET imaxe='$imaxe' WHERE id='$id'");
                    } else {
                        $texto_img = ' Hubo un problema al subir la imaxe.';
                    }
                }

                // Mensaxe e redirección
                $this->view->redireccionConMensaje('admin/novas', 'green', "La nova <strong>$titulo</strong> se guardado correctamente." . $texto_img);
            }
        }

        // Si non, obteño nova e amoso a xanela de edición
        else {
            // Obteño a nova
            $rowset = $this->db->query("SELECT * FROM novas WHERE id='$id' LIMIT 1");
            $row = $rowset->fetch(\PDO::FETCH_OBJ);
            $nova = new Nova($row);

            // Chamo á xanela de edición
            $this->view->vista('admin', 'novas/editar', $nova);
        }
    }



    }
}

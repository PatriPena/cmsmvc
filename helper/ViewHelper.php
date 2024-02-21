<?php

//este documento resuelve que pasa con los datos y las vistas

namespace App\Helper;

class ViewHelper
{
   public function vista($carpeta, $archivo, $datos = null)
    {
        //llamar al header
        require("../view/$carpeta/partials/header.php");

        //llamar a los contenidos
        require("../view/$carpeta/$archivo.php");

        //llamar al footer
        require("../view/$carpeta/partials/footer.php");
    }
}

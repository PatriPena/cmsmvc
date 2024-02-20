<?php

namespace App;

switch ($ruta) {
    //Front-end
    case "":
    case "/":
        controller()->index();
        break;

    case "novas":
        controller()->novas();
        break;


    //Back-end
    case "admin":
    case "admin/entrar":
        controller()->usuarios()->entrar();
        break;
    case"admin/sair":
        controller()->usuarios()->salir();
        break;
}

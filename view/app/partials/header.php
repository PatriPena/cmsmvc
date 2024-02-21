<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias de GrifendorRRR</title>
</head>

<body>
    <nav>
        <div class="nav-wrapper">
            <!--logotipo-->
            <a><img src="" alt="logotipo"></a>
            <ul id="nav.mobile">
                <li><a href= "<?php echo $_SESSION ['home']?>" title="Inicio" >Inicio</li>
                <li><a href= "<?php echo $_SESSION ['home']?>novas" title="Noticias" >Noticias</li>
                <li><a href= "<?php echo $_SESSION ['home']?>acercade" title="Acerca de" >Acerca de</li>
                <li><a href= "<?php echo $_SESSION ['home']?>contacto" title="Contacto" >Contacto</li>
                <li><a href= "<?php echo $_SESSION ['home']?>admin" title="Administracion" target="_blank" >Admin</li>

            </ul>

        </div>



    </nav>
</body>

</html>
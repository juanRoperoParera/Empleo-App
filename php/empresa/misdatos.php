<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Empleo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/estilo.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <?php
            require_once("../../bd/bd.php");
            require_once("../funciones.php");
            $con = conectar::conexion();
            nav();

        ?>

    </header>
    <main>
        <section class="misdatos sesion">
        <h3>GESTIÓN CUENTA EMPRESA</h3>
        <?php
            $usuario = $_SESSION['nombre'];
            $id = "select id from empresa where cif=?";
            $stmt = $con->prepare($id);
            $stmt->bind_param("s",$usuario);
            
            $stmt->execute();

            $stmt->bind_result($idUsuario);
            $stmt->fetch();
            $stmt->close();

            $datos = $con->query("select nombre,logo,portada from empresa where id=$idUsuario");
                    $i = 0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['nombre'] = $fila['nombre'];
                        $nombreAntiguo = $fila['nombre'];
                        $oferta[$i]['logo'] = $fila['logo'];
                        $oferta[$i]['portada'] = $fila['portada'];
                       
                        $i++;
                    }           
                    for($i = 0; $i<count($oferta); $i++){
                        $nombre = $oferta[$i]["nombre"];
                        $logo = $oferta[$i]["logo"];
                        $portada = $oferta[$i]["portada"]; 

                        echo "
                        <div class='formulario'>  ";
                          
                        echo "<form action='#' method='post' autocomplete='off' enctype='multipart/form-data'>
                                <input type='text' name='nombre' value='$nombre'>
                                <input type='password' placeholder='NUEVA CONTRASEÑA' name='contraseña'>
                                <input type='file' name='portada' class='portada'>
                                <input type='file' name='logo'>";
                                echo"<div class='preview'>";                   
                        if($portada == ""){
                            echo "<h4>Sin foto de portada.</h4>";
                        }else{
                            echo "<img class='portadaCuenta' src='../../assets/img/empresa/portada/$portada' alt=''> ";
                        } 
                        echo "<img src='../../assets/img/empresa/logo/$logo' alt=''>
                    </div>";  
                        echo "<input type='submit' name='modificar' value='Modificar'>
                            </form>
                        </div>
                        ";
                        
                    }
                    
                    if(isset($_POST['modificar'])){
                        $nombre = $_POST['nombre'];
                        $contraseña = $_POST['contraseña'];

                        $logo=$_FILES['logo']['tmp_name'];
                        $logotipo=$_FILES['logo']['type'];

                        $portada=$_FILES['portada']['tmp_name'];
                        $portadatipo=$_FILES['portada']['type'];

                        if($nombre !== ""){

                            if($logo !== ""){
                                $rutalogo = "../../assets/img/empresa/logo/".$nombre; 

                                switch($logotipo){
                                    case "image/png": $rutalogo .= ".png";
                                            $archivoLogo=$nombre.".png";
                                            break;
                                    case "image/jpeg": $rutalogo .= ".jpg";
                                            $archivoLogo=$nombre.".jpg";
                                            break;
                                    case "image/gif": $rutalogo .= ".gif";
                                            $archivoLogo=$nombre.".gif";
                                            break;
                                }

                                move_uploaded_file($logo,$rutalogo);
                            }

                            if($portada !== ""){
                                $rutaportada = "../../assets/img/empresa/portada/".$nombre; 

                                switch($portadatipo){
                                    case "image/png": $rutaportada .= ".png";
                                            $archivoPortada=$nombre.".png";
                                            break;
                                    case "image/jpeg": $rutaportada .= ".jpg";
                                            $archivoPortada=$nombre.".jpg";
                                            break;
                                    case "image/gif": $rutaportada .= ".gif";
                                            $archivoPortada=$nombre.".gif";
                                            break;
                                }

                                move_uploaded_file($portada,$rutaportada);
                            }

                            $verificacion = "select count(cif) as cuanto from empresa where nombre=?";
                            $stmt = $con->prepare($verificacion);
                            $stmt->bind_param("s",$nombre);
                            
                            $stmt->execute();
        
                            $stmt->bind_result($cuanto);
                            $stmt->fetch();
                            $stmt->close();

                            if(($cuanto == 0) or ($nombre == $nombreAntiguo)){
                                if($contraseña == ""){
                                    if($logo !== "" and $portada !== ""){
                                        $modificar = "update empresa set nombre='$nombre', logo='$archivoLogo', portada='$archivoPortada' where id=$idUsuario";
                                    }elseif($portada !== ""){
                                        $modificar = "update empresa set nombre='$nombre', portada='$archivoPortada' where id=$idUsuario";
                                    }elseif($logo !== ""){
                                        $modificar = "update empresa set nombre='$nombre', logo='$archivoLogo' where id=$idUsuario";
                                    }else{
                                        $modificar = "update empresa set nombre='$nombre' where id=$idUsuario";
                                    }
                                }else{
                                    $contraseñaCodificada = md5(md5($contraseña));
                                    if($logo !== "" and $portada !== ""){
                                        $modificar = "update empresa set nombre='$nombre', logo='$archivoLogo', portada='$archivoPortada', contraseña='$contraseñaCodificada' where id=$idUsuario";
                                    }elseif($portada !== ""){
                                        $modificar = "update empresa set nombre='$nombre', portada='$archivoPortada', contraseña='$contraseñaCodificada' where id=$idUsuario";
                                    }elseif($logo !== ""){
                                        $modificar = "update empresa set nombre='$nombre', logo='$archivoLogo', contraseña='$contraseñaCodificada' where id=$idUsuario";
                                    }else{
                                        $modificar = "update empresa set nombre='$nombre', contraseña='$contraseñaCodificada' where id=$idUsuario";
                                    }
                                }    
                                $datos = $con->query($modificar);
                                header('Refresh: 1.5; URL=#');
                                echo "      
                                <div class='correcto'>
                                    <p><i class='fa-solid fa-triangle-exclamation'></i> Datos modificados satisfactoriamente</p>
                                </div>"; 
                                    
                            }else{
                                echo "      
                                <div class='error'>
                                    <p><i class='fa-solid fa-triangle-exclamation'></i> El nombre de empresa <b>$nombre</b> no esta disponible</p>
                                </div>"; 
                            }
                        }else{
                            echo "      
                                <div class='error'>
                                    <p><i class='fa-solid fa-triangle-exclamation'></i> El nombre no puede estar vacio</p>
                                </div>"; 
                        }

                    }
                    
        ?>
        <br>
        <div class="navegacion">
                    <a href="cuentaempresa.php"><i class="fa-solid fa-arrow-left"></i> Mi cuenta</a>
                </div>
        </section>
    </main>
    <footer class="index">
        <?php
            footer();
            $con->close();
        ?>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</body>

</html>
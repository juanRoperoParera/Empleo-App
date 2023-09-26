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
        <h3>MIS DATOS</h3>
        <?php
            $usuario = $_SESSION['nombre'];

            $id = "select id from usuario where usuario=?";
            $stmt = $con->prepare($id);
            $stmt->bind_param("s",$usuario);
            
            $stmt->execute();

            $stmt->bind_result($idUsuario);
            $stmt->fetch();
            $stmt->close();

            $datos = $con->query("select nombre,f_nac,usuario from usuario where id=$idUsuario");
                    $i = 0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['nombre'] = $fila['nombre'];
                        $oferta[$i]['usuario'] = $fila['usuario'];
                        $oferta[$i]['f_nac'] = $fila['f_nac'];
                       
                        $i++;
                    }                
                    for($i = 0; $i<count($oferta); $i++){
                        $nombre = $oferta[$i]["nombre"];
                        $nombreAntiguo = $oferta[$i]["usuario"];
                        $usuario = $oferta[$i]["usuario"];
                        $f_nac = $oferta[$i]["f_nac"];   

                        $fechaNueva = fechas($f_nac);
                        echo "
                        <div class='formulario'>
                            <form action='#' method='post' autocomplete='off'  enctype='multipart/form-data'>
                                <input type='text' name='nombre' value='$nombre'>
                                <input type='text' name='usuario' value='$usuario'>
                                <input type='date' name='f_nac' value='$f_nac'>
                                <input type='password' placeholder='NUEVA CONTRASEÑA' name='contraseña'>
                                <input type='submit' name='modificar' value='Modificar'>
                            </form>
                        </div>
                        ";
                    }

                    if(isset($_POST['modificar'])){
                        $nombre = $_POST['nombre'];
                        $usuario = $_POST['usuario'];
                        $f_nac = $_POST['f_nac'];
                        $contraseña = $_POST['contraseña'];

                        if($nombre !== "" and $usuario !== "" and $f_nac !== ""){
                            $fecha_actual = time(); 
                            $f_nac_timestamp = strtotime($f_nac);
                            $diferencia = $fecha_actual - $f_nac_timestamp;
        
                            $segundos_por_año = 31536000;
                            $edad = $diferencia / $segundos_por_año;

                            if($edad >= 18){
                                $verificacion = "select count(usuario) as cuanto from usuario where usuario=?";
                                $stmt = $con->prepare($verificacion);
                                $stmt->bind_param("s",$usuario);
                                
                                $stmt->execute();
            
                                $stmt->bind_result($cuanto);
                                $stmt->fetch();
                                $stmt->close();

                                if(($cuanto == 0) or ($usuario == $nombreAntiguo)){
                                    if($contraseña == ""){
                                        $modificar = "update usuario set nombre='$nombre',  f_nac='$f_nac', usuario='$usuario' where id=$idUsuario";
                                    }else{
                                        $contraseñaCodificada = md5(md5($contraseña));
                                        $modificar = "update usuario set nombre='$nombre',  f_nac='$f_nac', usuario='$usuario', contraseña='$contraseñaCodificada' where id=$idUsuario";  
                                    }  
                                    $_SESSION['nombre'] = $usuario;     
                                    $datos = $con->query($modificar);
                                    header('Refresh: 1.5; URL=#');
                                    echo "      
                                    <div class='correcto'>
                                        <p><i class='fa-solid fa-triangle-exclamation'></i> Datos modificados satisfactoriamente</p>
                                    </div>"; 
                                    
                                }else{
                                    echo "      
                                        <div class='error'>
                                            <p><i class='fa-solid fa-triangle-exclamation'></i> El nombre de usuario <b>$usuario</b> no esta disponible</p>
                                        </div>"; 
                                }
                            }else{
                                echo "      
                                <div class='error'>
                                    <p><i class='fa-solid fa-triangle-exclamation'></i> Debes tener al menos <b>18 años</b> para registrarte en la aplicación</p>
                                </div>";
                            }
                        }else{
                            echo "      
                                <div class='error'>
                                    <p><i class='fa-solid fa-triangle-exclamation'></i> Debes rellenar todos los campos</p>
                                </div>"; 
                        }


                    }
        ?>
        <br>
        <div class="navegacion">
                    <a href="cuentausuario.php"><i class="fa-solid fa-arrow-left"></i> Mi cuenta</a>
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
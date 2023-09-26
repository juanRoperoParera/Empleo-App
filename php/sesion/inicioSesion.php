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
        <div class="contenedor-inicio-sesion">
            <section class="sesion inicio card-shadow">
                <h3>INICIAR SESIÓN</h3>
                <div class="formulario">
                    <form action="#" method="post" autocomplete="off">
                        <input type="text" placeholder="USUARIO / CIF" name="usuario">
                        <input type="password" placeholder="CONTRASEÑA" name="contraseña">
                        <div class="form-check form-switch mantener">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="mantener">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Mantener sesión
                                iniciada</label>
                        </div>
                        <input type="submit" name="iniciar" value="Iniciar sesión">
                    </form>
                </div>
            </section>
            <p>Si aún no tiene una cuenta haga click en <a href='../../index.php#registro'
                    class='sesion'>registrarme</a></p>
        </div>
        <?php
            if(isset($_POST['iniciar'])){
                $usuario = $_POST['usuario'];
                $contraseña = $_POST['contraseña'];
                $contraseñaCodificada = md5(md5($contraseña));

                if(isset($_POST['mantener'])){
                    $mantener = 1;
                }else{
                    $mantener = 0;
                }

                if($usuario !== "" and $contraseña !== ""){

                    
                    $verificacion = "select count(usuario) as numUsuario from usuario where usuario=? and contraseña=?";
                    $stmt = $con->prepare($verificacion);
                    $stmt->bind_param("ss",$usuario,$contraseñaCodificada);
                    
                    $stmt->execute();

                    $stmt->bind_result($numUsuario);
                    $stmt->fetch();
                    $stmt->close();

                    $verificacion = "select count(cif) as numEmpresa from empresa where cif=? and contraseña=?";
                    $stmt = $con->prepare($verificacion);
                    $stmt->bind_param("ss",$usuario,$contraseñaCodificada);
                    
                    $stmt->execute();

                    $stmt->bind_result($numEmpresa);
                    $stmt->fetch();
                    $stmt->close();

                    if($numEmpresa !== 0){

                        $_SESSION['nombre'] = $usuario;
                        $_SESSION['tipo'] = "empresa";

                        if($mantener == 1){
                            $datos = session_encode();
                            setcookie('sesion', $datos, time()+(2*365*24*60*60),'/proyectofinal'); 
                        }

                        echo "      
                        <div class='correcto'>
                            <p><i class='fa-solid fa-triangle-exclamation'></i> Sesión iniciada correctamente</p>
                        </div>"; 
                        header('Refresh: 3; URL=../../index.php');
                        
                    }elseif($numUsuario !==0){
                        $_SESSION['nombre'] = $usuario;
                        $_SESSION['tipo'] = "persona";

                        if($mantener == 1){
                            $datos = session_encode();
                            setcookie('sesion', $datos, time()+(2*365*24*60*60),'/proyectofinal'); 
                        }

                        echo "      
                        <div class='correcto'>
                            <p><i class='fa-solid fa-triangle-exclamation'></i> Sesión iniciada correctamente</p>
                        </div>"; 
                        header('Refresh: 1; URL=../../index.php');
                    }else{
                        echo "      
                        <div class='error'>
                            <p><i class='fa-solid fa-triangle-exclamation'></i> El nombre de usuario o la contraseña son incorrectos</p>
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
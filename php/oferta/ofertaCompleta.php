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

            $id = $_GET['id'];
            $idOFerta = $_GET['id'];
        ?>

    </header>
    <main>
        <section class="info-oferta card-shadow">
            <?php
                if(isset($_SESSION["tipo"])){
                    $datos = $con->query("select oferta.id id, titulo, fecha, empresa.nombre empresa, empresa.logo logo, empresa.portada portada, descripcion from oferta,empresa where oferta.id_empresa=empresa.id and oferta.id = $id");
                    $i = 0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['id'] = $fila['id'];
                        $oferta[$i]['titulo'] = $fila['titulo'];
                        $oferta[$i]['fecha'] = $fila['fecha'];
                        $oferta[$i]['logo'] = $fila['logo'];                     
                        $oferta[$i]['empresa'] = $fila['empresa'];  
                        $oferta[$i]['portada'] = $fila['portada'];  
                        $oferta[$i]['descripcion'] = $fila['descripcion'];  

                        $i++;
                    }                
                    for($i = 0; $i<count($oferta); $i++){
                        $id = $oferta[$i]["id"];
                        $titulo = $oferta[$i]["titulo"];
                        $fecha = $oferta[$i]["fecha"];
                        $logo = $oferta[$i]["logo"];  
                        $empresa = $oferta[$i]["empresa"];     
                        $portada = $oferta[$i]["portada"];
                        $descripcion = $oferta[$i]["descripcion"];
                        
                        $fechaNueva = fechas($fecha);
                        echo "

                        <div class='oferta-empresa'>
                            <h3>$titulo</h3>
                            <p>$empresa</p>
                            <i>$fechaNueva</i>
                        </div>
                        <div  class='oferta-logo'>
                            <img src='../../assets/img/empresa/logo/$logo' alt=''>
                        </div>
                        <div class='oferta-descripcion'>
                            <h4>DESCRIPCIÓN</h4>
                            <p>$descripcion</p>";

                            $comprobacion = $con->query("select count(id_categoria) cuanto from asociada,categoria where categoria.id = asociada.id_categoria and id_oferta = $id");
                            $i = 0;
                            while ($fila = $comprobacion->fetch_array(MYSQLI_ASSOC)) {
                                $cuanto = $fila['cuanto'];
                                
                                $i++;
                            }     
                            
                            if($cuanto == 0){
                                
                            }else{
                                echo "<div class='etiquetas'>";
                                $categoria = $con->query("select categoria.nombre nombre, categoria.id id from asociada,categoria where categoria.id = asociada.id_categoria and id_oferta = $id");
                                $i = 0;
                                while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                                    $oferta[$i]['nombre'] = $fila['nombre'];
                                    $oferta[$i]['id'] = $fila['id'];
                                    
                                    $i++;
                                }        
                
                                if(count($oferta) !== ""){
                                    for($i = 0; $i<count($oferta); $i++){
                                        $nombre = $oferta[$i]["nombre"];
                                        $id = $oferta[$i]["id"];
                                        if($nombre !== ""){
                                            echo "<a class='etiqueta' href='../empleo/empleoFiltroCategoria.php?categoria=$id'>$nombre</a>";
                                        }                         
                                    }
                                }
                                
                                echo "</div>";
                            }
                    
                        
                        if($_SESSION["tipo"] == "persona"){
                            echo"</div>
                            <form class='inscripcion' action='#' method='post'>
                                <input type='submit' name='inscribirme' value='INSCRIBIRME A ESTA OFERTA'>
                            </form> 
                            ";
                        }

                    }
                }else{
                    header('Refresh: 0; URL=../sesion/inicioSesion.php');
                }        
                
            ?>
            
        </section>
        <?php
            if(isset($_POST['inscribirme'])){
                $usuario = $_SESSION['nombre'];

                $idConsulta = "select id from usuario where usuario=?";
                $stmt = $con->prepare($idConsulta);
                $stmt->bind_param("s",$usuario);
                
                $stmt->execute();

                $stmt->bind_result($idUsuario);
                $stmt->fetch();
                $stmt->close();

                $verificacion = "select count(*) as cuanto from presenta where id_usuario=? and id_oferta=?";
                $stmt = $con->prepare($verificacion);
                $stmt->bind_param("ss",$idUsuario,$idOFerta);
                
                $stmt->execute();

                $stmt->bind_result($cuanto);
                $stmt->fetch();
                $stmt->close();

                if($cuanto == 0){
                    $insert = "INSERT INTO presenta (id_usuario, id_oferta, estado) VALUES ('$idUsuario','$idOFerta','abierta')";
                    $datos = $con->query($insert);
                    echo "      
                            <div class='correcto'>
                                <p><i class='fa-solid fa-triangle-exclamation'></i> Se ha presentado tu candidatura</p>
                            </div>"; 
                }else{
                    echo "      
                            <div class='correcto'>
                                <p><i class='fa-solid fa-triangle-exclamation'></i> Ya has presentado una solicitud a esta oferta</p>
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
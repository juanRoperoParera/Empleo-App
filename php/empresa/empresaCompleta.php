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

            $verificacion = "select portada from empresa where id=?";
            $stmt = $con->prepare($verificacion);
            $stmt->bind_param("s",$id);
            
            $stmt->execute();

            $stmt->bind_result($portada);
            $stmt->fetch();
            $stmt->close();
        ?>

    </header>
    <?php
        echo "<img class='portada' src='../../assets/img/empresa/portada/$portada' alt=''>"
    ?>
    <main>
        <section class="info-empresa">
            <?php
                $datos = $con->query("select * from empresa where id=$id");
                $i = 0;
                while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                    $empresa[$i]['nombre'] = $fila['nombre'];
                    $empresa[$i]['logo'] = $fila['logo'];
                    $empresa[$i]['portada'] = $fila['portada'];                     

                    $i++;
                }
                
                for($i = 0; $i<count($empresa); $i++){
                    $nombre = $empresa[$i]["nombre"];
                    $portada = $empresa[$i]["portada"];
                    $logo = $empresa[$i]["logo"];
                    
                    echo "
                    <h1>$nombre</h1>
                    <div class='logo'>
                        <img src='../../assets/img/empresa/logo/$logo' alt=''>
                    </div>
                    ";
    
                    
                }   
                echo "<div class='ofertas'>";
                $datos = $con->query("select count(*) num from oferta,empresa where oferta.id_empresa=empresa.id and empresa.id = $id and oferta.estado=1");
                $fila = $datos->fetch_array(MYSQLI_ASSOC);
                $numOfertas = $fila['num'];
                if($numOfertas > 0){
                    $datos = $con->query("select oferta.id id, oferta.estado activo, titulo, fecha, descripcion from oferta,empresa where oferta.id_empresa=empresa.id and empresa.id = $id");
                    $i = 0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['id'] = $fila['id'];
                        $oferta[$i]['titulo'] = $fila['titulo'];
                        $oferta[$i]['fecha'] = $fila['fecha'];                     
                        $oferta[$i]['descripcion'] = $fila['descripcion'];  
                        $oferta[$i]['activo'] = $fila['activo'];  
    
                        $i++;
                    }                
                    for($i = 0; $i<count($oferta); $i++){
                        $idOferta = $oferta[$i]["id"];
                        $titulo = $oferta[$i]["titulo"];
                        $fecha = $oferta[$i]["fecha"];
                        $descripcion = $oferta[$i]["descripcion"];
                        $descripcionCorta = substr($descripcion,0,105);
                        $activo = $oferta[$i]["activo"];

                        $fechaNueva = fechas($fecha);
                        
                        if($activo == 1){
                            echo "
                            <div class='card card-shadow' style='width: 18rem;'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$titulo</h5>
                                    <h6 class='card-subtitle mb-2 text-body-secondary'>$fechaNueva</h6>
                                    <p class='card-text'>$descripcionCorta...</p>
                                    <a class='boton' href='../oferta/ofertaCompleta.php?id=$idOferta'>VER MÁS</a>
                                </div>
                            </div>
                        ";
                        }
                    }
                }else{
                    echo "<p>No hay ofertas</p>";
                }
                echo "</div>";
            ?>

        </section>
        <div class="navegacion">
            <a href="../../index.php"><i class="fa-solid fa-arrow-left"></i> VOLVER</a>
        </div>
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
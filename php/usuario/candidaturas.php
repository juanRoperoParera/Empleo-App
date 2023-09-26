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
        <section class="candidaturas">
            <h3>MIS CANDIDATURAS</h3>
            <?php
                $usuario = $_SESSION['nombre'];

                $idConsulta = "select id from usuario where usuario=?";
                $stmt = $con->prepare($idConsulta);
                $stmt->bind_param("s",$usuario);
                
                $stmt->execute();

                $stmt->bind_result($idUsuario);
                $stmt->fetch();
                $stmt->close();

                $comprobacion = "select count(*) from presenta where id_usuario=?";
                $stmt = $con->prepare($comprobacion);
                $stmt->bind_param("s",$idUsuario);
                
                $stmt->execute();

                $stmt->bind_result($cuanto);
                $stmt->fetch();
                $stmt->close();
                
                if($cuanto !== 0){
                    $categoria = $con->query("select oferta.id idOferta, oferta.titulo titulo, presenta.estado estado, empresa.nombre nombre, empresa.logo logo from oferta, presenta, empresa where presenta.id_oferta = oferta.id and empresa.id = oferta.id_empresa and id_usuario = $idUsuario");
                    $i = 0;
                    while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['titulo'] = $fila['titulo'];
                        $oferta[$i]['estado'] = $fila['estado'];
                        $oferta[$i]['nombre'] = $fila['nombre'];
                        $oferta[$i]['logo'] = $fila['logo'];
                        $oferta[$i]['idOferta'] = $fila['idOferta'];
                        
                        $i++;
                    }        
    
                    if(count($oferta) !== ""){
                        for($i = 0; $i<count($oferta); $i++){
                            $titulo = $oferta[$i]["titulo"];
                            $estado = $oferta[$i]["estado"];
                            $nombre = $oferta[$i]["nombre"];
                            $logo = $oferta[$i]["logo"];
                            $idOferta = $oferta[$i]["idOferta"];
                            
                            echo "<div class='candidatura'>
                                    <div class='info'>
                                        <h4>$titulo</h4>
                                        <p>$nombre</p>
                                        <a class='' href='../oferta/ofertaCompleta.php?id=$idOferta'>VER MÁS</a>
                                    </div>
                                    <img src='../../assets/img/empresa/logo/$logo' alt=''>
                                    <div class='estado $estado'>
                                        <p>Estado de la candidatura: <b>$estado</b></p>
                                    </div>
                                </div>";
                        }
                    }
                }else{
                    echo "<p>No se ha presentado ninguna candidatura</p>";
                }
                
            ?>
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

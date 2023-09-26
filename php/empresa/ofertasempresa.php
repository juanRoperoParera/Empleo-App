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
            $cif=$_SESSION['nombre'];
        ?>
    </header>
    <main>
        <div class='limpiar'>
            <?php
                if(isset($_GET['idOfertaVerMas'])){
                    $idOfertaVerMas = $_GET['idOfertaVerMas'];
                    echo "
                    <a class='boton' href='limpiarCandidaturas.php?idOferta=$idOfertaVerMas'>LIMPIAR CANDIDATURAS <i class='fa-solid fa-broom'></i></a>";
                }
            ?>
        </div>
        <section class="candidaturasMain">        
            <section class="candidaturas empresa">
                <?php
                    $idConsulta = "select nombre,id from empresa where cif=?";
                    $stmt = $con->prepare($idConsulta);
                    $stmt->bind_param("s",$cif);
                    
                    $stmt->execute();

                    $stmt->bind_result($nombreEmpresa,$idEmpresa);
                    $stmt->fetch();
                    $stmt->close();

                    $nombreEmpresaMayus = strtoupper($nombreEmpresa);
                    echo "<h3>OFERTAS DE PUBLICADAS</h3>";
                ?>
                
                <?php

                    $comprobacion = "select count(*) from oferta where id_empresa=? and estado=1";
                    $stmt = $con->prepare($comprobacion);
                    $stmt->bind_param("s",$idEmpresa);
                    
                    $stmt->execute();

                    $stmt->bind_result($cuanto);
                    $stmt->fetch();
                    $stmt->close();
                    
                    if($cuanto !== 0){
                        $categoria = $con->query("select oferta.fecha fecha, oferta.estado activa, oferta.id idOferta, oferta.titulo titulo, empresa.nombre nombre, empresa.logo logo from oferta, empresa where empresa.id = oferta.id_empresa and id_empresa=$idEmpresa");
                        $i = 0;
                        while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                            $oferta[$i]['titulo'] = $fila['titulo'];
                            $oferta[$i]['logo'] = $fila['logo'];
                            $oferta[$i]['idOferta'] = $fila['idOferta'];
                            $oferta[$i]['fecha'] = $fila['fecha'];
                            $oferta[$i]['activa'] = $fila['activa'];
                            $i++;
                        }        
        
                        if(count($oferta) !== ""){
                            for($i = 0; $i<count($oferta); $i++){
                                $titulo = $oferta[$i]["titulo"];
                                $logo = $oferta[$i]["logo"];
                                $idOferta = $oferta[$i]["idOferta"];
                                $fecha = $oferta[$i]["fecha"];
                                $activa = $oferta[$i]["activa"];
                                
                                if($activa == 1){
                                    echo "<div class='candidatura'>
                                        <div class='info'>
                                            <h4>$titulo</h4>
                                            <a class='' href='../oferta/ofertaCompleta.php?id=$idOferta'>VER MÁS</a>
                                        </div>
                                        <img src='../../assets/img/empresa/logo/$logo' alt=''>
                                        <div class='opciones'>
                                            <a class='ver' href='ofertasempresa.php?idOfertaVerMas=$idOferta'>Seleccionar</a>
                                            <a class='rechazar' href='eliminarOferta.php?idOferta=$idOferta'>Eliminar</a>
                                        </div>
                                    </div>";
                                }
                                
                            }
                        }
                    }else{
                        echo "<p>No se ha presentado ninguna oferta</p>";
                    }
                    
                ?>
            </section>
            <section class="candidaturas empresa">
                <?php
                    $idConsulta = "select nombre,id from empresa where cif=?";
                    $stmt = $con->prepare($idConsulta);
                    $stmt->bind_param("s",$cif);
                    
                    $stmt->execute();

                    $stmt->bind_result($nombreEmpresa,$idEmpresa);
                    $stmt->fetch();
                    $stmt->close();

                    $nombreEmpresaMayus = strtoupper($nombreEmpresa);
                    echo "<h3>CANDIDATURAS RECIBIDAS</h3>";
                ?>
                
                <?php

                    if(isset($_GET['idOfertaVerMas'])){
                        $idOfertaVerMas = $_GET['idOfertaVerMas'];
                        $comprobacion = "select count(*) from oferta,presenta where presenta.id_oferta=oferta.id and id_empresa=? and oferta.id=? and oferta.estado=1";
                        $stmt = $con->prepare($comprobacion);
                        $stmt->bind_param("ss",$idEmpresa,$idOfertaVerMas);
                        
                        $stmt->execute();
        
                        $stmt->bind_result($cuanto);
                        $stmt->fetch();
                        $stmt->close();
                        
                        if($cuanto !== 0){
                            $categoria = $con->query("select presenta.id_usuario usuario, oferta.id idOferta, oferta.titulo titulo, presenta.estado estado, empresa.nombre nombre, empresa.logo logo from oferta, presenta, empresa where oferta.estado=1 and presenta.id_oferta = oferta.id and empresa.id = oferta.id_empresa and empresa.id=$idEmpresa and oferta.id=$idOfertaVerMas");
                            $i = 0;
                            $contador = 0;
                            while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                                $oferta[$i]['titulo'] = $fila['titulo'];
                                $oferta[$i]['estado'] = $fila['estado'];
                                $oferta[$i]['nombre'] = $fila['nombre'];
                                $oferta[$i]['logo'] = $fila['logo'];
                                $oferta[$i]['idOferta'] = $fila['idOferta'];
                                $oferta[$i]['usuario'] = $fila['usuario'];
                                
                                $i++;
                                $contador++;
                            }        
                            if(count($oferta) !== ""){
                                for($i = 0; $i<$contador; $i++){
                                    $titulo = $oferta[$i]["titulo"];
                                    $estado = $oferta[$i]["estado"];
                                    $nombre = $oferta[$i]["nombre"];
                                    $logo = $oferta[$i]["logo"];
                                    $idOferta = $oferta[$i]["idOferta"];
                                    $usuario = $oferta[$i]["usuario"];

                                    $verificacion = "select nombre from usuario where id=?";
                                    $stmt = $con->prepare($verificacion);
                                    $stmt->bind_param("s",$usuario);
                                    
                                    $stmt->execute();
                
                                    $stmt->bind_result($nombreUsuario);
                                    $stmt->fetch();
                                    $stmt->close();
                                    
                                    echo "<div class='candidatura'>
                                            <div class='info'>
                                                <h4>$titulo</h4>
                                                <a class=''>$nombreUsuario</a>
                                            </div>
                                            <img src='../../assets/img/empresa/logo/$logo' alt=''>";
                                            if($estado == "abierta"){
                                                echo "<div class='opciones'>
                                                        <a class='aceptar' href='estadoCandidatura.php?estado=aceptada&idOferta=$idOferta&usuario=$usuario&puesto=$titulo&empresa=$idEmpresa'>ACEPTAR</a>
                                                        <a class='rechazar' href='estadoCandidatura.php?estado=rechazada&idOferta=$idOferta&usuario=$usuario'>RECHAZAR</a>
                                                    </div>
                                                ";
                                            }else{
                                                if($estado == "rechazada"){
                                                    echo "<div class='estado $estado'>
                                                            <p>Estado de la candidatura: <b>$estado</b></p>
                                                        </div>
                                                    ";
                                                }else{
                                                    echo "<div class='estado $estado'>
                                                            <p>Estado de la candidatura: <b>$estado</b></p>
                                                        </div>
                                                    ";
                                                }
                                                
                                            }
                                            
                                        echo "</div>";
                                }
                            }
                        }else{
                            echo "<p>No se ha presentado ninguna candidatura a esta oferta</p>";
                        }
                    }else{
                        echo "<p>Selecciona una oferta</p>";
                    }
                    
                    
                ?>
            </section>
            
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


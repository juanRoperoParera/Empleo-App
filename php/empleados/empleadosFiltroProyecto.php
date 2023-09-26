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

            $proyectoBuscado = $_GET['proyecto'];
        ?>
    </header>
    <main class="sinmargen">
        <img class='portadaEmpleo' src='../../assets/img/empleados/empleados-background.jpg' alt=''>
        <section class="empleo">
            <div class="buscador">
                <form action="#" method="post" autocomplete="off">
                    <input type="text" name="busqueda" placeholder="Nombre del trabajador ">
                    <button type="submit" name="buscar"><i class='fa-solid fa-magnifying-glass'></i></button>                    
                </form>
            </div>
            <div class="filtros">
                <ul>
                    <?php
                        if($proyectoBuscado == "null"){
                            echo "<li class='buscada'><a class='etiqueta' href='empleadosFiltroProyecto.php?proyecto=null'>Sin asignar</a></li>";
                        }else{
                            echo "<li><a class='etiqueta' href='empleadosFiltroProyecto.php?proyecto=null'>Sin asignar</a></li>";   
                        }
                    ?>
                
                    <?php
                        $cif=$_SESSION['nombre'];
                        $idConsulta = "select id from empresa where cif=?";
                        $stmt = $con->prepare($idConsulta);
                        $stmt->bind_param("s",$cif);
                        
                        $stmt->execute();

                        $stmt->bind_result($idEmpresa);
                        $stmt->fetch();
                        $stmt->close();

                        $comprobacion = "select count(*) from proyecto where id_empresa=? and f_fin is null";
                        $stmt = $con->prepare($comprobacion);
                        $stmt->bind_param("s",$idEmpresa);
                        
                        $stmt->execute();

                        $stmt->bind_result($cuanto);
                        $stmt->fetch();
                        $stmt->close();


                        if($cuanto !== 0){
                            $categoria = $con->query("select nombre,id from proyecto where id_empresa=$idEmpresa and f_fin is null");
                            $i = 0;
                            while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                                $oferta[$i]['nombre'] = $fila['nombre'];
                                $oferta[$i]['id'] = $fila['id'];
                                
                                $i++;
                            }        
            
                            if(count($oferta) !== ""){
                                for($i = 0; $i<count($oferta); $i++){
                                    $nombre = $oferta[$i]["nombre"];
                                    $idProyecto = $oferta[$i]["id"];
                                    if($nombre !== ""){
                                        if($proyectoBuscado !== "null"){
                                            if($idProyecto == $proyectoBuscado){
                                                echo "<li class='buscada'><a class='etiqueta' href='empleadosFiltroProyecto.php?proyecto=$idProyecto'>$nombre</a></li>";
                                            }else{
                                                echo "<li><a class='etiqueta' href='empleadosFiltroProyecto.php?proyecto=$idProyecto'>$nombre</a></li>";
                                            }
                                        }else{
                                            echo "<li><a class='etiqueta' href='empleadosFiltroProyecto.php?proyecto=$idProyecto'>$nombre</a></li>";
                                        }
                                        
                                        
                                    }                         
                                }
                            }
                        }
                    ?>
                </ul>
                <a class="eliminar" href="empleados.php"> <i class="fa-solid fa-square-xmark"></i> QUITAR FILTRO</a>
            </div>
            <div class="resultados">
            <?php
                    if(isset($_POST['buscar'])){
                        $busqueda = $_POST['busqueda'];
                        $busquedaFormat = "%".$busqueda."%";
                        
                        if($proyectoBuscado == "null"){
                            $datos = $con->query("select * from usuario where id in (select id_usuario from trabaja where id_empresa=$idEmpresa GROUP BY id_usuario having count(id_usuario) = 1) and nombre like '$busquedaFormat'");
                        }else{
                            $datos = $con->query("select * from usuario where id in (select id_usuario from trabaja where id_empresa=$idEmpresa and id_proyecto=$proyectoBuscado) and nombre like '$busquedaFormat'");
                        }
                        
                    }else{
                        if($proyectoBuscado == "null"){
                            $datos = $con->query("select * from usuario where id in (select id_usuario from trabaja where id_empresa=$idEmpresa GROUP BY id_usuario having count(id_usuario) = 1)");
                        }else{
                            $datos = $con->query("select * from usuario where id in (select id_usuario from trabaja where id_empresa=$idEmpresa and id_proyecto=$proyectoBuscado)");
                        }
                        
        
                    }

                    $i = 0;
                    $contador = 0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['id'] = $fila['id'];                        
                        $oferta[$i]['nombre'] = $fila['nombre'];  
                        $oferta[$i]['f_nac'] = $fila['f_nac'];   
                        $oferta[$i]['estado'] = $fila['estado'];  
                        
                        $contador++;

                        $i++;
                    }      
                    
                    if($contador == 0){
                        echo "<p class='sinresultados'>Sin resultados</p>";
                    }else{
                        for($i = 0; $i<$contador; $i++){
                            $id = $oferta[$i]["id"];
                            $nombre = $oferta[$i]["nombre"];
                            $f_nac = $oferta[$i]["f_nac"];
                            $estado = $oferta[$i]["estado"];
                             
                            $verificacion = "select puesto from trabaja where id_proyecto is NULL and id_usuario = ? and id_empresa=?";
                            $stmt = $con->prepare($verificacion);
                            $stmt->bind_param("ss",$id,$idEmpresa);
                            
                            $stmt->execute();
        
                            $stmt->bind_result($puesto);
                            $stmt->fetch();
                            $stmt->close();
    
                            $fecha_actual = time(); 
                            $f_nac_timestamp = strtotime($f_nac);
                            $diferencia = $fecha_actual - $f_nac_timestamp;

                            $segundos_por_año = 31536000;
                            $edad = $diferencia / $segundos_por_año;
                            $edadTruncada = round($edad, 0);
                            echo "
                                    <div class='card card-shadow' style='width: 18rem;'>       
                                        <div class='card-body'>
                                        <h5 class='card-title'>$nombre</h5>
                                        <p class='card-text'>$edadTruncada años</p>
                                        <i>$puesto</i>
                                        </div>
                                        <div class='card-body enlace'>
                                            <a class='boton' href='../proyecto/usuarioAñadir.php?id=$id'>AÑADIR</a>
                                        </div>
                                    </div>
                                    ";
                        }
                    }
                    
                ?>
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
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
        <section class="info-oferta card-shadow">
            <?php
                if(isset($_SESSION["tipo"])){
                    $idUsuario = $_GET['id'];

                    $idConsulta = "select usuario from usuario where id=?";
                    $stmt = $con->prepare($idConsulta);
                    $stmt->bind_param("s",$idUsuario);
                    
                    $stmt->execute();
    
                    $stmt->bind_result($nombreUsuario);
                    $stmt->fetch();
                    $stmt->close();

                    $nombreUsuarioCAP = strtoupper($nombreUsuario);
                    echo "

                    <div class='titulo'>
                        <h3>TAREAS DE $nombreUsuarioCAP </h3>
                        </div>";

                    $idProyecto = $_GET['proyecto'];

                    $datos = $con->query("select * from tarea where id_usuario=$idUsuario and id_proyecto=$idProyecto and completada=0 order by importancia asc");
                    $i = 0;
                    $contador = 0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['id'] = $fila['id'];                  
                        $oferta[$i]['descripcion'] = $fila['descripcion'];  
                        $oferta[$i]['importancia'] = $fila['importancia'];  
                        $oferta[$i]['duracion'] = $fila['duracion'];  
                        $oferta[$i]['completada'] = $fila['completada'];  
    
                        $i++;
                        $contador++;
                    }       
                    
                    if($contador == 0){
                        echo "<p>No hay ninguna tarea asignada</p>";
                    }else{
                        for($i = 0; $i<$contador; $i++){
                            $id = $oferta[$i]["id"];
                            $descripcion = $oferta[$i]["descripcion"];
                            $importancia = $oferta[$i]["importancia"];
                            $duracion = $oferta[$i]["duracion"];
                            $completada = $oferta[$i]["completada"];
                            
                            if($importancia == "Menor"){
                                $importanciaCambio = "Baja";
                            }else{
                                $importanciaCambio = $importancia;
                            }

                            echo "<div class='tarea $importancia'>
                                <p>$descripcion</p>
                                <div class='tarea-info'>
                                    <p><b>Duración: </b>$duracion horas | <b>Importancia: </b>$importanciaCambio</p>
                                </div>
                                <a class='eliminar' href='../usuario/borrarTarea.php?id=$id&idUsuario=$idUsuario&idProyecto=$idProyecto'>Eliminar</a>
                            </div>";
                           
                        }
                    }
                    
                    echo "<a class='boton' href='../usuario/asignarTarea.php?idUsuario=$idUsuario&idProyecto=$idProyecto'>ASIGNAR TAREA</a>";
                }else{
                    header('Refresh: 0; URL=../sesion/inicioSesion.php');
                }        
                
            ?>
            
        </section>
        <div class="navegacion">
                    <?php
                        echo "<a href='../usuario/proyectos.php?proyecto=$idProyecto'><i class='fa-solid fa-arrow-left'></i> Proyectos</a>";
                    ?>
                    
                </div>
        <?php
           
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
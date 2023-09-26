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
        <section class="empleo">         
            <div class="filtros">
                <ul>
                    <?php
                        $usuario=$_SESSION['nombre'];
                        $idConsulta = "select id from usuario where usuario=?";
                        $stmt = $con->prepare($idConsulta);
                        $stmt->bind_param("s",$usuario);
                        
                        $stmt->execute();

                        $stmt->bind_result($idUsuario);
                        $stmt->fetch();
                        $stmt->close();

                        $comprobacion = "select count(*) from trabaja where id_usuario=?  and id_proyecto in (select id from proyecto where f_fin is null)";
                        $stmt = $con->prepare($comprobacion);
                        $stmt->bind_param("s",$idUsuario);
                        
                        $stmt->execute();

                        $stmt->bind_result($cuanto);
                        $stmt->fetch();
                        $stmt->close();

                        
                        if($cuanto !== 0){
                            $categoria = $con->query("select id,nombre from proyecto where id in(select id_proyecto from trabaja where id_usuario=$idUsuario  and id_proyecto in (select id from proyecto where f_fin is null))");
                            $i = 0;
                            while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                                $oferta[$i]['id'] = $fila['id'];
                                $oferta[$i]['nombre'] = $fila['nombre'];
                                
                                $i++;
                            }        
            
                            if(count($oferta) !== ""){

                                for($i = 0; $i<count($oferta); $i++){
                                    $idProyecto = $oferta[$i]["id"];
                                    $nombre = $oferta[$i]["nombre"];

                                    if(isset($_GET['proyecto'])){
                                        $proyecto = $_GET['proyecto'];
                                        if($idProyecto == $proyecto){
                                            echo "<li class='buscada'><a class='etiqueta' href='proyectos.php?proyecto=$idProyecto'>$nombre</a></li>";
                                        }else{
                                            echo "<li><a class='etiqueta' href='proyectos.php?proyecto=$idProyecto'>$nombre</a></li>";
                                        }
                                    }else{
                                        echo "<li><a class='etiqueta' href='proyectos.php?proyecto=$idProyecto'>$nombre</a></li>";
                                    }

                                    
                                                       
                                }
                            }
                        }
                    ?>                
             </ul>
            </div>
            <div class="resultados">
            <?php
                    if($cuanto !== 0){
                        if(isset($_GET['proyecto'])){
                            $proyecto = $_GET['proyecto'];

                            $idConsulta = "select puesto from trabaja where id_usuario=? and id_proyecto=?";
                            $stmt = $con->prepare($idConsulta);
                            $stmt->bind_param("ss",$idUsuario,$proyecto);
                            
                            $stmt->execute();

                            $stmt->bind_result($puesto);
                            $stmt->fetch();
                            $stmt->close();

                            if($puesto == "Project leader"){
                                echo "<a class='boton limpiarTareas chatenlace' href='../usuario/chat.php?idUsuario=$idUsuario&idProyecto=$proyecto'>CHAT PROYECTO</a>";
                                echo "<h3>Trabajadores</h3>";
                                $categoria = $con->query("select * from usuario where id in(select id_usuario from trabaja where id_proyecto=$proyecto and puesto != 'Project leader')");

                                $i = 0;
                                $contador=0;
                                while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                                    $oferta[$i]['id'] = $fila['id'];
                                    $oferta[$i]['nombre'] = $fila['nombre'];
    
                                    
                                    $i++;
                                    $contador++;
                                }       
                                if($contador==0){
                                    echo "<p class='sinresultados'>Todavía no hay trabajadores asignados</p>";  
                                }else{
                                    for($i = 0; $i<$contador; $i++){ 
                                        $idUsuario = $oferta[$i]["id"];                             
                                        $nombre = $oferta[$i]["nombre"];

                                        $verificacion = "select puesto from trabaja where id_proyecto=? and id_usuario = ?";
                                        $stmt = $con->prepare($verificacion);
                                        $stmt->bind_param("ss",$proyecto,$idUsuario);
                                        
                                        $stmt->execute();
                    
                                        $stmt->bind_result($puesto);
                                        $stmt->fetch();
                                        $stmt->close();
                                        echo "
                                            <div class='card card-shadow' style='width: 18rem;'>       
                                                <div class='card-body'>
                                                <h5 class='card-title'>$nombre</h5>
                                                <i>$puesto</i>
                                                </div>
                                                <div class='card-body enlace'>
                                                    <a class='boton' href='../usuario/verTarea.php?id=$idUsuario&proyecto=$proyecto'>VER TAREAS</a>
                                                </div>
                                            </div>
                                            ";
                                                            
                                    }
            
                                }
                            }else{
                                echo "<a class='boton limpiarTareas chatenlace' href='../usuario/chat.php?idUsuario=$idUsuario&idProyecto=$proyecto'>CHAT PROYECTO</a>";
                                echo "<h3>Tareas</h3>";
                                $categoria = $con->query("select * from tarea where id_usuario=$idUsuario and id_proyecto=$proyecto order by importancia asc");

                                $i = 0;
                                $contador=0;
                                $contadorCompletada=0;
                                while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                                    $oferta[$i]['id'] = $fila['id'];                  
                                    $oferta[$i]['descripcion'] = $fila['descripcion'];  
                                    $oferta[$i]['importancia'] = $fila['importancia'];  
                                    $oferta[$i]['duracion'] = $fila['duracion'];  
                                    $oferta[$i]['completada'] = $fila['completada'];  
    
                                    if($oferta[$i]['completada'] == 1){
                                        $contadorCompletada++;
                                    }
                                    $i++;
                                    $contador++;
                                }        
    
                                if($contador==0){
                                    echo "<p class='sinresultados'>No tienes ninguna tarea asignada</p>";  
                                }else{
                                    if($contadorCompletada != 0){
                                        echo "
                                        <a class='boton limpiarTareas' href='../usuario/limpiarTareas.php?idUsuario=$idUsuario&idProyecto=$proyecto'>LIMPIAR TAREAS <i class='fa-solid fa-broom'></i></a>";
                                    }                
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
                                        
                                        if($completada==1){
                                            $fondo = "verde";
                                        }else{
                                            $fondo = "normal";
                                        }
                                        echo "<div class='tarea-trabajador $importancia $fondo'>
                                            <p>$descripcion</p>
                                            <div class='tarea-info'>
                                                <p><b>Duración: </b>$duracion horas | <b>Importancia: </b>$importanciaCambio</p>
                                            </div>";
                                            if($completada == 0){
                                                echo "<a class='completada' href='../usuario/completarTarea.php?id=$id&idProyecto=$proyecto'>Marcar como completada</a>";
                                            }
                                            
                                        echo "</div>";
                                                            
                                    }
            
                                }
                            }
                            
                        }else{
                            echo "<p class='sinresultados'>Selecciona un proyecto</p>";  
                        }
                    }else{
                        echo "<p class='sinresultados'>No estas en ningun proyecto activo</p>";
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
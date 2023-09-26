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
        ?>

    </header>
    <main>
        <section class="info-oferta card-shadow">
            <?php
                if(isset($_SESSION["tipo"])){
                    $datos = $con->query("select * from proyecto where id=$id");
                    $i = 0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['id'] = $fila['id'];
                        $oferta[$i]['nombre'] = $fila['nombre'];                   
                        $oferta[$i]['descripcion'] = $fila['descripcion'];  
                        $oferta[$i]['f_inicio'] = $fila['f_inicio'];  
                        $oferta[$i]['f_fin'] = $fila['f_fin'];  
                        $oferta[$i]['estado'] = $fila['estado'];  
    
                        $i++;
                    }                               
                    for($i = 0; $i<count($oferta); $i++){
                        $idOferta = $oferta[$i]["id"];
                        $titulo = $oferta[$i]["nombre"];
                        $estado = $oferta[$i]["estado"];
                        $f_inicio = $oferta[$i]["f_inicio"];
                        $f_fin = $oferta[$i]["f_fin"];

                        $descripcion = $oferta[$i]["descripcion"];
                        $descripcionCorta = substr($descripcion,0,105);

                        $fechaNueva = fechas($f_inicio);
                        
                        echo "

                        <div class='oferta-empresa'>
                            <h3>$titulo</h3>";
                        if($f_fin !== null){
                            $fechaFin = fechas($f_fin);
                            echo "<i>$fechaNueva - $fechaFin</i>";
                        }else{
                            echo "<i>$fechaNueva</i>";
                        }
                            
                        echo "</div>
                        <div  class='oferta-logo'>
                           
                        </div>
                        <div class='oferta-descripcion'>
                            <h4>DESCRIPCIÓN</h4>
                            <p>$descripcion</p>         
                        </div>";

                        

                        $datosTrabajador = $con->query("select usuario.id id, usuario.nombre nombre, trabaja.puesto puesto from trabaja, usuario where trabaja.id_usuario = usuario.id and id_proyecto=$id");
                        $contador = 0;
                        $i=0;
                        while ($filaTrabajador = $datosTrabajador->fetch_array(MYSQLI_ASSOC)) {
                            $trabajador[$i]['id'] = $filaTrabajador['id'];
                            $trabajador[$i]['nombre'] = $filaTrabajador['nombre'];                   
                            $trabajador[$i]['puesto'] = $filaTrabajador['puesto'];  

                            $i++;
                            $contador++;
                        }

                        if($contador!==0){
                            echo " <div class='oferta-descripcion'>
                        <br>
                        <h4>EQUIPO</h4>       
                    </div>";
                            for($i = 0; $i<$contador; $i++){      
                                $idUsuario = $trabajador[$i]["id"];
                                $nombre = $trabajador[$i]["nombre"];
                                $puesto = $trabajador[$i]["puesto"];

                                echo "<div class='trabajadorProyecto'>
                                    <h4>$nombre</h4>
                                    <p>$puesto</p>
                                    <a class='eliminar' href='proyectoUsuarioEliminar.php?id=$idUsuario&proyecto=$id'>Eliminar</a>
                                </div>";
      
                            }
                        }
                       
                    }
                }else{
                    header('Refresh: 0; URL=../sesion/inicioSesion.php');
                }        
                
            ?>
            
        </section>
        <div class="navegacion">
                    <a href="../empresa/proyectosEmpresa.php"><i class="fa-solid fa-arrow-left"></i> Proyectos</a>
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
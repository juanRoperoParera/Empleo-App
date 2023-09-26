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
        <div class="contenedor">
            <h1>Selecciona un proyecto:</h1>
            <section class="intereses">
                <form action="#" method="POST">
                    <div class="contenido">
                    <?php
                        $idUsuario = $_GET['id'];
                        $cif=$_SESSION['nombre'];

                        $idConsulta = "select id from empresa where cif=?";
                        $stmt = $con->prepare($idConsulta);
                        $stmt->bind_param("s",$cif);
                        
                        $stmt->execute();

                        $stmt->bind_result($idEmpresa);
                        $stmt->fetch();
                        $stmt->close();

                        $intereses = $con->query("select id from proyecto where id in(select id_proyecto from trabaja where id_usuario=$idUsuario and id_empresa=$idEmpresa and id_proyecto in(select id from proyecto where f_fin is null and estado=1))");
                        $i = 0;
                        $oferta = array();
                        while ($fila = $intereses->fetch_array(MYSQLI_ASSOC)) {
                            $oferta[$i]['id'] = $fila['id'];
                            
                            $i++;
                        }        
        
                        $arrayIntereses = array();
                        if(count($oferta) !== ""){
                            for($i = 0; $i<count($oferta); $i++){
                                $categoria = $oferta[$i]["id"];      
                                
                                array_push($arrayIntereses, $categoria);
                            }
                        }

                        $categoria = $con->query("select nombre,id from proyecto where f_fin is null and estado=1");
                        $i = 0;
                        $contador=0;
                        while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                            $oferta[$i]['nombre'] = $fila['nombre'];
                            $oferta[$i]['id'] = $fila['id'];

                            $i++;
                            $contador++;
                        }        
                        if($contador > 0){
                            for($i = 0; $i<$contador; $i++){
                                $nombre = $oferta[$i]["nombre"];
                                $id = $oferta[$i]["id"];
                                if($nombre !== ""){
                                    if(in_array($id, $arrayIntereses)){
                                        echo "<div class='interes añadido'>";
                                        echo "<p class='añadido'>$nombre</p>";
                                        echo "</div>";
                                    }else{
                                        echo "<div class='interes'>";
                                        echo "<input type='checkbox' name='intereses[]' value='$id'><p>$nombre</p>";
                                        echo "</div>";
                                    }

                                }                         
                            }
                            echo "
                            </div>
                                <div class='checkbox'>
                                    <input type='checkbox' name='leader'>Añadir como project leader
                                    <input type='submit' name='APLICAR' value='AÑADIR'>
                                </div>
                                
                                
                            
                            ";
                        }else{
                            echo "<p>No hay ningun proyecto disponible</p>";
                        }
                    ?>
                    </form>
                <br>
                <div class="navegacion">
                    <a href="../empleados/empleados.php"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                </div>

                <?php
                    if(isset($_POST['APLICAR'])){
                        $leader = $_POST['leader'];
                        if(isset($_POST['intereses'])){
                            foreach ($_POST['intereses'] as $interes) {
                                $comprobacion = "select count(*) from trabaja where id_usuario=? and id_proyecto=?";
                                $stmt = $con->prepare($comprobacion);
                                $stmt->bind_param("ss",$idUsuario, $interes);
                                
                                $stmt->execute();
            
                                $stmt->bind_result($numFilas);
                                $stmt->fetch();
                                $stmt->close();

                                $verificacion = "select puesto from trabaja where id_proyecto is NULL and id_usuario = ? and id_empresa=?";
                                $stmt = $con->prepare($verificacion);
                                $stmt->bind_param("ss",$idUsuario,$idEmpresa);
                                
                                $stmt->execute();
            
                                $stmt->bind_result($puesto);
                                $stmt->fetch();
                                $stmt->close();

                                if(isset($leader)){
                                    $puesto="Project leader";
                                }

                                if($numFilas == 0){
                                    $insert = "INSERT INTO trabaja (id_usuario, id_proyecto, id_empresa, puesto) VALUES ( '$idUsuario', '$interes', '$idEmpresa', '$puesto')";
                                    $datos = $con->query($insert); 
                                }
                                

                            }
                            header("Refresh:0; url=#");
                        }
                        
                    }
                ?>
            </section>
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
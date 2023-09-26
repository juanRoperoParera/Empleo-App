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
    <main class="sinmargen">
        <img class='portadaEmpleo' src='../../assets/img/empleo/empleo-background.jpg' alt=''>
        <section class="empleo">
            <div class="buscador">
                <form action="#" method="post" autocomplete="off">
                    <input type="text" name="busqueda" placeholder="Búsqueda por puesto ">
                    <button type="submit" name="buscar"><i class='fa-solid fa-magnifying-glass'></i></button>                    
                </form>
            </div>
            <div class="filtros">
                <ul>
                    <?php
                        $categoria = $con->query("select distinct categoria.nombre nombre,categoria.id id from asociada,categoria where categoria.id = asociada.id_categoria");
                        $i = 0;
                        while ($fila = $categoria->fetch_array(MYSQLI_ASSOC)) {
                            $oferta[$i]['nombre'] = $fila['nombre'];
                            $oferta[$i]['id'] = $fila['id'];
                            
                            $i++;
                        }        
        
                        if(count($oferta) !== ""){
                            for($i = 0; $i<count($oferta); $i++){
                                $nombre = $oferta[$i]["nombre"];
                                $idCategoria = $oferta[$i]["id"];
                                if($nombre !== ""){
                                    echo "<li><a class='etiqueta' href='empleoFiltroCategoria.php?categoria=$idCategoria'>$nombre</a></li>";
                                }                         
                            }
                        }
                    ?>
                </ul>
            </div>
            <div class="resultados">
                <?php
                    if(isset($_POST['buscar'])){
                        $busqueda = $_POST['busqueda'];
                        $busquedaFormat = "%".$busqueda."%";
                        $datos = $con->query("select oferta.id id, titulo, fecha, empresa.nombre empresa, empresa.logo logo, asociada.id_categoria categoria from oferta,empresa,asociada where oferta.estado=1 and oferta.id_empresa=empresa.id and oferta.id = asociada.id_oferta and oferta.titulo like '$busquedaFormat' order by fecha desc");
                    }else{
                        $datos = $con->query("select oferta.id id, titulo, fecha, empresa.nombre empresa, empresa.logo logo, asociada.id_categoria categoria from oferta,empresa,asociada where oferta.estado=1 and  oferta.id_empresa=empresa.id and oferta.id = asociada.id_oferta order by fecha desc");
                    }

                    $i = 0;
                    $contador = 0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['id'] = $fila['id'];
                        $oferta[$i]['titulo'] = $fila['titulo'];
                        $oferta[$i]['fecha'] = $fila['fecha'];
                        $oferta[$i]['logo'] = $fila['logo'];                     
                        $oferta[$i]['empresa'] = $fila['empresa'];  
                        $oferta[$i]['categoria'] = $fila['categoria'];      
                        
                        $contador++;

                        $i++;
                    }      
                    
                    if($contador == 0){
                        echo "<p class='sinresultados'>Sin resultados</p>";
                    }else{
                        for($i = 0; $i<$contador; $i++){
                            $id = $oferta[$i]["id"];
                            $titulo = $oferta[$i]["titulo"];
                            $fecha = $oferta[$i]["fecha"];
                            $logo = $oferta[$i]["logo"];  
                            $empresa = $oferta[$i]["empresa"];   
                            $categoria = $oferta[$i]["categoria"];    
    
                            $fechaNueva = fechas($fecha);
                            echo "
                            <div class='card card-shadow' style='width: 18rem;'>       
                                <div class='card-body'>
                                <img src='../../assets/img/empresa/logo/$logo' class='card-img-top' alt='...'>
                                    <h5 class='card-title'>$titulo</h5>
                                    <p class='card-text'>$empresa</p>
                                    <i>$fechaNueva</i>
                                </div>
                                <div class='card-body enlace'>
                                    <a class='boton' href='../oferta/ofertaCompleta.php?id=$id'>VER MÁS</a>
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
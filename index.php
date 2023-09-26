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
    <link rel="stylesheet" href="assets/estilo.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="assets/img/logo.png">
</head>

<body>
    <header>
        <?php
            require_once("bd/bd.php");
            require_once("php/funciones.php");
            $con = conectar::conexion();
            navIndex();
        ?>
    </header>
    <div class="slider">
        <div class="slide-track">
            <?php
                $datos = $con->query("select * from oferta order by fecha desc");
                $i = 0;
                while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                    $oferta[$i]['titulo'] = $fila['titulo'];

                    $i++;
                }

                for($j=0; $j<2; $j++){
                    for($i = 0; $i<count($oferta); $i++){
                        $titulo = $oferta[$i]["titulo"];
     
                        echo "
                            <div class='slide'>
                                <p>$titulo</p>
                            </div>
                        ";
                    }
                }
                
            ?>

        </div>
    </div>
    <main class="navIndex">
        <section class="carrousel">
            <div class="container-sm">
                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                            $datos = $con->query("select * from empresa ORDER BY RAND()");
                            $i = 0;
                            while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                                $empresa[$i]['id'] = $fila['id'];
                                $empresa[$i]['nombre'] = $fila['nombre'];
                                $empresa[$i]['logo'] = $fila['logo'];
                                $empresa[$i]['portada'] = $fila['portada'];                     

                                $i++;
                            }
                            
                            $contador = 0;
                            for($i = 0; $i<3; $i++){
                                $id = $empresa[$i]["id"];
                                $nombre = $empresa[$i]["nombre"];
                                $portada = $empresa[$i]["portada"];

                                if($portada == null){

                                }else{
                                    if($contador == 0){
                                        echo "
                                        <div class='carousel-item active' data-bs-interval='5000'>
                                            <img src='assets/img/empresa/portada/$portada' class='d-block w-100' alt='...'>
                                            <div class='carousel-caption d-none d-md-block'>
                                                <h5>$nombre</h5>
                                                <a class='boton' href='php/empresa/empresaCompleta.php?id=$id'>VER MÁS</a>
                                            </div>
                                        </div>
                                    ";
                                        $contador++;
                                    }else{
                                        echo "
                                        <div class='carousel-item' data-bs-interval='5000'>
                                            <img src='assets/img/empresa/portada/$portada' class='d-block w-100' alt='...'>
                                            <div class='carousel-caption d-none d-md-block'>
                                                <h5>$nombre</h5>
                                                <a class='boton' href='php/empresa/empresaCompleta.php?id=$id'>VER MÁS</a>
                                            </div>
                                        </div>
                                        ";
                                    }
                                }
                                
                            }   
                        ?>

                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>
        <section class='ultimas-ofertas'>
            <div class='container-sm'>
                <?php
                if(isset($_SESSION['tipo']) and $_SESSION['tipo']=="persona"){
                    echo "
                            <h3>OPORTUNIDADES DE EMPLEO PARA TI</h3>
                            <div class='container ofertas'>";

                    $usuario = $_SESSION['nombre'];

                    $id = "select id from usuario where usuario=?";
                    $stmt = $con->prepare($id);
                    $stmt->bind_param("s",$usuario);
                    
                    $stmt->execute();

                    $stmt->bind_result($idUsuario);
                    $stmt->fetch();
                    $stmt->close();       

                    $comprobacion = "select count(*) from interesa where id_usuario=?";
                    $stmt = $con->prepare($comprobacion);
                    $stmt->bind_param("s",$idUsuario);
                    
                    $stmt->execute();

                    $stmt->bind_result($numIntereses);
                    $stmt->fetch();
                    $stmt->close();

                    if($numIntereses !== 0){
                        $datos = $con->query("select id_categoria id from interesa where id_usuario=$idUsuario");
                        $categorias=array();
                        $i = 0;
                        while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                            $oferta[$i]['id'] = $fila['id'];

                            array_push($categorias,$oferta[$i]['id']);
                            $i++;
                        } 
                        
                    }

                    $datos = $con->query("select oferta.id id, titulo, fecha, empresa.nombre empresa, empresa.logo logo, asociada.id_categoria categoria from oferta,empresa,asociada where oferta.id_empresa=empresa.id and oferta.id = asociada.id_oferta order by fecha desc limit 6");
                    
                    $i = 0;
                    $contador=0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['id'] = $fila['id'];
                        $oferta[$i]['titulo'] = $fila['titulo'];
                        $oferta[$i]['fecha'] = $fila['fecha'];
                        $oferta[$i]['logo'] = $fila['logo'];                     
                        $oferta[$i]['empresa'] = $fila['empresa'];  
                        $oferta[$i]['categoria'] = $fila['categoria'];  

                        $i++;
                        $contador++;
                    }                
                    for($i = 0; $i<$contador; $i++){
                        $id = $oferta[$i]["id"];
                        $titulo = $oferta[$i]["titulo"];
                        $fecha = $oferta[$i]["fecha"];
                        $logo = $oferta[$i]["logo"];  
                        $empresa = $oferta[$i]["empresa"];   
                        $categoria = $oferta[$i]["categoria"];    

                        $fechaNueva = fechas($fecha);

                        if($numIntereses !== 0){
                            if(in_array($categoria,$categorias)){
                                echo "
                                <div class='card card-shadow' style='width: 18rem;'>
                                    <div class='card-body'>
                                        <img src='assets/img/empresa/logo/$logo' class='card-img-top' alt='...'>
                                        <h5 class='card-title'>$titulo</h5>
                                        <p class='card-text'>$empresa</p>
                                        <i>$fechaNueva</i>
                                    </div>
                                    <div class='card-body enlace'>
                                        <a class='boton' href='php/oferta/ofertaCompleta.php?id=$id'>VER MÁS</a>
                                    </div>
                                </div>
                                ";
                            }        
                        }else{
                            echo "
                            <div class='card card-shadow' style='width: 18rem;'>
                                <div class='card-body'>
                                    <img src='assets/img/empresa/logo/$logo' class='card-img-top' alt='...'>
                                    <h5 class='card-title'>$titulo</h5>
                                    <p class='card-text'>$empresa</p>
                                    <i>$fechaNueva</i>
                                </div>
                                <div class='card-body enlace'>
                                    <a class='boton' href='php/oferta/ofertaCompleta.php?id=$id'>VER MÁS</a>
                                </div>
                            </div>
                            ";
                        }
                        
                    }
                }else{
                    echo "
                        
                            <h3>ÚLTIMAS OFERTAS</h3>
                            <div class='container ofertas'>";
                    $datos = $con->query("select oferta.id id, titulo, fecha, empresa.nombre empresa, empresa.logo logo from oferta,empresa where oferta.id_empresa=empresa.id and oferta.estado=1 order by fecha desc limit 6");
                    $i = 0;
                    $contador = 0;
                    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                        $oferta[$i]['id'] = $fila['id'];
                        $oferta[$i]['titulo'] = $fila['titulo'];
                        $oferta[$i]['fecha'] = $fila['fecha'];
                        $oferta[$i]['logo'] = $fila['logo'];                     
                        $oferta[$i]['empresa'] = $fila['empresa'];  

                        $i++;
                        $contador++;
                    }                
                    for($i = 0; $i<$contador; $i++){
                        $id = $oferta[$i]["id"];
                        $titulo = $oferta[$i]["titulo"];
                        $fecha = $oferta[$i]["fecha"];
                        $logo = $oferta[$i]["logo"];  
                        $empresa = $oferta[$i]["empresa"];     

                        $fechaNueva = fechas($fecha);
                        echo "
                        <div class='card card-shadow' style='width: 18rem;'>
                            <div class='card-body'>
                                <img src='assets/img/empresa/logo/$logo' class='card-img-top' alt='...'>    
                                <h5 class='card-title'>$titulo</h5>
                                <p class='card-text'>$empresa</p>
                                <i>$fechaNueva</i>
                            </div>
                            <div class='card-body enlace'>
                                <a class='boton' href='php/oferta/ofertaCompleta.php?id=$id'>VER MÁS</a>
                            </div>
                        </div>
                        ";
                    }
                }

            ?>
            </div>
            </div>
        </section>

        <?php
            if(!(isset($_SESSION['tipo']))){
                echo "
                <section class='registro' id='registro'>
                <h4>¿ERES NUEVO?</h4>
                <p>Si ya tienes una cuenta haz click en <a href='php/sesion/inicioSesion.php' class='sesion'>iniciar sesión</a></p>
                <div class='contenido'>
                    <div class='registro-persona'>
                        <a href='php/sesion/registroPersona.php' class='boton'>CREAR UNA CUENTA COMO PARTICULAR</a>
                    </div>
                    <div class='registro-empresa'>
                        <a href='php/sesion/registroEmpresa.php' class='boton'>CREAR UNA CUENTA COMO EMPRESA</a>
                    </div>
                </div>
            </section>
                ";
            }
        ?>

    </main>
    <footer class="index">
        <?php
            footerIndex();
            $con->close();
        ?>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</body>

</html>
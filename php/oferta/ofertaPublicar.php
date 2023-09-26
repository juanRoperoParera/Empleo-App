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
    <section class="sesion card-shadow publicarOferta">
            <h3>PUBLICAR OFERTA</h3>
            <div class="formulario">
                <form action="#" method="post" autocomplete="off">
                    <input type="text" placeholder="PUESTO" name="titulo">
                    <textarea name="descripcion" placeholder="Descripción de la oferta"></textarea>
                    <select name="categoria">
                    <option value="0" selected hidden>Selecciona una categoria</option>
                    <?php
                        $categoria = $con->query("select nombre,id from categoria");
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
                                    echo "<option value='$idCategoria'>$nombre</option>";
                                }                         
                            }
                        }
                    ?>
                    </select>
                    <input type="submit" name="publicar" value="Publicar">
                </form>
            </div>
        </section>

        <?php
            if(isset($_POST['publicar'])){
                $titulo = $_POST['titulo'];
                $descripcion = $_POST['descripcion'];
                $categoria = $_POST['categoria'];

                if($titulo !== "" and $descripcion !== "" and $categoria !== "0"){
                    $fechaActual = date("Y-m-d");

                    $cif=$_SESSION['nombre'];
                    $idConsulta = "select id from empresa where cif=?";
                    $stmt = $con->prepare($idConsulta);
                    $stmt->bind_param("s",$cif);
                    
                    $stmt->execute();
    
                    $stmt->bind_result($idEmpresa);
                    $stmt->fetch();
                    $stmt->close();

                    $insert = "INSERT INTO oferta (id, id_empresa, titulo, fecha, estado, descripcion) VALUES ( null, '$idEmpresa', '$titulo', '$fechaActual', 1, '$descripcion')";
                    $datos = $con->query($insert);

                    $idOferta = $con->insert_id;

                    $insert = "INSERT INTO asociada (id_categoria, id_oferta) VALUES ('$categoria', '$idOferta')";
                    $datos = $con->query($insert);

                    echo "      
                        <div class='correcto'>
                            <p><i class='fa-solid fa-triangle-exclamation'></i> Oferta publicada</p>
                        </div>"; 
                    header('Refresh: 1; URL=../empresa/ofertasempresa.php');
                }else{
                    echo "      
                    <div class='error'>
                        <p><i class='fa-solid fa-triangle-exclamation'></i> Debes rellenar todos los campos</p>
                    </div>";
                }                
            }
        ?>
        <br>
        <div class="navegacion">
                    <a href="../empresa/cuentaempresa.php"><i class="fa-solid fa-arrow-left"></i> Mi cuenta</a>
                </div>
    </main>
    <footer>
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
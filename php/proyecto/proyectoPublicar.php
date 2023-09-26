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
            <h3>PUBLICAR PROYECTO</h3>
            <div class="formulario">
                <form action="#" method="post" autocomplete="off">
                    <input type="text" placeholder="NOMBRE" name="nombre">
                    <textarea name="descripcion" placeholder="Descripción del proyecto"></textarea>
                    <input type="submit" name="publicar" value="Publicar">
                </form>
            </div>
        </section>

        <?php
            if(isset($_POST['publicar'])){
                $titulo = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];

                if($titulo !== "" and $descripcion !== ""){
                    $fechaActual = date("Y-m-d");

                    $cif=$_SESSION['nombre'];
                    $idConsulta = "select id from empresa where cif=?";
                    $stmt = $con->prepare($idConsulta);
                    $stmt->bind_param("s",$cif);
                    
                    $stmt->execute();
    
                    $stmt->bind_result($idEmpresa);
                    $stmt->fetch();
                    $stmt->close();

                    $insert = "INSERT INTO proyecto (id, id_empresa, nombre, descripcion, f_inicio, f_fin, estado) VALUES ( null, '$idEmpresa', '$titulo', '$descripcion', '$fechaActual', null, 1)";
                    $datos = $con->query($insert);
                    echo "      
                        <div class='correcto'>
                            <p><i class='fa-solid fa-triangle-exclamation'></i> Proyecto publicado</p>
                        </div>"; 
                    /* header('Refresh: 1; URL=../empresa/ofertasempresa.php'); */
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
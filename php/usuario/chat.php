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

    <script src="https://www.gstatic.com/firebasejs/5.8.2/firebase.js"></script>
  
    <script type="text/javascript" src="../../js/app.js" defer></script>
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
        <?php
            $idUsuario = $_GET['idUsuario'];
            $idProyecto = $_GET['idProyecto'];

            $consulta = "select nombre from usuario where id=?";
            $stmt = $con->prepare($consulta);
            $stmt->bind_param("s",$idUsuario);
            
            $stmt->execute();

            $stmt->bind_result($nombreUsuario);
            $stmt->fetch();
            $stmt->close();

            $consulta = "select nombre from proyecto where id=?";
            $stmt = $con->prepare($consulta);
            $stmt->bind_param("s",$idProyecto);
            
            $stmt->execute();

            $stmt->bind_result($nombreProyecto);
            $stmt->fetch();
            $stmt->close();

            echo "<p id='nombre'>$nombreUsuario</p>";
            echo "<p id='nombreProyecto'>$nombreProyecto</p>";
        ?>    
        <?php
            echo "<h1 id='chath1'>Chat de $nombreProyecto</h1>";
        ?> 
        <div class="contenedor chat">
            <div class='mensajes' id="mensajes">

            </div>
            <div class='escribir'>
                <input type="text" id="caja"><br>
                <input type="button" class="enviar-chat" id="boton-chat" value="enviar">
            </div>         
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
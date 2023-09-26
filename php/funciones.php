<?php
    session_start();
    function navIndex(){
        echo "<script src='js/app2.js' defer></script>";
        if(isset($_SESSION['tipo'])){
            if($_SESSION['tipo'] == "persona"){
                echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='#' class='logo'><img src='assets/img/logo.png' alt=''></a>
                    <li><a href='php/empleo/empleo.php'>EMPLEO</a></li>
                    <li><a href='php/usuario/candidaturas.php'>CANDIDATURAS</a></li>
                    <li><a href='php/usuario/proyectos.php'>PROYECTOS</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='php/usuario/cuentausuario.php'>MI CUENTA</a>
            </nav>";
            }elseif($_SESSION['tipo'] == "empresa"){
                echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='#' class='logo'><img src='assets/img/logo.png' alt=''></a>
                    <li><a href='php/empleo/empleo.php'>EMPLEO</a></li>
                    <li><a href='php/empresa/ofertasempresa.php'>OFERTAS</a></li>
                    <li><a href='php/empleados/empleados.php'>EMPLEADOS</a></li>
                    <li><a href='php/empresa/proyectosEmpresa.php'>PROYECTOS</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='php/empresa/cuentaempresa.php'>CUENTA EMPRESA</a>
            </nav>";
            }
        }elseif(isset($_COOKIE['sesion'])){
            session_decode($_COOKIE['sesion']);
            if($_SESSION['tipo'] == "persona"){
                echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='#' class='logo'><img src='assets/img/logo.png' alt=''></a>
                    <li><a href='php/empleo/empleo.php'>EMPLEO</a></li>
                    <li><a href='php/usuario/candidaturas.php'>CANDIDATURAS</a></li>
                    <li><a href='php/usuario/proyectos.php'>PROYECTOS</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='php/usuario/cuentausuario.php'>MI CUENTA</a>
            </nav>";
            }elseif($_SESSION['tipo'] == "empresa"){
                echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='#' class='logo'><img src='assets/img/logo.png' alt=''></a>
                    <li><a href='php/empleo/empleo.php'>EMPLEO</a></li>
                    <li><a href='php/empresa/ofertasempresa.php'>OFERTAS</a></li>
                    <li><a href='php/empleados/empleados.php'>EMPLEADOS</a></li>
                    <li><a href='php/empresa/proyectosEmpresa.php'>PROYECTOS</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='php/empresa/cuentaempresa.php'>CUENTA EMPRESA</a>
            </nav>";
            }
        }else{
            echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='#' class='logo'><img src='assets/img/logo.png' alt=''></a> 
                    <li><a href='php/empleo/empleo.php'>EMPLEO</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='php/sesion/inicioSesion.php'>INICIAR SESIÓN</a>
            </nav>";
        }
        
    }
    function nav(){
        echo "<script src='../../js/app2.js' defer></script>";
        if(isset($_SESSION['tipo'])){
            if($_SESSION['tipo'] == "persona"){
                echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='../../index.php' class='logo'><img src='../../assets/img/logo.png' alt=''></a>
                    <li><a href='../empleo/empleo.php'>EMPLEO</a></li>
                    <li><a href='../usuario/candidaturas.php'>CANDIDATURAS</a></li>
                    <li><a href='../usuario/proyectos.php'>PROYECTOS</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='../usuario/cuentausuario.php'>MI CUENTA</a>
            </nav>";
            }elseif($_SESSION['tipo'] == "empresa"){
                echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='../../index.php' class='logo'><img src='../../assets/img/logo.png' alt=''></a>
                    <li><a href='../empleo/empleo.php'>EMPLEO</a></li>
                    <li><a href='../empresa/ofertasempresa.php'>OFERTAS</a></li>
                    <li><a href='../empleados/empleados.php'>EMPLEADOS</a></li>
                    <li><a href='../empresa/proyectosEmpresa.php'>PROYECTOS</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='../empresa/cuentaempresa.php'>CUENTA EMPRESA</a>
            </nav>";
            }
        }elseif(isset($_COOKIE['sesion'])){
            session_decode($_COOKIE['sesion']);
            if($_SESSION['tipo'] == "persona"){
                echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='../../index.php' class='logo'><img src='../../assets/img/logo.png' alt=''></a>
                    <li><a href='../empleo/empleo.php'>EMPLEO</a></li>
                    <li><a href='../usuario/candidaturas.php'>CANDIDATURAS</a></li>
                    <li><a href='../usuario/proyectos.php'>PROYECTOS</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='../usuario/cuentausuario.php'>MI CUENTA</a>
            </nav>";
            }elseif($_SESSION['tipo'] == "empresa"){
                echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='../../index.php' class='logo'><img src='../../assets/img/logo.png' alt=''></a>
                    <li><a href='../empleo/empleo.php'>EMPLEO</a></li>
                    <li><a href='../empresa/ofertasempresa.php'>OFERTAS</a></li>
                    <li><a href='../empleados/empleados.php'>EMPLEADOS</a></li>
                    <li><a href='../empresa/proyectosEmpresa.php'>PROYECTOS</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='../empresa/cuentaempresa.php'>CUENTA EMPRESA</a>
            </nav>";
            }
        }else{
            echo "<nav>
                <i class='fa-solid fa-xmark cerrarMenu oculto'></i>
                <ul>
                    <a href='../../index.php' class='logo'><img src='../../assets/img/logo.png' alt=''></a>
                    <li><a href=''>EMPLEO</a></li>
                </ul>
                <i class='fa-solid fa-bars mobile abrirMenu'></i>
                <a class='boton' href='../sesion/inicioSesion.php'> INICIAR SESIÓN</a>
            </nav>";
        }      
    }
    function footerIndex(){
        echo "<img src='assets/img/logo.png' alt=''>
            <ul>
                <li>Empleo App</li>
            </ul>";
    }

    function footer(){
        echo "<img src='../../assets/img/logo.png' alt=''>
            <ul>
                <li>Empleo App</li>
            </ul>";
    }

    function fechas($fecha){
        $dia = date('d', strtotime($fecha)); 
        $mes = date('m', strtotime($fecha)); 
        $año = date('Y', strtotime($fecha));   
        
        $nuevaFecha = $dia."/".$mes."/".$año;

        return $nuevaFecha;
    }
?>
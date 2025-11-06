<?php
    if(!empty($_POST["Ingresar"])){
        if(!empty($_POST["nick"] and !empty($_POST["password"]))){
            $nick = $_POST["nick"];
            $password = $_POST["password"];

            $Login = $ConBD->query("SELECT * FROM login WHERE nick = '$nick' and password = '$password'");
         
            if($Rows = $Login->fetch_object()){
                echo 'Bienvenido';
                header("Location:Views/Pages/Inicio.php");

            }
            else{
                echo 'Error en Usuario Y/O Contraseña';
            }
            
        }

    }

?>

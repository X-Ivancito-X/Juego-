<?php
    if(!empty($_POST["Ingresar"])){
        if(!empty($_POST["Usuario"] and !empty($_POST["Contraseña"]))){
            $Usuario = $_POST["Usuario"];
            $Contraseña = $_POST["Contraseña"];

            $Login = $ConBD->query("SELECT * FROM login WHERE Usuario = '$Usuario' and Contraseña = '$Contraseña'");
         
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

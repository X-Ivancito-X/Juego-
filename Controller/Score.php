<?php
    session_start();

    include 'Model/ConexionBD.php';

    $score = $_POST['numero'];
    $nick = $_SESSION['nick'];

    $query = "UPDATE usuario SET score = '$score' WHERE nick = '$nick' ";
    $ejecutar = mysqli_query($conexion, $query);

    if($ejecutar){
        echo'
            <script>
                alert("Se a guardado su score");
            </script>
        ';
    }

    mysqli_close($conexion);

?>
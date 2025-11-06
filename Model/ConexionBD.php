<?php
  // Ajusta estas credenciales a tu entorno
  $host = 'localhost';
  $user = 'root';        // <--- cambia a tu usuario
  $pass = '';            // <--- cambia a tu contraseña
  $db   = 'jdar_db';     // <--- cambia al nombre de tu base

  $conexion = mysqli_connect($host, $user, $pass, $db);
  if (!$conexion) {
    die('Error de conexión: ' . mysqli_connect_error());
  }
  mysqli_set_charset($conexion, 'utf8mb4');
?>
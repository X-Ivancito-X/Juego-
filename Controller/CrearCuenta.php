<?php
session_start();
require_once __DIR__ . '/../Model/ConexionBD.php';

header('Content-Type: text/html; charset=utf-8');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nick = trim($_POST['nick'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($nick === '' || $password === '') {
    $error = 'Ingresa usuario y contraseña.';
  } else if (!isset($conexion) || !$conexion) {
    $error = 'Error de conexión a la base de datos.';
  } else {
    // Verifica si el usuario ya existe
    $stmt = $conexion->prepare('SELECT 1 FROM login WHERE nick = ? LIMIT 1');
    if (!$stmt) {
      $error = 'Error preparando la consulta.';
    } else {
      $stmt->bind_param('s', $nick);
      $stmt->execute();
      $stmt->store_result();
      if ($stmt->num_rows > 0) {
        $error = 'El usuario ya existe.';
      } else {
        $stmt->close();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt2 = $conexion->prepare('INSERT INTO login (nick, password) VALUES (?, ?)');
        if (!$stmt2) {
          $error = 'Error preparando inserción.';
        } else {
          $stmt2->bind_param('ss', $nick, $hash);
          if ($stmt2->execute()) {
            $success = 'Cuenta creada correctamente. Ahora puedes iniciar sesión.';
          } else {
            $error = 'Error al crear la cuenta.';
          }
          $stmt2->close();
        }
      }
    }
    mysqli_close($conexion);
  }
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Crear Cuenta | GuitarHero Style</title>
    <link rel="icon" href="../Views/Src/Img/escuchando.png" type="image/png">
    <link rel="stylesheet" href="../Views/Css/style.css">
  </head>
  <body>
    <div class="wrap">
      <div class="card">
        <div class="brand">
          <div class="logo">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 3v18M6 7l12 10M18 7L6 17" stroke="url(#g)" stroke-width="2" stroke-linecap="round" />
              <defs>
                <linearGradient id="g" x1="0" y1="0" x2="24" y2="24">
                  <stop stop-color="#0084ff"/>
                  <stop offset="1" stop-color="#00b0ff"/>
                </linearGradient>
              </defs>
            </svg>
          </div>
          <h1>Crear Cuenta</h1>
        </div>

        <p class="subtitle">Regístrate para jugar y guardar tu score.</p>

        <?php if ($error): ?>
          <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
          <div class="success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="on" action="">
          <div>
            <label for="nick">Usuario</label>
            <input type="text" id="nick" name="nick" placeholder="Tu nick" required />
          </div>
          <div>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required />
          </div>
          <button class="btn btn--orange" type="submit">Crear cuenta</button>
          <div class="foot">
            <span class="hint">¿Ya tienes cuenta?</span>
            <a class="link" href="../index.php">Volver al login</a>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
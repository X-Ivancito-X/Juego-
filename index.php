<?php
session_start();
// Lee error proveniente del controlador
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | GuitarHero Style</title>
    <link rel="icon" href="Views/Src/Img/escuchando.png" type="image/png">
    <link rel="stylesheet" href="Views/Css/style.css">
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
                  <stop stop-color="#7c3aed"/>
                  <stop offset="1" stop-color="#00e0ff"/>
                </linearGradient>
              </defs>
            </svg>
          </div>
          <h1>GuitarHero Style</h1>
        </div>

        <p class="subtitle">Inicia sesión para jugar y guardar tu score.</p>

        <?php if ($error): ?>
          <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="on" action="Controller/Login.php">
          <div>
            <label for="nick">Usuario</label>
            <input type="text" id="nick" name="nick" placeholder="Tu nick" required />
          </div>

          <div>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required />
          </div>
          
          <button class="btn btn--blue" type="submit" name="Ingresar">Entrar y jugar</button>

          <div class="foot">
            <span class="hint">¿Nuevo por aquí? <a class="link" href="Controller/CrearCuenta.php">Crea tu cuenta.</a></span>
            <a class="link" href="Views/Page/Juego.php">Probar demo en página aparte</a>
          </div>

        </form>

      </div>

    </div>

  </body>

</html>

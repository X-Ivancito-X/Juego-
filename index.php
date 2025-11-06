<?php
session_start();

// Manejo de login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once __DIR__ . '/Model/ConexionBD.php';
  if (!isset($conexion) || !$conexion) {
    $error = 'Error de conexión a la base de datos. Verifique credenciales.';
  } else {
    $nick = trim($_POST['nick'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($nick === '' || $password === '') {
      $error = 'Ingresa usuario y contraseña.';
    } else {
      // Consulta básica (ajusta a tu esquema). Se asume tabla `usuario` con campos `nick` y `password`.
      $stmt = $conexion->prepare('SELECT nick, password FROM usuario WHERE nick = ? LIMIT 1');
      if ($stmt) {
        $stmt->bind_param('s', $nick);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
          $stmt->bind_result($dbNick, $dbPass);
          $stmt->fetch();
          // Comparación simple; si usas hash, reemplaza por password_verify($password, $dbPass)
          if ($password === $dbPass) {
            $_SESSION['nick'] = $dbNick;
            header('Location: Views/Page/Juego.html');
            exit;
          } else {
            $error = 'Credenciales incorrectas.';
          }
        } else {
          $error = 'Usuario no encontrado.';
        }
        $stmt->close();
      } else {
        $error = 'Error preparando la consulta.';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | GuitarHero Style</title>
    <link rel="icon" href="Views/Src/Img/escuchando.png" type="image/png">
    <style>
      :root {
        --bg: #0b0f1a;
        --panel: #12182a;
        --accent: #7c3aed;
        --accent2: #00e0ff;
        --text: #e5e7eb;
        --muted: #9aa0aa;
      }
      * { box-sizing: border-box; }
      body {
        margin: 0; min-height: 100vh; font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', Arial, sans-serif; color: var(--text);
        background: radial-gradient(1200px 600px at 80% 0%, rgba(124,58,237,.25), transparent 60%),
                    radial-gradient(800px 400px at 20% 100%, rgba(0,224,255,.25), transparent 60%),
                    linear-gradient(180deg, #0b0f1a 0%, #0a0d17 100%);
      }
      .wrap { display: grid; place-items: center; min-height: 100vh; padding: 24px; }
      .card {
        width: 100%; max-width: 420px; background: linear-gradient(180deg, rgba(18,24,42,.9), rgba(10,13,23,.9));
        border: 1px solid rgba(255,255,255,.06); border-radius: 16px; padding: 28px; position: relative; overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,.6), inset 0 1px 0 rgba(255,255,255,.06);
      }
      .card::before {
        content: ""; position: absolute; inset: -2px; border-radius: 18px; padding: 2px; background:
          linear-gradient(45deg, rgba(124,58,237,.6), rgba(0,224,255,.6)); -webkit-mask: linear-gradient(#000, #000) content-box, linear-gradient(#000, #000);
        -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
      }
      .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
      .logo {
        width: 42px; height: 42px; border-radius: 8px; display: grid; place-items: center;
        background: linear-gradient(135deg, rgba(124,58,237,.25), rgba(0,224,255,.25));
        box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 6px 20px rgba(0,0,0,.35);
      }
      .brand h1 { font-size: 20px; margin: 0; letter-spacing: .4px; }
      .subtitle { color: var(--muted); font-size: 13px; margin-bottom: 18px; }
      form { display: grid; gap: 14px; }
      label { font-size: 13px; color: var(--muted); }
      input[type="text"], input[type="password"] {
        width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid rgba(255,255,255,.1);
        background: #0e1324; color: var(--text);
        outline: none; transition: border-color .2s, box-shadow .2s;
      }
      input[type="text"]:focus, input[type="password"]:focus {
        border-color: rgba(124,58,237,.6); box-shadow: 0 0 0 4px rgba(124,58,237,.15);
      }
      .btn {
        background: linear-gradient(90deg, var(--accent), var(--accent2)); color: white; border: none;
        padding: 12px 16px; border-radius: 12px; font-weight: 600; cursor: pointer;
        box-shadow: 0 8px 20px rgba(124,58,237,.35); transition: transform .06s ease;
      }
      .btn:active { transform: translateY(1px); }
      .error { background: rgba(239,68,68,.12); color: #fecaca; border: 1px solid rgba(239,68,68,.25);
        padding: 10px 12px; border-radius: 10px; font-size: 13px; }
      .foot { display: flex; justify-content: space-between; align-items: center; margin-top: 6px; }
      .hint { font-size: 12px; color: var(--muted); }
      .link { color: var(--accent2); text-decoration: none; font-size: 12px; }
    </style>
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

        <form method="post" autocomplete="on">
          <div>
            <label for="nick">Usuario</label>
            <input type="text" id="nick" name="nick" placeholder="Tu nick" required />
          </div>
          <div>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required />
          </div>
          <button class="btn" type="submit">Entrar y jugar</button>
          <div class="foot">
            <span class="hint">¿Nuevo por aquí? Consulta con el admin.</span>
            <a class="link" href="Views/Page/Juego.html">Probar demo</a>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
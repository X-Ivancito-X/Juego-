<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <title>Juego - GuitarHero Style</title>
    <link rel="icon" href="../Src/Img/escuchando.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="../Css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/p5@1.11.10/lib/p5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/p5@1.11.10/lib/addons/p5.sound.min.js"></script>
  </head>
  <body>
    <!-- Demo del juego embebida en el index: no guarda si no estás logueado -->
    <div class="wrap wrap--game">
      <div class="card card--wide">
        <p class="subtitle subtitle--tight">Demo embebida: juega aquí mismo.
          <?php if (!isset($_SESSION['nick'])): ?>
            <br>Para guardar el score debes iniciar sesión.
          <?php else: ?>
            <br>Estás logueado como <strong><?= htmlspecialchars($_SESSION['nick'], ENT_QUOTES, 'UTF-8'); ?></strong>.
          <?php endif; ?>
        </p>
        <main>
          <div class="game-controls">
            <button type="button" class="btn btn--sm btn--orange" onclick="location.href='/index.php'" <?php if (!isset($_SESSION['nick'])) echo 'disabled title="Inicia sesión para guardar"'; ?>>Guardar score</button>
          </div>
        </main>
      </div>
    </div>
    <!-- Scripts del juego -->
    <script src="../Js/sketch.js"></script>
    <?php if (isset($_SESSION['nick'])): ?>
      <script src="../Js/Guardar.js"></script>
    <?php endif; ?>
  </body>
</html>
let notes = [];
let lanes = 10; // 5 carriles por jugador
let laneWidth;

let scoreLeft = 0, scoreRight = 0;
let totalNotesLeft = 0, totalNotesRight = 0;

let comboLeft = 0, comboRight = 0;
let maxComboLeft = 0, maxComboRight = 0;

let barWidth, barHeight = 25;
let barLeftX, barRightX;
let barSpeed = 8;

let gameOver = false;
let song;
let songStarted = false;
let fft;
let lastBeatLeft = 0, lastBeatRight = 0;
let beatCooldown = 250;

// efecto de flash
let flashLeft = 0, flashRight = 0;

function preload() {
  // ⚠️ Cambia el ID por el de tu archivo en Google Drive
  song = loadSound("Soda_Stereo_-_Tele_K_(mp3.pm).mp3");
}

function setup() {
  createCanvas(windowWidth, windowHeight);
  laneWidth = width / lanes;

  // barra de cada jugador: ocupa los 5 carriles de su lado
  barWidth = laneWidth * 1;

  barLeftX = laneWidth * 2 - barWidth / 2; // centro en los 5 carriles izquierdos
  barRightX = width - laneWidth * 3 - barWidth / 2; // centro en los 5 carriles derechos

  fft = new p5.FFT(0.8, 1024);
}

function draw() {
  background(20);

  if (!songStarted) {
    fill(255);
    textAlign(CENTER, CENTER);
    textSize(32);
    text("Presiona ESPACIO para comenzar", width / 2, height / 2);
    return;
  }

  if (!gameOver) {
    // dibujar carriles
    stroke(100);
    for (let i = 0; i <= lanes; i++) {
      line(i * laneWidth, 0, i * laneWidth, height);
    }

    // análisis de audio
    let spectrum = fft.analyze();
    let bass = fft.getEnergy("bass");   // graves
    let mid = fft.getEnergy("mid");     // medios/agudos

    // generar notas en los 5 carriles izquierdos (Jugador 1)
    if (bass > 180 && millis() - lastBeatLeft > beatCooldown) {
      let lane = floor(random(0, 4)); // carriles 0-4
      spawnNote(lane);
      lastBeatLeft = millis();
    }

    // generar notas en los 5 carriles derechos (Jugador 2)
    if (mid > 150 && millis() - lastBeatRight > beatCooldown) {
      let lane = floor(random(6, 10)); // carriles 5-9
      spawnNote(lane);
      lastBeatRight = millis();
    }

    // dibujar notas
    let remainingNotes = 0;
    for (let n of notes) {
      if (!n.hit && n.y < height) {
        fill(n.lane < 5 ? color(0,200,255) : color(255,100,0));
        rect(n.lane * laneWidth + laneWidth*0.2, n.y, laneWidth*0.6, 25, 5);
        n.y += 5;

        // colisión jugador 1
        if (
          n.lane < 5 &&
          n.y + 25 > height - barHeight &&
          n.y < height &&
          n.lane * laneWidth + laneWidth*0.6 > barLeftX &&
          n.lane * laneWidth < barLeftX + barWidth
        ) {
          n.hit = true;
          scoreLeft += 10;
          comboLeft++;
          if (comboLeft > maxComboLeft) maxComboLeft = comboLeft;
          flashLeft = 10;
        }

        // colisión jugador 2
        if (
          n.lane >= 5 &&
          n.y + 25 > height - barHeight &&
          n.y < height &&
          n.lane * laneWidth + laneWidth*0.6 > barRightX &&
          n.lane * laneWidth < barRightX + barWidth
        ) {
          n.hit = true;
          scoreRight += 10;
          comboRight++;
          if (comboRight > maxComboRight) maxComboRight = comboRight;
          flashRight = 10;
        }
      }
      if (!n.hit && n.y < height) {
        remainingNotes++;
      } else if (!n.hit && n.y >= height) {
        if (n.lane < 5) comboLeft = 0;
        else comboRight = 0;
      }
    }

    // barras con flash
    if (flashLeft > 0) { fill(0, 255, 255); flashLeft--; }
    else { fill(0, 200, 255); }
    rect(barLeftX, height - barHeight, barWidth, barHeight, 5);

    if (flashRight > 0) { fill(255, 180, 0); flashRight--; }
    else { fill(255, 100, 0); }
    rect(barRightX, height - barHeight, barWidth, barHeight, 5);

    // puntajes
    fill(255);
    textSize(22);
    textAlign(LEFT, TOP);
    text("Jugador 1: " + scoreLeft, 20, 20);
    text("Combo: " + comboLeft, 20, 50);

    textAlign(RIGHT, TOP);
    text("Jugador 2: " + scoreRight, width - 20, 20);
    text("Combo: " + comboRight, width - 20, 50);

    // movimiento jugadores
    if (keyIsDown(65)) barLeftX -= barSpeed; // A
    if (keyIsDown(68)) barLeftX += barSpeed; // D
    barLeftX = constrain(barLeftX, 0, laneWidth*5 - barWidth);

    if (keyIsDown(LEFT_ARROW)) barRightX -= barSpeed;
    if (keyIsDown(RIGHT_ARROW)) barRightX += barSpeed;
    barRightX = constrain(barRightX, laneWidth*5, width - barWidth);

    // fin del juego
    if (!song.isPlaying() && notes.length > 0 && remainingNotes === 0) {
      gameOver = true;
    }

  } else {
    background(50);
    fill(255);
    textSize(36);
    textAlign(CENTER);
    text("¡Juego Terminado!", width / 2, height / 2 - 150);

    textSize(24);
    text(`Jugador 1: ${scoreLeft} puntos (Max Combo: ${maxComboLeft})`, width/2, height/2 - 70);
    text(`Jugador 2: ${scoreRight} puntos (Max Combo: ${maxComboRight})`, width/2, height/2 - 30);
  }
}

function spawnNote(lane) {
  notes.push({ lane: lane, y: -20, hit: false });
  if (lane < 5) totalNotesLeft++;
  else totalNotesRight++;
}

function keyPressed() {
  if (!songStarted && key === ' ') {
    song.play();
    songStarted = true;
  }
}

function windowResized() {
  resizeCanvas(windowWidth, windowHeight);
  laneWidth = width / lanes;
}

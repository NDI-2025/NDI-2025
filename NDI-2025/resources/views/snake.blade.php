<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Hidden Game</title>
</head>

<body>
    @vite([
        'resources/css/snake.css',
        'resources/js/app.js',
        'resources/js/snake-init.js',
    ])

    <div class="ui">Touches : flèches / ZQSD — R : réinitialiser</div>

    <div class="params-panel">
        <h3>Paramètres du jeu</h3>

        <div class="group">
            <h4>Serpent</h4>

            <label>Taille initiale</label>
            <input type="number" id="snake-size" min="5" max="200">

            <label>Couleur</label>
            <input type="color" id="snake-color">

            <label>Vitesse</label>
            <input type="number" id="snake-speed" min="1" max="20">

            <label>Augmentation par nourriture</label>
            <input type="number" id="snake-increase" min="1" max="10">
        </div>

        <button id="apply">Appliquer</button>
    </div>
    <canvas id="game" width="640" height="480"></canvas>
    <p style="color:orange" id="windows-warning"></p>
</body>

</html>
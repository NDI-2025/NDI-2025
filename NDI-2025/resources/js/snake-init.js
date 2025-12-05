import Snake from './snake';
import Game from './game';
import Params from './params';
import readInput, { directionKeys, keys } from './input';
import Food from './food';

// Récup canvas et contexte
const canvas = document.getElementById('game');
const ctx = canvas.getContext('2d');

// Taille logique (peut adapter au redimensionnement si besoin)
const W = canvas.width;
const H = canvas.height;

// Initialisation du jeu
let snake = new Snake([{ x: 100, y: 100 }], 35, 'lime', { x: 0, y: 0 }, 5, Params);
let food = []; // Initialize food variable
const game = new Game(W, H, snake, food, ctx, Params);
game.init();

let lastTurnTime = 0;
const turnCooldown = 100; // ms
let queue = [];

// Ec input.js
window.addEventListener("keydown", (e) => {
    const now = Date.now();
    if (directionKeys.includes(e.key)) {

        if (now - lastTurnTime >= turnCooldown) {
            // Met tout à false
            for (const k of directionKeys) {
                keys[k] = false;
            }
            // Active seulement cette touche
            keys[e.key] = true;
            lastTurnTime = now;
        } else {
            // Ajoute à la file d'attente
            queue.push(e.key);
        }
    }

    // Gérer la queue
    if (queue.length > 0 && now - lastTurnTime >= turnCooldown) {
        const nextKey = queue.shift();
        // Met tout à false
        for (const k of directionKeys) {
            keys[k] = false;
        }
        // Active seulement la prochaine touche
        keys[nextKey] = true;
        lastTurnTime = now;
    }

    // touche reset
    if (e.key === "r" || e.key === "R") {
        game.reset();
        for (const k of directionKeys) {
            keys[k] = false;
        }
    }
});
window.addEventListener("keyup", (e) => {
    if (e.key in keys) keys[e.key] = false;
});

// Ex ui.js

window.addEventListener("DOMContentLoaded", () => {
    // --- Remplir l'UI avec les valeurs actuelles --- //
    const sizeInput = document.getElementById("snake-size");
    const colorInput = document.getElementById("snake-color");
    const speedInput = document.getElementById("snake-speed");
    const increaseInput = document.getElementById("snake-increase");
    const applyBtn = document.getElementById("apply");

    if(sizeInput) sizeInput.value = Params.snake.initialSize;
    if(colorInput) colorInput.value = Params.snake.color;
    if(speedInput) speedInput.value = Params.snake.speed;
    if(increaseInput) increaseInput.value = Params.snake.increasePerFood;

    // --- Appliquer les modifications --- //
    if(applyBtn) {
        applyBtn.addEventListener("click", () => {
            Params.snake.initialSize = parseInt(sizeInput.value);
            Params.snake.color = colorInput.value;
            Params.snake.speed = parseInt(speedInput.value);
            Params.snake.increasePerFood = parseInt(increaseInput.value);

            // Re-créer le snake et la food
            snake = new Snake(
                [{ x: 100, y: 100 }],
                Params.snake.initialSize,
                Params.snake.color,
                { x: 0, y: 0 },
                Params.snake.speed
            );
            food = [
                new Food(200, 200, Params.food.size, Params.food.icons[0]),
                new Food(300, 150, Params.food.size, Params.food.icons[0]),
                new Food(150, 300, Params.food.size, Params.food.icons[0]),
                new Food(350, 350, Params.food.size, Params.food.icons[0]),
            ];

            game.snake = snake;
            game.food = food;

            console.log("Snake et Food recréés avec les nouveaux paramètres.");

            game.updateParams(Params);
            console.log("Params mis à jour :", Params);
        });
    }
});

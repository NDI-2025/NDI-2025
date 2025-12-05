window.addEventListener("DOMContentLoaded", () => {
    // --- Remplir l'UI avec les valeurs actuelles --- //
    document.getElementById("snake-size").value = Params.snake.initialSize;
    document.getElementById("snake-color").value = Params.snake.color;
    document.getElementById("snake-speed").value = Params.snake.speed;
    document.getElementById("snake-increase").value =
        Params.snake.increasePerFood;

    // --- Appliquer les modifications --- //
    document.getElementById("apply").addEventListener("click", () => {
        Params.snake.initialSize = parseInt(
            document.getElementById("snake-size").value
        );
        Params.snake.color = document.getElementById("snake-color").value;
        Params.snake.speed = parseInt(
            document.getElementById("snake-speed").value
        );
        Params.snake.increasePerFood = parseInt(
            document.getElementById("snake-increase").value
        );

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
});

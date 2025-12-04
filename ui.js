window.addEventListener("DOMContentLoaded", () => {

    // --- Remplir l'UI avec les valeurs actuelles --- //
    document.getElementById("snake-size").value = Params.snake.initialSize;
    document.getElementById("snake-color").value = Params.snake.color;
    document.getElementById("snake-speed").value = Params.snake.speed;
    document.getElementById("snake-increase").value = Params.snake.increasePerFood;

    document.getElementById("food-size").value = Params.food.size;
    document.getElementById("food-color").value = Params.food.color;
    document.getElementById("food-icons").value = Params.food.icons.join(",");

    // --- Appliquer les modifications --- //
    document.getElementById("apply").addEventListener("click", () => {

        Params.snake.initialSize = parseInt(document.getElementById("snake-size").value);
        Params.snake.color = document.getElementById("snake-color").value;
        Params.snake.speed = parseInt(document.getElementById("snake-speed").value);
        Params.snake.increasePerFood = parseInt(document.getElementById("snake-increase").value);

        Params.food.size = parseInt(document.getElementById("food-size").value);
        Params.food.color = document.getElementById("food-color").value;

        const icons = document.getElementById("food-icons").value.split(",");
        Params.food.icons = icons.map(i => i.trim()).filter(i => i !== "");

        // Re-créer le snake et la food
        snake = new Snake([{x: 100, y: 100}], Params.snake.initialSize, Params.snake.color, {x: 0, y: 0}, Params.snake.speed);
        food = new Food(200, 200, Params.food.size, Params.food.color, Params.food.icons[0]);

        game.snake = snake;
        game.food = food;

        console.log("Snake et Food recréés avec les nouveaux paramètres.");

        game.updateParams(Params);
        console.log("Params mis à jour :", Params);
    });

});

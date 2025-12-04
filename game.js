class Game {
    constructor(width, height, snake, food, grid) {
        this.width = width;
        this.height = height;
        this.snake = snake;
        this.food = food;
        this.grid = grid;
    }

    // Initialisation
    init() {
        // Position initiale de la nourriture
        this.food.x =
            Math.floor(Math.random() * (this.width / this.food.size)) *
            this.food.size;
        this.food.y =
            Math.floor(Math.random() * (this.height / this.food.size)) *
            this.food.size;
    }

    // Collision avec les bords (empêche de sortir)
    clampToBounds(obj) {
        if (
            this.snake.x < 0 ||
            this.snake.y < 0 ||
            obj.x + obj.size > this.width ||
            obj.y + obj.size > this.height
        ) {
            this.reset();
        }
    }

    // Resets
    reset() {
        snake.x = W / 2 - snake.size / 2;
        snake.y = H / 2 - snake.size / 2;
        snake.vx = snake.vy = 0;
    }

    // Game loop
    update(deltaTime) {
        // Lire les entrées du clavier
        const input = readInput();
        
        // Mettre à jour la direction seulement si une touche est pressée
        if (input.dx !== 0 || input.dy !== 0) {
            this.snake.direction.x = input.dx;
            this.snake.direction.y = input.dy;
        }
        
        // Met à jour la position du serpent
        const dx = this.snake.direction.x;
        const dy = this.snake.direction.y;
        this.snake.move(dx, dy);
        // Vérifie les collisions avec les bords
        this.clampToBounds(this.snake.body[0]);

        // Vérifie la collision avec la nourriture
        const head = this.snake.body[0];
        if (
            head.x < this.food.x + this.food.size &&
            head.x + this.snake.size > this.food.x &&
            head.y < this.food.y + this.food.size &&
            head.y + this.snake.size > this.food.y
        ) {
            // Mange la nourriture
            this.snake.body.push({ x: head.x + 15, y: head.y + 15 });
            // Repositionne la nourriture
            this.food.x =
                Math.floor(Math.random() * (this.width / this.food.size)) *
                this.food.size;
            this.food.y =
                Math.floor(Math.random() * (this.height / this.food.size)) *
                this.food.size;
        }
    }

    // Fin de partie
    endGame() {
        alert("Game Over!");
        reset();
    }
}

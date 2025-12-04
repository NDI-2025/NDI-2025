class Game {
    constructor(width, height, snake, food, ctx) {
        this.width = width;
        this.height = height;
        this.snake = snake;
        this.food = food;
        this.ctx = ctx;
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

        // Lance la boucle de jeu
        this.loop();
    }

    // Collision avec les bords
    clampToBounds(obj) {
        if(obj.x < 0) obj.x = this.width - this.snake.size;
        if(obj.y < 0) obj.y = this.height - this.snake.size;
        if(obj.x + this.snake.size > this.width) obj.x = 0;
        if(obj.y + this.snake.size > this.height) obj.y = 0;
    }

    // Reset du jeu
    reset() {
        this.snake.body = [{x: this.width / 2, y: this.height / 2}];
        this.snake.direction = {x: 0, y: 0};
    }

    // Mise à jour du jeu
    update() {
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
            this.snake.body.push({ x: head.x, y: head.y });
            // Repositionne la nourriture
            this.food.x =
                Math.floor(Math.random() * (this.width / this.food.size)) *
                this.food.size;
            this.food.y =
                Math.floor(Math.random() * (this.height / this.food.size)) *
                this.food.size;
        }
    }

    // Rendu du jeu
    draw() {
        // Effacer le canvas
        this.ctx.fillStyle = '#0b1220';
        this.ctx.fillRect(0, 0, this.width, this.height);

        // Dessiner le serpent
        this.ctx.fillStyle = this.snake.color;
        this.snake.body.forEach(segment => {
            this.ctx.fillRect(segment.x, segment.y, this.snake.size, this.snake.size);
        });

        // Dessiner la nourriture
        if (this.food.icon) {
            // Dessiner l'emoji/icon
            this.ctx.font = `${this.food.size}px Arial`;
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.fillText(this.food.icon, this.food.x + this.food.size / 2, this.food.y + this.food.size / 2);
        } else {
            // Fallback: rectangle coloré
            this.ctx.fillStyle = this.food.color;
            this.ctx.fillRect(this.food.x, this.food.y, this.food.size, this.food.size);
        }
    }

    // Boucle de jeu
    loop = () => {
        this.update();
        this.draw();
        requestAnimationFrame(this.loop);
    }

    // Fin de partie
    endGame() {
        alert("Game Over!");
        this.reset();
    }
    
}

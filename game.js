class Game {
    constructor(width, height, snake, food, ctx, params) {
        this.width = width;
        this.height = height;
        this.snake = snake;
        this.food = food;
        this.ctx = ctx;
        this.params = params;
    }

    // Initialisation
    init() {
        // Position initiale de la nourriture

        this.food[0].x =
            Math.floor(Math.random() * (this.width / this.food[0].size)) *
            this.food[0].size;
        this.food[0].y =
            Math.floor(Math.random() * (this.height / this.food[0].size)) *
            this.food[0].size;

        // Lance la boucle de jeu
        this.loop();
    }

    // Collision avec les bords
    clampToBounds(obj) {
        if (obj.x < 0) obj.x = this.width - this.snake.size;
        if (obj.y < 0) obj.y = this.height - this.snake.size;
        if (obj.x + this.snake.size > this.width) obj.x = 0;
        if (obj.y + this.snake.size > this.height) obj.y = 0;
    }

    // Reset du jeu
    reset() {
        this.snake.body = [{ x: this.width / 2, y: this.height / 2 }];
        this.snake.direction = { x: 0, y: 0 };
    }

    // Mise à jour du jeu
    update() {
        // Lire les entrées du clavier
        const input = readInput();

        // Mettre à jour la direction seulement si une touche est pressée
        if ( this.checkOppositeDirectionChange(input)  && (input.dx !== 0 || input.dy !== 0)  ) {
            this.snake.direction.x = input.dx;
            this.snake.direction.y = input.dy;
        }

        // Met à jour la position du serpent
        const dx = this.snake.direction.x;
        const dy = this.snake.direction.y;
        this.snake.move(dx, dy);

        // Vérifie les collisions avec les bords
        this.clampToBounds(this.snake.body[0]);

        // Vérifie la collision avec lui-même
        if (this.checkSelfCollision()) {
            this.endGame();
        }

        // Vérifie la collision avec la nourriture
        this.checkFoodCollision();
    }

    // Rendu du jeu
    draw() {
        // Effacer le canvas
        this.ctx.fillStyle = "#0b1220";
        this.ctx.fillRect(0, 0, this.width, this.height);

        // Dessiner le serpent
        this.ctx.fillStyle = this.snake.color;
        this.snake.body.forEach((segment) => {
            this.ctx.fillRect(
                segment.x,
                segment.y,
                this.snake.size,
                this.snake.size
            );
        });

        // Dessiner la nourriture
        this.drawFood();
    }

    // Check de la collision avec la nourriture
    checkFoodCollision() {
        const head = this.snake.body[0];
        for (let i = 0; i < this.food.length; i++) {
            let currentFood = this.food[i];
            if (
                head.x < currentFood.x + currentFood.size &&
                head.x + this.snake.size > currentFood.x &&
                head.y < currentFood.y + currentFood.size &&
                head.y + this.snake.size > currentFood.y
            ) {
                if(currentFood.type === 'poison'){
                    this.endGame();
                    return;
                }

                // Mange la nourriture
                for (let i = 0; i < this.params.snake.increasePerFood; i++)
                    this.snake.body.push({ x: head.x, y: head.y });

                
                // Repositionne la nourriture
                this.food.splice(i, 1);

                // Ajouter une nouvelle nourriture avec un nouvel icône
                const newIcon =
                    this.params.food.icons[
                        Math.floor(
                            Math.random() * this.params.food.icons.length
                        )   
                    ];;
                const newFood = new Food(
                    Math.floor(Math.random() * (this.width / this.params.food.size)) *
                        this.params.food.size,
                    Math.floor(Math.random() * (this.height / this.params.food.size)) *
                        this.params.food.size,
                    this.params.food.size,
                    newIcon
                );
                this.food.push(newFood);
            }
        }
    }

    // Vérifier que le serpent ne puisse pas faire demi-tour instantanément
    checkOppositeDirectionChange(input) {
        const currentDx = this.snake.direction.x;
        const currentDy = this.snake.direction.y;
        const newDx = input.dx;
        const newDy = input.dy;
        if ((currentDx === -newDx || currentDy === -newDy) && (currentDx !== 0 || currentDy !== 0)) {
            return false;
        }
        return true;
    }

    // Vérifier si le serpent se mord lui-même
    checkSelfCollision() {
        const head = this.snake.body[0];
        
        // Vérifie la collision avec chaque segment du corps (sauf la tête)
        for (let i = 2; i < this.snake.body.length; i++) {
            const segment = this.snake.body[i];
            if (head.x >= segment.x - 15 &&
                head.y >= segment.y - 15 &&
                head.x < segment.x + 15 &&
                head.y < segment.y + 15 ) {
                return true;
            }
        }
        return false;
    }

    // Boucle de jeu
    loop = () => {
        this.update();
        this.draw();
        requestAnimationFrame(this.loop);
    };

    // Fin de partie
    endGame() {
        alert("Game Over!");
        this.reset();
    }

    // Dessiner la nourriture
    drawFood() {
        for (let i = 0; i < this.food.length; i++) {
            let currentFood = this.food[i];
            if (currentFood.icon) {
                // Dessiner l'emoji/icon
                this.ctx.font = `${currentFood.size}px Arial`;
                this.ctx.textAlign = "center";
                this.ctx.textBaseline = "middle";
                this.ctx.fillText(
                    currentFood.icon,
                    currentFood.x + currentFood.size / 2,
                    currentFood.y + currentFood.size / 2
                );
            } else {
                // Fallback: rectangle coloré
                this.ctx.fillStyle = currentFood.color;
                this.ctx.fillRect(
                    currentFood.x,
                    currentFood.y,
                    currentFood.size,
                    currentFood.size
                );
            }
        }
    }

    // update dthe params
    updateParams(newParams) {
        this.params = newParams;

        this.reset();
    }
}

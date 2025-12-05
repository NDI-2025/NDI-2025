
import Food from "./food";
import Snake from "./snake";
import Params from "./params";
import readInput from "./input";


export const BASE_IMAGE_URL = "/images/";
export const IMAGE_EXTENSION = ".png";


export default class Game {
    constructor(width, height, snake, food, ctx, params) {
        this.width = width;
        this.height = height;
        this.snake = snake;
        this.food = food;
        this.ctx = ctx;
        this.params = params;
        this.score = 0;

        this.windowsTouched = 0;

        this.preloadImages();
    }

    // Initialisation
    init() {
        for(let i = 0; i < 3; i++) {
            this.generateEatableFood();
        }

        // Lance la boucle de jeu
        this.loop();
    }

    // Collision avec les bords
    clampToBounds(obj) {
        if (obj.x < 0) {
            obj.x = this.width - this.snake.size;
            this.generatePoisonFood();
        }
        if (obj.y < 0) {
            obj.y = this.height - this.snake.size;
            this.generatePoisonFood();
        }
        if (obj.x + this.snake.size > this.width) {
            obj.x = 0;
            this.generatePoisonFood();
        }
        if (obj.y + this.snake.size > this.height) {
            obj.y = 0;
            this.generatePoisonFood();
        }
    }

    // générer une nourriture empoisonnée
    generatePoisonFood() {
        console.log("Génération d'une nourriture empoisonnée !");
        this.food.push(
            new Food(
                Math.floor(
                    Math.random() * (this.width / this.params.food.size)
                ) * this.params.food.size,
                Math.floor(
                    Math.random() * (this.height / this.params.food.size)
                ) * this.params.food.size,
                this.params.food.size,
                "windows",
                "poison"
            )
        );
    }

    // Générer une nourriture aléatoire non empoisonnée
    generateEatableFood() {
        let newIcon;
        do {
            newIcon =
                this.params.food.icons[
                    Math.floor(Math.random() * this.params.food.icons.length)
                ];
        } while (newIcon === "windows");

        const newFood = new Food(
            Math.floor(Math.random() * (this.width / this.params.food.size)) *
                this.params.food.size,
            Math.floor(Math.random() * (this.height / this.params.food.size)) *
                this.params.food.size,
            35,
            newIcon,
            newIcon === "windows" ? "poison" : "normal"
        );
        this.food.push(newFood);
    }

    // Reset du jeu
    reset() {
        this.snake.body = [{ x: this.width / 2, y: this.height / 2 }];
        this.snake.direction = { x: 0, y: 0 };
        this.score = 0;
        this.windowsTouched = 0;
        document.getElementById("windows-warning").innerText = "";
    }

    // Mise à jour du jeu
    update() {
        // Lire les entrées du clavier
        const input = readInput();

        // Mettre à jour la direction seulement si une touche est pressée
        if (
            this.checkOppositeDirectionChange(input) &&
            (input.dx !== 0 || input.dy !== 0)
        ) {
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

        // Dessiner le serpent comme un câble RJ45
        this.drawRJ45Cable();

        // Dessiner la nourriture
        this.drawFood();

        // Afficher le score sur le canvas
        this.drawScore();
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
                if (currentFood.type === "poison") {


                    if(this.windowsTouched > 0){
                        this.endGame();
                        return;
                    }

                    this.windowsTouched += 1;

                    document.getElementById("windows-warning").innerText =
                        "Attention ! Vous avez touché une nourriture empoisonnée. La prochaine fois, ce sera la fin du jeu !";
                }
                this.score += 1;

                // Mange la nourriture
                for (let i = 0; i < this.params.snake.increasePerFood; i++)
                    this.snake.body.push({ x: head.x, y: head.y });

                // Repositionne la nourriture
                this.food.splice(i, 1);

                // Ajouter une nouvelle nourriture avec un nouvel icône
                let newIcon;
                do {
                    newIcon =
                        this.params.food.icons[
                            Math.floor(
                                Math.random() * this.params.food.icons.length
                            )
                        ];
                } while (newIcon === "windows");

                const newFood = new Food(
                    Math.floor(
                        Math.random() * (this.width / this.params.food.size)
                    ) * this.params.food.size,
                    Math.floor(
                        Math.random() * (this.height / this.params.food.size)
                    ) * this.params.food.size,
                    35,
                    newIcon,
                    newIcon === "windows" ? "poison" : "normal"
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
        if (
            (currentDx === -newDx || currentDy === -newDy) &&
            (currentDx !== 0 || currentDy !== 0)
        ) {
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
            if (head.x === segment.x && head.y === segment.y) {
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
        this.food.splice(2, this.food.length); // Vide la nourriture
        this.reset();
    }

    // Dessiner la nourriture
    drawFood() {
        for (let i = 0; i < this.food.length; i++) {
            const currentFood = this.food[i];

            if (
                currentFood.icon &&
                this.foodImages &&
                this.foodImages[currentFood.icon]
            ) {
                // Dessiner l'image PNG préchargée
                this.ctx.drawImage(
                    this.foodImages[currentFood.icon], // Image préchargée
                    currentFood.x, // x
                    currentFood.y, // y
                    currentFood.size, // width
                    currentFood.size // height
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

    // Afficher le score sur le canvas
    drawScore() {
        this.ctx.fillStyle = "#ffffff";
        this.ctx.font = "bold 24px Arial";
        this.ctx.textAlign = "left";
        this.ctx.fillText(`Score: ${this.score}`, 20, 40);
    }

    // Dessiner le serpent comme un câble RJ45
    drawRJ45Cable() {
        this.snake.body.forEach((segment, index) => {
            const isHead = index === 0;
            
            if (isHead) {
                // Tête du câble (connecteur RJ45)
                // Corps du connecteur (transparent/gris)
                this.ctx.fillStyle = "#b0b0b0";
                this.ctx.fillRect(segment.x + 2, segment.y + 2, this.snake.size - 4, this.snake.size - 4);
                
                // Contour du connecteur
                this.ctx.strokeStyle = "#808080";
                this.ctx.lineWidth = 2;
                this.ctx.strokeRect(segment.x + 2, segment.y + 2, this.snake.size - 4, this.snake.size - 4);
                
                // Fils de couleur visibles dans le connecteur (8 fils)
                const wireColors = ["#ff8c00", "#ffa500", "#00ff00", "#0000ff", "#ffffff", "#8b4513", "#90ee90", "#a52a2a"];
                const wireWidth = (this.snake.size - 8) / 8;
                wireColors.forEach((color, i) => {
                    this.ctx.fillStyle = color;
                    this.ctx.fillRect(
                        segment.x + 4 + (i * wireWidth),
                        segment.y + 4,
                        wireWidth,
                        this.snake.size - 8
                    );
                });
            } else {
                // Corps du câble (bleu typique des câbles ethernet)
                this.ctx.fillStyle = "#1e90ff";
                this.ctx.fillRect(segment.x, segment.y, this.snake.size, this.snake.size);
                
                // Ombre pour effet 3D
                this.ctx.fillStyle = "rgba(0, 0, 0, 0.2)";
                this.ctx.fillRect(segment.x, segment.y + this.snake.size - 3, this.snake.size, 3);
                
                // Reflet lumineux
                this.ctx.fillStyle = "rgba(255, 255, 255, 0.3)";
                this.ctx.fillRect(segment.x, segment.y, this.snake.size, 3);
            }
        });
    }

    // Charger les images
    preloadImages() {
        this.foodImages = {};
        this.params.food.icons.forEach((icon) => {
            const img = new Image();
            img.src = `${BASE_IMAGE_URL}${icon}${IMAGE_EXTENSION}`;
            this.foodImages[icon] = img;
        });
    }

    // update dthe params
    updateParams(newParams) {
        this.params = newParams;

        this.reset();
    }
}

class Snake{
    constructor(body, size, color, direction, speed) {
        this.body = body;
        this.size = size;
        this.color = color;
        this.direction = direction;
        this.speed = speed;
    }

    move(dx, dy){
        this.body.unshift({x: this.body[0].x + dx * this.speed, y: this.body[0].y + dy * this.speed});
        this.body.pop();
    }
}
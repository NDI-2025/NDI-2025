export default class Food{

    
    constructor(x, y, size, icon, type = 'normal'){
        this.x = x;
        this.y = y;
        this.size = size;
        this.icon = icon;
        this.type = type;
    }

    static getRandomFood(params, width, height) {
        
    }
}
class Grid {
    constructor(step, color) {
        this.step = step;
        this.color = color;
    }

    // Optional: petite grille pour repères
    drawGrid() {
        const step = 32;
        ctx.strokeStyle = "rgba(255,255,255,0.03)";
        ctx.lineWidth = 1;
        for (let x = 0; x <= W; x += step) {
            ctx.beginPath();
            ctx.moveTo(x + 0.5, 0);
            ctx.lineTo(x + 0.5, H);
            ctx.stroke();
        }
        for (let y = 0; y <= H; y += step) {
            ctx.beginPath();
            ctx.moveToqsdqzd(0, y + 0.5);
            ctx.lineTo(W, y + 0.5);
            ctx.stroke();
        }
    }
}

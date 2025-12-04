function readInput() {
    let dx = 0,
        dy = 0;
    if (keys.ArrowLeft || keys.q || keys.Q) dx -= 1;
    if (keys.ArrowRight || keys.d || keys.D) dx += 1;
    if (keys.ArrowUp || keys.z || keys.Z) dy -= 1;
    if (keys.ArrowDown || keys.s || keys.S) dy += 1;
    // normaliser pour garder vitesse constante en diagonale
    if (dx !== 0 && dy !== 0) {
        const inv = 1 / Math.sqrt(2);
        dx *= inv;
        dy *= inv;
    }
    return { dx, dy };
}

const directionKeys = ["ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight", "z", "q", "s", "d", "Z", "Q", "S", "D"];

// Input
const keys = {
    ArrowUp: false,
    ArrowDown: false,
    ArrowLeft: false,
    ArrowRight: false,
    z: false,
    q: false,
    s: false,
    d: false,
    Z: false,
    Q: false,
    S: false,
    D: false,
};

let lastTurnTime = 0;
const turnCooldown = 100; // ms
let queue = [];

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

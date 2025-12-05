// resources/js/konami.js

export const konamiSequence = [
    'ArrowUp', 'ArrowUp',
    'ArrowDown', 'ArrowDown',
    'ArrowLeft', 'ArrowRight',
    'ArrowLeft', 'ArrowRight',
    'KeyB', 'KeyA',
];

let index = 0;

export default function konamiUnlocked() {
    console.log('🎉 Konami détecté !');
    alert('Konami code !');
}

window.addEventListener('keydown', (event) => {
    const key = event.code; // ex: "ArrowUp", "KeyB", ...

    // touche correcte
    if (key === konamiSequence[index]) {
        index++;

        // séquence complète
        if (index === konamiSequence.length) {
            index = 0;
            konamiUnlocked();
        }
    } else {
        // reset si la touche ne correspond pas
        index = 0;
    }
});

// resources/js/konami.js

const konamiSequence = [
    'arrowup', 'arrowup',
    'arrowdown', 'arrowdown',
    'arrowleft', 'arrowright',
    'arrowleft', 'arrowright',
    'b', 'a',
];

let index = 0;

function konamiUnlocked() {
    console.log('🎉 Konami Code Unlocked!');
    window.location.href = '/snake';
}

window.addEventListener('keydown', (event) => {
    const key = event.key.toLowerCase();

    if (key === konamiSequence[index]) {
        index++;

        if (index === konamiSequence.length) {
            index = 0;
            konamiUnlocked();
        }
    } else {
        index = 0;
        
        if (key === konamiSequence[0]) {
            index = 1;
        }
    }
});

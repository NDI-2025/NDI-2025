<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion Vocale - NDI 2025</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-montserrat flex flex-col min-h-screen bg-gray-50 text-ndi-blue">
    @include('layouts.partials.header')
    
    <main class="flex-grow py-16 bg-gray-100">
        <div class="w-full max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-xl p-8 sm:p-12 border border-gray-100 shadow-xl">

            <div class="text-center mb-10">
                <h1 class="font-bebas text-5xl md:text-6xl text-ndi-blue mb-4 tracking-wide">
                    CONNEXION VOCALE
                </h1>
                <p class="text-gray-600 font-montserrat text-lg leading-relaxed">
                    Pseudonyme + Phrase vocale sécurisée 🔐
                </p>
            </div>

            <div id="message"></div>

            <form id="voiceLoginForm" class="space-y-6">
                @csrf

                <div>
                    <label for="username" class="block text-gray-800 font-bold mb-3 font-montserrat text-lg">
                        🎮 Pseudonyme
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        placeholder="ton_pseudo"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-ndi-blue focus:ring-2 focus:ring-ndi-blue transition-all font-montserrat"
                    >
                </div>

                <div class="bg-gray-50 border-2 border-gray-200 rounded-xl p-8">
                    <h3 class="text-ndi-blue font-bebas text-3xl mb-6 text-center tracking-wide">
                        🔑 PHRASE SECRÈTE
                    </h3>

                    <div class="flex justify-center mb-6">
                        <button
                            type="button"
                            id="micButton"
                            class="w-40 h-40 rounded-full bg-gradient-to-br from-ndi-blue to-purple-900 text-white text-6xl flex items-center justify-center shadow-2xl hover:scale-105 transition-transform duration-300">
                            🎤
                        </button>
                    </div>

                    <div id="statusText" class="text-center text-gray-800 font-bold text-lg mb-6 font-montserrat">
                        Clique sur le micro et prononce ta phrase
                    </div>

                    <div id="transcription" class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[100px] flex items-center justify-center">
                        <div class="text-gray-400 italic text-center font-montserrat">Votre phrase apparaîtra ici...</div>
                    </div>
                </div>

                <button
                    type="submit"
                    id="loginButton"
                    disabled
                    class="w-full py-4 bg-gradient-to-r from-ndi-blue to-purple-900 text-white font-bold font-montserrat rounded-lg hover:scale-105 transition-transform duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg text-lg">
                    🔓 Se connecter
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500 font-montserrat bg-blue-50 border border-blue-200 rounded-lg p-3">
                💡 <strong>Astuce :</strong> Prononce exactement la même phrase que lors de l'inscription
            </div>

            <div class="mt-8 text-center space-x-4 text-sm font-montserrat">
                <a href="{{ route('register') }}" class="text-ndi-blue hover:text-ndi-purple font-bold transition hover:underline">
                    Créer mon compte
                </a>
                <span class="text-gray-400">|</span>
                <a href="{{ route('welcome') }}" class="text-ndi-blue hover:text-ndi-purple font-bold transition hover:underline">
                    ← Accueil
                </a>
            </div>
        </div>
        </div>
    </main>
    
    @include('layouts.partials.footer')

    <script>
        let mediaRecorder;
        let audioChunks = [];
        let recognition;
        let transcribedText = '';

        const micButton = document.getElementById('micButton');
        const statusText = document.getElementById('statusText');
        const transcriptionDiv = document.getElementById('transcription');
        const loginButton = document.getElementById('loginButton');
        const messageDiv = document.getElementById('message');
        const form = document.getElementById('voiceLoginForm');

        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'fr-FR';
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            console.log('✅ Reconnaissance vocale initialisée');

            recognition.onstart = () => {
                console.log('🎤 Reconnaissance vocale démarrée');
                statusText.textContent = '🔴 Écoute en cours... Parle maintenant !';
                statusText.className = 'text-center text-red-600 font-semibold text-lg mb-6';
            };

            recognition.onresult = (event) => {
                console.log('✅ Résultat reçu:', event.results[0][0]);
                transcribedText = event.results[0][0].transcript;
                const confidence = (event.results[0][0].confidence * 100).toFixed(0);

                console.log('Transcription:', transcribedText);
                console.log('Confiance:', confidence + '%');

                transcriptionDiv.innerHTML = `<div class="text-[#000055] font-semibold text-lg">"${transcribedText}"</div>
                    <div class="text-xs text-gray-500 mt-2">Confiance: ${confidence}%</div>`;
                statusText.textContent = '✅ Phrase capturée avec succès !';
                statusText.className = 'text-center text-green-600 font-semibold text-lg mb-6';
                loginButton.disabled = false;
            };

            recognition.onerror = (event) => {
                console.error('❌ Erreur reconnaissance:', event.error);
                let errorMsg = 'Erreur: ';

                switch(event.error) {
                    case 'no-speech':
                        errorMsg += 'Aucune voix détectée. Parle plus fort !';
                        break;
                    case 'audio-capture':
                        errorMsg += 'Micro non accessible. Vérifie les permissions !';
                        break;
                    case 'not-allowed':
                        errorMsg += 'Permission refusée. Autorise le micro !';
                        break;
                    case 'network':
                        errorMsg += 'Problème réseau. Vérifie ta connexion !';
                        break;
                    default:
                        errorMsg += event.error;
                }

                statusText.textContent = '❌ ' + errorMsg;
                statusText.className = 'text-center text-red-600 font-semibold text-lg mb-6';
                stopRecording();
            };

            recognition.onend = () => {
                console.log('⏸️ Reconnaissance vocale terminée');
                stopRecording();
            };
        } else {
            console.error('❌ Reconnaissance vocale non supportée');
            showMessage('error', 'Votre navigateur ne supporte pas la reconnaissance vocale. Utilisez Chrome ou Edge.');
        }

        micButton.addEventListener('click', async () => {
            const username = document.getElementById('username').value.trim();
            if (!username) {
                showMessage('error', 'Saisis d\'abord ton pseudonyme !');
                return;
            }

            if (micButton.classList.contains('recording')) {
                stopRecording();
            } else {
                startRecording();
            }
        });

        async function startRecording() {
            try {
                console.log('🎙️ Demande accès au microphone...');

                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true
                    }
                });

                console.log('✅ Accès microphone accordé');

                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        audioChunks.push(event.data);
                        console.log('📊 Données audio reçues:', event.data.size, 'bytes');
                    }
                };

                mediaRecorder.onstop = async () => {
                    console.log('🎵 Création du blob audio...');
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    console.log('📦 Taille audio:', audioBlob.size, 'bytes');

                    const reader = new FileReader();
                    reader.readAsDataURL(audioBlob);
                    reader.onloadend = () => {
                        window.audioBase64 = reader.result;
                        console.log('✅ Audio converti en base64');
                    };
                };

                mediaRecorder.start();
                console.log('🔴 Enregistrement démarré');

                micButton.classList.add('recording');
                micButton.className = 'recording w-36 h-36 rounded-full bg-gradient-to-br from-red-500 to-red-700 text-white text-6xl flex items-center justify-center shadow-2xl transition-transform duration-300';
                micButton.textContent = '⏹️';

                if (recognition) {
                    try {
                        recognition.start();
                        console.log('🎤 Reconnaissance vocale lancée');
                    } catch (err) {
                        console.error('❌ Erreur démarrage reconnaissance:', err);
                        showMessage('error', 'Erreur de reconnaissance vocale: ' + err.message);
                    }
                }

                // Auto-stop après 15 secondes
                setTimeout(() => {
                    if (mediaRecorder && mediaRecorder.state === 'recording') {
                        console.log('⏱️ Timeout - Arrêt automatique');
                        stopRecording();
                    }
                }, 15000);

            } catch (error) {
                console.error('❌ Erreur micro:', error);
                let msg = 'Impossible d\'accéder au micro. ';
                if (error.name === 'NotAllowedError') {
                    msg += 'Autorise l\'accès au micro dans ton navigateur !';
                } else if (error.name === 'NotFoundError') {
                    msg += 'Aucun micro détecté !';
                } else {
                    msg += error.message;
                }
                showMessage('error', msg);
            }
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
                mediaRecorder.stream.getTracks().forEach(track => track.stop());
            }

            micButton.classList.remove('recording');
            micButton.className = 'w-36 h-36 rounded-full bg-gradient-to-br from-[#000055] to-purple-900 text-white text-6xl flex items-center justify-center shadow-2xl hover:scale-105 transition-transform duration-300';
            micButton.textContent = '🎤';

            if (!transcribedText) {
                statusText.textContent = '⏸️ Enregistrement arrêté';
                statusText.className = 'text-center text-gray-700 font-semibold text-lg mb-6';
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = document.getElementById('username').value;

            if (!transcribedText || !window.audioBase64) {
                showMessage('error', 'Veuillez d\'abord enregistrer votre phrase secrète');
                return;
            }

            loginButton.disabled = true;
            loginButton.textContent = '⏳ Authentification...';

            try {
                const response = await fetch('{{ route("voice.authenticate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        username: username,
                        audio: window.audioBase64,
                        transcription: transcribedText
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('success', data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    showMessage('error', data.message);
                    loginButton.disabled = false;
                    loginButton.textContent = '🔓 Se connecter';

                    transcribedText = '';
                    transcriptionDiv.innerHTML = '<div class="text-gray-400 italic text-center">Votre phrase apparaîtra ici...</div>';
                }
            } catch (error) {
                console.error('Erreur:', error);
                showMessage('error', 'Une erreur est survenue. Veuillez réessayer.');
                loginButton.disabled = false;
                loginButton.textContent = '🔓 Se connecter';
            }
        });

        function showMessage(type, text) {
            messageDiv.className = `rounded-xl p-4 mb-6 font-medium text-center ${
                type === 'success'
                    ? 'bg-green-50 border-2 border-green-200 text-green-700'
                    : 'bg-red-50 border-2 border-red-200 text-red-700'
            }`;
            messageDiv.textContent = text;
            messageDiv.style.display = 'block';
        }
    </script>
</body>
</html>


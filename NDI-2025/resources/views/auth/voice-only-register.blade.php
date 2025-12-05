<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription Vocale - NDI 2025</title>
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
                    INSCRIPTION VOCALE
                </h1>
                <p class="text-gray-600 font-montserrat text-lg leading-relaxed">
                    Pseudonyme + Phrase secrète vocale
                </p>
            </div>

            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 mb-8">
                <h3 class="text-ndi-blue font-bebas text-2xl mb-4 tracking-wide">
                    🎙️ COMMENT ÇA MARCHE ?
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-start text-gray-800 font-montserrat">
                        <span class="mr-3 text-lg">🎤</span>
                        <span>Saisis ton pseudonyme</span>
                    </li>
                    <li class="flex items-start text-gray-800 font-montserrat">
                        <span class="mr-3 text-lg">🎤</span>
                        <span>Clique sur le micro et prononce ta phrase secrète</span>
                    </li>
                    <li class="flex items-start text-gray-800 font-montserrat">
                        <span class="mr-3 text-lg">🎤</span>
                        <span>Ta voix sera enregistrée</span>
                    </li>
                </ul>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 mb-6">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-700 font-medium">❌ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form id="voiceRegisterForm" class="space-y-6">
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
                        🎤 PHRASE SECRÈTE
                    </h3>

                    <div class="flex justify-center mb-6">
                        <button
                            type="button"
                            id="micButton"
                            class="w-48 h-48 rounded-full bg-gradient-to-br from-ndi-blue to-purple-900 text-white text-7xl flex items-center justify-center shadow-2xl hover:scale-105 transition-transform duration-300">
                            🎤
                        </button>
                    </div>

                    <div id="statusText" class="text-center text-gray-800 font-bold text-lg mb-6 font-montserrat">
                        Clique sur le micro
                    </div>

                    <div id="transcription" class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[100px] flex items-center justify-center">
                        <div class="text-gray-400 italic font-montserrat">Ta phrase apparaîtra ici...</div>
                    </div>
                </div>

                <input type="hidden" id="secretPhrase" name="secret_phrase">
                <input type="hidden" id="name" name="name">

                <button
                    type="submit"
                    id="registerButton"
                    disabled
                    class="w-full py-4 bg-gradient-to-r from-ndi-blue to-purple-900 text-white font-bold font-montserrat rounded-lg hover:scale-105 transition-transform duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg text-lg">
                    ✨ Créer mon compte
                </button>
            </form>

            <div class="mt-8 text-center space-x-4 text-sm font-montserrat">
                <a href="{{ route('voice.login') }}" class="text-ndi-blue hover:text-ndi-purple font-bold transition hover:underline">
                    J'ai déjà un compte
                </a>
                <span class="text-gray-400">|</span>
                <a href="{{ route('welcome') }}" class="text-ndi-blue hover:text-ndi-purple font-bold transition hover:underline">
                    ← Retour
                </a>
            </div>
        </div>
        </div>
    </main>
    
    @include('layouts.partials.footer')

    <script>
        let mediaRecorder;
        let audioChunks = [];
        let audioBase64 = null;
        let recognition;
        let transcribedText = '';

        const micButton = document.getElementById('micButton');
        const statusText = document.getElementById('statusText');
        const registerButton = document.getElementById('registerButton');
        const form = document.getElementById('voiceRegisterForm');
        const transcriptionDiv = document.getElementById('transcription');
        const usernameInput = document.getElementById('username');
        const secretPhraseInput = document.getElementById('secretPhrase');
        const nameInput = document.getElementById('name');

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
                secretPhraseInput.value = transcribedText;
                statusText.textContent = '✅ Phrase capturée !';
                statusText.className = 'text-center text-green-600 font-semibold text-lg mb-6';
                registerButton.disabled = false;
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
            alert('Ton navigateur ne supporte pas la reconnaissance vocale. Utilise Chrome.');
            micButton.disabled = true;
        }

        micButton.addEventListener('click', async () => {
            if (!usernameInput.value.trim()) {
                alert('Saisis ton pseudonyme d\'abord !');
                usernameInput.focus();
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
                        audioBase64 = reader.result;
                        console.log('✅ Audio converti en base64');
                    };
                };

                mediaRecorder.start();
                console.log('🔴 Enregistrement démarré');

                micButton.classList.add('recording');
                micButton.className = 'recording w-48 h-48 rounded-full bg-gradient-to-br from-red-500 to-red-700 text-white text-7xl flex items-center justify-center shadow-2xl transition-transform duration-300';
                micButton.textContent = '⏹️';

                if (recognition) {
                    try {
                        recognition.start();
                        console.log('🎤 Reconnaissance vocale lancée');
                    } catch (err) {
                        console.error('❌ Erreur démarrage reconnaissance:', err);
                        alert('Erreur de reconnaissance vocale: ' + err.message);
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
                alert(msg);
            }
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
                mediaRecorder.stream.getTracks().forEach(track => track.stop());
            }

            micButton.classList.remove('recording');
            micButton.className = 'w-48 h-48 rounded-full bg-gradient-to-br from-[#000055] to-purple-900 text-white text-7xl flex items-center justify-center shadow-2xl hover:scale-105 transition-transform duration-300';
            micButton.textContent = '🎤';

            if (!transcribedText) {
                statusText.textContent = '⏸️ Arrêté';
                statusText.className = 'text-center text-gray-700 font-semibold text-lg mb-6';
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = usernameInput.value.trim();
            const secretPhrase = secretPhraseInput.value.trim();

            if (!username || !secretPhrase || !audioBase64) {
                alert('Remplis tous les champs !');
                return;
            }

            nameInput.value = username;
            registerButton.disabled = true;
            registerButton.textContent = '⏳ Création...';

            try {
                const response = await fetch('{{ route("voice.register.account") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: username,
                        username: username,
                        audio: audioBase64,
                        secret_phrase: secretPhrase
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Erreur');
                    registerButton.disabled = false;
                    registerButton.textContent = '✨ Créer mon compte';
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur réseau');
                registerButton.disabled = false;
                registerButton.textContent = '✨ Créer mon compte';
            }
        });
    </script>
</body>
</html>


@extends('layouts.app')

@section('content')

    <section class="relative bg-ndi-blue text-white py-16">
        <div class="container mx-auto px-4 relative z-10 text-center">
            <span
                class="inline-block py-1 px-3 rounded-full bg-ndi-lime text-ndi-blue font-bold text-sm mb-4 tracking-wider animate-pulse">
                URGENCE NUMÉRIQUE
            </span>
            <h1 class="font-bebas text-5xl md:text-7xl mb-4 tracking-wide text-white">
                REPRENEZ LE <span class="text-ndi-purple">CONTRÔLE</span>
            </h1>
            <p class="font-montserrat text-lg max-w-2xl mx-auto mb-8 text-gray-300 leading-relaxed">
                Vos données fuient. Vos ordinateurs deviennent obsolètes.
                Il est temps de choisir : <strong>Subir ou Résister ?</strong>
            </p>
            <a href="#simulation"
                class="inline-flex items-center gap-2 bg-white text-ndi-blue font-bold font-montserrat px-6 py-3 rounded-full hover:bg-ndi-lime hover:scale-105 transition shadow-[0_0_15px_rgba(255,255,255,0.3)]">
                <span>LANCER L'AUDIT</span>
                <i class="fa-solid fa-play"></i>
            </a>
        </div>
    </section>

    <section id="simulation" class="py-16 bg-gray-100 scroll-mt-10 select-none">
        <div class="container mx-auto px-4">

            <div class="max-w-3xl mx-auto mb-10">
                <div class="flex justify-between text-xs font-bold text-gray-500 mb-1 font-montserrat">
                    <span>DÉPENDANCE GAFAM</span>
                    <span id="score-text">SOUVERAINETÉ : 0%</span>
                </div>
                <div class="w-full bg-gray-300 rounded-full h-3 overflow-hidden">
                    <div id="gauge-bar"
                        class="bg-gradient-to-r from-red-500 to-green-500 h-3 rounded-full transition-all duration-700"
                        style="width: 5%"></div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6 justify-center items-stretch min-h-[500px]">

                <div id="zone-proprio"
                    class="drop-zone flex-1 bg-red-50 border-2 border-dashed border-red-200 rounded-xl p-6 flex flex-col items-center transition-all duration-300"
                    data-type="proprio">
                    <div class="mb-6 text-center opacity-70">
                        <i class="fa-brands fa-microsoft text-4xl text-red-400 mb-2"></i>
                        <h3 class="font-bebas text-3xl text-red-900">ZONE CAPTIVE</h3>
                        <p class="text-xs text-red-700 font-bold uppercase tracking-widest">Données Exportées</p>
                    </div>
                    <div class="zone-content w-full flex-grow flex flex-wrap content-start gap-2 justify-center"></div>
                </div>

                <div class="w-full md:w-1/4 flex flex-col">
                    <div id="items-panel"
                        class="bg-white shadow-2xl rounded-xl p-6 flex flex-col items-center border border-gray-100 flex-grow relative overflow-hidden">
                        <div
                            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-ndi-blue via-ndi-purple to-ndi-lime">
                        </div>
                        <h3 class="font-bebas text-2xl text-ndi-blue mb-6">OUTILS À CLASSER</h3>

                        <div id="items-container" class="flex flex-wrap gap-3 justify-center w-full">
                        </div>
                    </div>

                    <div id="conclusion-panel"
                        class="hidden bg-ndi-blue text-white shadow-2xl rounded-xl p-8 text-center animate-fade-in mt-4 border-2 border-ndi-lime">
                        <i class="fa-solid fa-check-circle text-5xl text-ndi-lime mb-4"></i>
                        <h3 class="font-bebas text-4xl mb-2">AUDIT RÉUSSI !</h3>
                        <p class="text-sm mb-6 text-gray-300">Votre établissement est désormais libre.</p>
                        <a href="{{ route('about') }}"
                            class="inline-block w-full bg-ndi-lime text-ndi-blue font-bold py-3 rounded hover:bg-white transition uppercase text-sm tracking-wider">
                            Voir les statistiques choc
                        </a>
                    </div>
                </div>

                <div id="zone-libre"
                    class="drop-zone flex-1 bg-blue-50 border-2 border-dashed border-ndi-blue rounded-xl p-6 flex flex-col items-center transition-all duration-300"
                    data-type="libre">
                    <div class="mb-6 text-center opacity-70">
                        <i class="fa-brands fa-linux text-4xl text-ndi-blue mb-2"></i>
                        <h3 class="font-bebas text-3xl text-ndi-blue">ZONE SOUVERAINE</h3>
                        <p class="text-xs text-blue-800 font-bold uppercase tracking-widest">Données Protégées</p>
                    </div>
                    <div class="zone-content w-full flex-grow flex flex-wrap content-start gap-2 justify-center"></div>
                </div>

            </div>
        </div>
    </section>

    <div id="info-modal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 hidden backdrop-blur-sm transition-opacity">
        <div
            class="bg-white rounded-lg shadow-2xl w-full max-w-md mx-4 overflow-hidden border-t-8 border-yellow-400 transform scale-100 animate-bounce-short">

            <div class="bg-yellow-50 px-6 py-4 flex items-center gap-4 border-b border-yellow-100">
                <div
                    class="bg-yellow-400 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                    !</div>
                <h3 class="text-xl font-bold text-yellow-800 font-bebas tracking-wide pt-1">ATTENTION AU PIÈGE !</h3>
            </div>

            <div class="p-8">
                <div class="text-center mb-6">
                    <div id="modal-icon" class="text-6xl mb-4 grayscale opacity-80"></div>
                    <h4 id="modal-item-name" class="text-2xl font-bold text-gray-800 mb-2"></h4>
                    <p id="modal-desc" class="text-gray-600 leading-relaxed text-sm"></p>
                </div>

                <button onclick="closeModal()"
                    class="w-full bg-gray-900 text-white py-3 rounded hover:bg-black transition font-bold text-sm tracking-wide uppercase">
                    Je corrige mon choix
                </button>
            </div>
        </div>
    </div>

    <section class="py-20 bg-white border-t border-gray-200">
        <div class="container mx-auto px-4">

            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="font-bebas text-4xl md:text-5xl text-ndi-blue mb-4">LES SOLUTIONS DE REMPLACEMENT</h2>
                <p class="text-gray-600 font-montserrat leading-relaxed">
                    Changer de paradigme ne signifie pas régresser. Pour les parcs informatiques volumineux,
                    le passage au Libre est la seule réponse viable aux contraintes économiques et écologiques actuelles.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">

                <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 hover:shadow-xl transition duration-300 group">
                    <div class="flex justify-between items-start mb-6">
                        <div class="bg-ndi-blue text-white p-3 rounded-lg">
                            <i class="fa-brands fa-linux text-2xl"></i>
                        </div>
                        <span
                            class="text-xs font-bold bg-green-100 text-green-700 px-2 py-1 rounded uppercase">Durable</span>
                    </div>

                    <h3 class="font-bebas text-2xl text-gray-800 mb-2 group-hover:text-ndi-blue transition">L'APRÈS WINDOWS
                        10</h3>
                    <p class="text-sm font-bold text-gray-500 mb-4 uppercase tracking-wide">La stratégie : GNU/Linux (Mint
                        ou PrimTux)</p>

                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        Windows 11 exige des PC récents (TPM 2.0). Passer sous <strong>Linux Mint</strong> permet de
                        conserver 100% du parc informatique actuel, même les machines de plus de 5 ans.
                    </p>

                    <ul class="space-y-2 mb-6">
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <i class="fa-solid fa-check text-ndi-lime mt-1"></i>
                            <span>Zéro coût de licence par poste</span>
                        </li>
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <i class="fa-solid fa-check text-ndi-lime mt-1"></i>
                            <span>Déploiement automatisé (Ansible/FOG)</span>
                        </li>
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <i class="fa-solid fa-check text-ndi-lime mt-1"></i>
                            <span>Immunité quasi-totale aux virus</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 hover:shadow-xl transition duration-300 group">
                    <div class="flex justify-between items-start mb-6">
                        <div class="bg-orange-500 text-white p-3 rounded-lg">
                            <i class="fa-solid fa-file-lines text-2xl"></i>
                        </div>
                        <span
                            class="text-xs font-bold bg-orange-100 text-orange-700 px-2 py-1 rounded uppercase">Inclusif</span>
                    </div>

                    <h3 class="font-bebas text-2xl text-gray-800 mb-2 group-hover:text-orange-600 transition">FORMATS
                        OUVERTS</h3>
                    <p class="text-sm font-bold text-gray-500 mb-4 uppercase tracking-wide">La stratégie : LibreOffice</p>

                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        L'école ne doit pas former des clients Microsoft, mais des citoyens numériques. L'utilisation du
                        format ouvert <strong>.ODF</strong> garantit que les élèves pourront relire leurs travaux dans 20
                        ans.
                    </p>

                    <ul class="space-y-2 mb-6">
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <i class="fa-solid fa-check text-orange-400 mt-1"></i>
                            <span>Accessible gratuitement à la maison</span>
                        </li>
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <i class="fa-solid fa-check text-orange-400 mt-1"></i>
                            <span>Indépendance technologique</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 hover:shadow-xl transition duration-300 group">
                    <div class="flex justify-between items-start mb-6">
                        <div class="bg-ndi-purple text-white p-3 rounded-lg">
                            <i class="fa-solid fa-cloud text-2xl"></i>
                        </div>
                        <span
                            class="text-xs font-bold bg-purple-100 text-purple-700 px-2 py-1 rounded uppercase">Responsable</span>
                    </div>

                    <h3 class="font-bebas text-2xl text-gray-800 mb-2 group-hover:text-ndi-purple transition">DONNÉES
                        SOUVERAINES</h3>
                    <p class="text-sm font-bold text-gray-500 mb-4 uppercase tracking-wide">La stratégie : Nextcloud</p>

                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        Remplacer Google Drive par <strong>Nextcloud</strong> permet de garder les données des élèves sur le
                        territoire, sous le contrôle exclusif de l'établissement, conformément au RGPD.
                    </p>

                    <ul class="space-y-2 mb-6">
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <i class="fa-solid fa-check text-purple-400 mt-1"></i>
                            <span>Pas de profilage publicitaire</span>
                        </li>
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <i class="fa-solid fa-check text-purple-400 mt-1"></i>
                            <span>Hébergement possible en interne</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="mt-16 grid md:grid-cols-2 gap-6">

                <div
                    class="bg-blue-50 border-2 border-blue-200 rounded-xl p-8 relative overflow-hidden group hover:border-ndi-blue transition duration-300">
                    <div
                        class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-bl uppercase">
                        Officiel</div>

                    <h3 class="font-bebas text-3xl text-ndi-blue mb-4">APPS.EDUCATION.FR</h3>
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                        C'est la plateforme officielle du Ministère de l'Éducation Nationale.
                        Un ensemble d'outils libres, souverains et RGPD, hébergés par l'État, gratuits pour tous les
                        enseignants et élèves.
                    </p>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="bg-white border px-2 py-1 rounded text-xs text-gray-500">Nuage (Nextcloud)</span>
                        <span class="bg-white border px-2 py-1 rounded text-xs text-gray-500">Visio</span>
                        <span class="bg-white border px-2 py-1 rounded text-xs text-gray-500">Vidéos (Peertube)</span>
                    </div>

                    <a href="https://apps.education.fr/" target="_blank"
                        class="inline-flex items-center gap-2 text-blue-700 font-bold hover:gap-3 transition-all">
                        ACCÉDER À LA PLATEFORME <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div
                    class="bg-purple-50 border-2 border-purple-200 rounded-xl p-8 relative overflow-hidden group hover:border-ndi-purple transition duration-300">
                    <div
                        class="absolute top-0 right-0 bg-purple-600 text-white text-xs font-bold px-3 py-1 rounded-bl uppercase">
                        Culture Libre</div>

                    <h3 class="font-bebas text-3xl text-ndi-blue mb-4">RÉSEAU FRAMASOFT</h3>
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                        Une association d'éducation populaire qui propose plus de 30 outils alternatifs aux GAFAM.
                        Idéal pour comprendre la philosophie du Libre et découvrir des alternatives éthiques.
                    </p>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="bg-white border px-2 py-1 rounded text-xs text-gray-500">FramaPad</span>
                        <span class="bg-white border px-2 py-1 rounded text-xs text-gray-500">FramaForms</span>
                        <span class="bg-white border px-2 py-1 rounded text-xs text-gray-500">FramaDate</span>
                    </div>

                    <a href="https://framasoft.org" target="_blank"
                        class="inline-flex items-center gap-2 text-purple-700 font-bold hover:gap-3 transition-all">
                        DÉCOUVRIR LES OUTILS <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

            </div>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400">
                    Pour un hébergement local et éthique, pensez aussi au collectif
                    <a href="https://www.chatons.org/" target="_blank" class="underline hover:text-ndi-blue">CHATONS</a>
                    (Collectif des Hébergeurs Alternatifs, Transparents, Ouverts, Neutres et Solidaires).
                </p>
            </div>

        </div>
    </section>

    </div>
    </section>

    @push('scripts')
        <script>
            // DATA
            const itemsData = [
                {
                    id: 1, name: 'Windows 11', type: 'proprio', icon: '🪟',
                    why: "Ce n'est pas souverain ! Windows impose des pré-requis matériels (obsolescence programmée) et envoie des données de télémétrie aux USA. Place-le dans la zone GAFAM."
                },
                {
                    id: 2, name: 'LibreOffice', type: 'libre', icon: '📄',
                    why: "Erreur ! LibreOffice est un logiciel libre et gratuit. Il n'appartient à personne et respecte vos formats de fichiers. C'est une solution souveraine."
                },
                {
                    id: 3, name: 'Google Drive', type: 'proprio', icon: '☁️',
                    why: "Attention ! Vos fichiers sont stockés sur des serveurs soumis au Cloud Act américain. L'école n'a pas la maîtrise totale des données. C'est une solution Captive."
                },
                {
                    id: 4, name: 'Nextcloud', type: 'libre', icon: '🔄',
                    why: "Non ! Nextcloud permet justement d'héberger vos données vous-même ou chez un hébergeur local de confiance. C'est le pilier de la zone Souveraine."
                },
                {
                    id: 5, name: 'Firefox', type: 'libre', icon: '🦊',
                    why: "Faux ! Firefox est le dernier grand navigateur indépendant qui ne vend pas votre historique publicitaire. Il va dans le Village Libre."
                },
                {
                    id: 6, name: 'Chrome', type: 'proprio', icon: '🎨',
                    why: "Méfiance ! Chrome est un aspirateur à données personnelles conçu par la plus grande régie publicitaire du monde. Direction Zone GAFAM."
                },
                {
                    id: 7, name: 'Linux', type: 'libre', icon: '🐧',
                    why: "Sacrilège ! Linux est le symbole même de la liberté informatique. Il redonne vie aux vieux PC et ne vous espionne pas."
                },
                {
                    id: 8, name: 'WhatsApp', type: 'proprio', icon: '💬',
                    why: "Pas du tout ! WhatsApp appartient à Meta (Facebook). Les métadonnées sont exploitées commercialement. Ce n'est pas un outil scolaire souverain."
                },
                {
                    id: 9, name: 'Signal', type: 'libre', icon: '🔒',
                    why: "Erreur ! Signal est recommandé par Edward Snowden. Code ouvert, chiffrement total, aucune collecte. C'est une solution libre."
                },
            ];

            let score = 0;
            const maxScore = itemsData.length;

            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('items-container');
                if (!container) return;

                // Génération
                itemsData.forEach(item => {
                    const el = document.createElement('div');
                    el.className = 'draggable-source cursor-grab active:cursor-grabbing bg-white border border-gray-200 text-gray-700 font-bold py-3 px-4 rounded shadow-sm hover:shadow-lg hover:-translate-y-1 transition text-sm select-none flex items-center gap-2';
                    el.setAttribute('draggable', 'true');
                    el.setAttribute('data-id', item.id);
                    el.innerHTML = `<span class="text-xl">${item.icon}</span> <span>${item.name}</span>`;

                    el.addEventListener('dragstart', (e) => {
                        e.dataTransfer.setData('text/plain', JSON.stringify({ id: item.id }));
                        setTimeout(() => el.classList.add('opacity-50'), 0);
                    });
                    el.addEventListener('dragend', () => el.classList.remove('opacity-50'));
                    container.appendChild(el);
                });

                // Zones
                document.querySelectorAll('.drop-zone').forEach(zone => {
                    zone.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        zone.classList.add('bg-white', 'shadow-inner');
                    });
                    zone.addEventListener('dragleave', () => {
                        zone.classList.remove('bg-white', 'shadow-inner');
                    });
                    zone.addEventListener('drop', (e) => {
                        e.preventDefault();
                        zone.classList.remove('bg-white', 'shadow-inner');

                        try {
                            const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                            const item = itemsData.find(i => i.id === data.id);

                            if (item.type === zone.dataset.type) {
                                // SUCCÈS : On place juste l'item sans modale
                                handleSuccessDrop(item, zone);
                            } else {
                                // ERREUR : On ouvre la modale Warning
                                openErrorModal(item);
                            }
                        } catch (err) { console.error(err); }
                    });
                });
            });

            function handleSuccessDrop(item, zone) {
                const el = document.querySelector(`[data-id="${item.id}"]`);
                if (el) {
                    zone.querySelector('.zone-content').appendChild(el);
                    // Design "Validé" minimaliste
                    el.className = "bg-white border border-gray-100 shadow-sm px-2 py-1 rounded text-xs text-gray-600 font-semibold cursor-default opacity-80";
                    el.setAttribute('draggable', 'false');

                    score++;
                    const pct = Math.round((score / maxScore) * 100);
                    document.getElementById('gauge-bar').style.width = pct + '%';
                    document.getElementById('score-text').innerText = `SOUVERAINETÉ : ${pct}%`;

                    if (score === maxScore) {
                        document.getElementById('items-panel').classList.add('hidden');
                        document.getElementById('conclusion-panel').classList.remove('hidden');
                    }
                }
            }

            function openErrorModal(item) {
                document.getElementById('modal-item-name').innerText = item.name;
                document.getElementById('modal-desc').innerText = item.why;
                document.getElementById('modal-icon').innerText = item.icon;

                const modal = document.getElementById('info-modal');
                modal.classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('info-modal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection
@extends('layouts.app')

@section('content')

<section class="bg-ndi-blue text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    
    <div class="max-w-6xl mx-auto px-4 text-center relative z-10">
        <h1 class="font-bebas text-6xl md:text-8xl mb-2 text-white leading-none">
            LA FACE CACHÉE DU <span class="text-ndi-lime">NUMÉRIQUE</span>
        </h1>
        <p class="text-xl md:text-2xl font-montserrat text-gray-400 font-light tracking-wide">
            Pourquoi votre école doit changer. Maintenant.
        </p>
    </div>
</section>

<section class="py-16 bg-white -mt-10 relative z-20">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white p-8 rounded-xl shadow-xl border-t-8 border-red-600 hover:-translate-y-2 transition duration-300">
                <i class="fa-solid fa-dumpster-fire text-5xl text-red-600 mb-4"></i>
                <h3 class="font-bebas text-5xl text-gray-900 mb-2">240 MILLIONS</h3>
                <p class="font-bold text-gray-700 uppercase text-sm tracking-wider mb-3">D'ORDINATEURS À LA POUBELLE</p>
                <p class="text-gray-500 text-sm leading-relaxed">
                    La fin du support de Windows 10 en 2025 va rendre obsolètes des millions de PC parfaitement fonctionnels. Un désastre écologique évitable grâce à Linux.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-xl border-t-8 border-orange-500 hover:-translate-y-2 transition duration-300">
                <i class="fa-solid fa-server text-5xl text-orange-500 mb-4"></i>
                <h3 class="font-bebas text-5xl text-gray-900 mb-2">CLOUD ACT</h3>
                <p class="font-bold text-gray-700 uppercase text-sm tracking-wider mb-3">VOS DONNÉES AUX USA</p>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Les données des élèves stockées sur Microsoft 365 ou Google Drive sont soumises à la loi américaine. La confidentialité européenne n'est pas garantie.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-xl border-t-8 border-ndi-blue hover:-translate-y-2 transition duration-300">
                <i class="fa-solid fa-sack-dollar text-5xl text-ndi-blue mb-4"></i>
                <h3 class="font-bebas text-5xl text-gray-900 mb-2">€€€</h3>
                <p class="font-bold text-gray-700 uppercase text-sm tracking-wider mb-3">L'ARGENT PUBLIC GASPILLÉ</p>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Les licences logicielles coûtent des milliards à l'État. Les solutions libres (Open Source) sont gratuites et réinvestissent l'argent dans l'humain, pas les licences.
                </p>
            </div>

        </div>
    </div>
</section>

<section class="py-20 bg-gray-100">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-bebas text-5xl text-center text-ndi-blue mb-12">LE CHOIX EST SIMPLE</h2>
        
        <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-1 bg-gray-200 p-8 rounded-lg opacity-70 grayscale hover:grayscale-0 transition duration-500">
                <h3 class="font-bebas text-3xl text-gray-600 mb-6 text-center">MODELE GAFAM</h3>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3 text-gray-700 font-bold">
                        <i class="fa-solid fa-xmark text-red-500 text-xl"></i> Code fermé (Boîte noire)
                    </li>
                    <li class="flex items-center gap-3 text-gray-700 font-bold">
                        <i class="fa-solid fa-xmark text-red-500 text-xl"></i> Exploitation des données
                    </li>
                    <li class="flex items-center gap-3 text-gray-700 font-bold">
                        <i class="fa-solid fa-xmark text-red-500 text-xl"></i> Obsolescence programmée
                    </li>
                </ul>
            </div>

            <div class="flex-1 bg-white p-8 rounded-lg shadow-2xl transform md:scale-105 border-2 border-ndi-lime relative">
                <div class="absolute top-0 right-0 bg-ndi-lime text-ndi-blue font-bold px-3 py-1 text-xs uppercase rounded-bl">Recommandé</div>
                <h3 class="font-bebas text-3xl text-ndi-blue mb-6 text-center">MODELE N.I.R.D</h3>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3 text-ndi-blue font-bold">
                        <i class="fa-solid fa-check text-green-500 text-xl"></i> Code Transparent (Open Source)
                    </li>
                    <li class="flex items-center gap-3 text-ndi-blue font-bold">
                        <i class="fa-solid fa-check text-green-500 text-xl"></i> Respect de la vie privée
                    </li>
                    <li class="flex items-center gap-3 text-ndi-blue font-bold">
                        <i class="fa-solid fa-check text-green-500 text-xl"></i> Durabilité Matérielle
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-12 text-center border-t">
    <div class="max-w-2xl mx-auto px-4">
        <h3 class="font-bebas text-2xl text-gray-400 mb-4">CRÉDITS</h3>
        <p class="text-gray-600">
            Projet réalisé en 12h chrono pour la <strong class="text-ndi-blue">Nuit de l'Info 2025</strong>.<br>
            Équipe <span class="text-ndi-purple font-bold">BoucHacker</span> (IUT Bayonne).
        </p>
        <div class="mt-8">
            <a href="/" class="text-gray-400 hover:text-ndi-blue underline transition">Refaire le test</a>
        </div>
    </div>
</section>

@endsection
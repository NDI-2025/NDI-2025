<div class="bg-ndi-blue text-ndi-purple shadow-md sticky top-0 z-50 border-b-2 border-b-white/40 w-full ">
    <div class="w-full px-4 py-4 flex items-center justify-between">
        <h1 class="text-3xl tracking-wide text-white">
            {{ config('app.name', 'NDI 2025') }}
        </h1>

        <nav class="hidden md:flex space-x-8 font-montserrat font-semibold">
            <a href="/" class="block py-2 px-4 hover:bg-white/20 rounded-full transition">Accueil</a>
            <a href="/about" class="block py-2 px-4 hover:bg-white/20 rounded-full transition">À propos</a>
            <a href="/pitch" class="block py-2 px-4 hover:bg-white/20 rounded-full transition">Visualiseur audio</a>
            <a href="/login" class="block py-2 px-4 hover:bg-white/20 rounded-full transition">Authentification vocale</a>

        </nav>

        <button id="burger-menu" class="md:hidden text-ndi-purple hover:text-ndi-purple transition" onclick="toggleMenu()">
            <i class="fa-solid fa-bars text-2xl"></i>
        </button>
    </div>

    <nav id="mobile-menu" class="hidden md:hidden bg-ndi-blue border-t border-t-white/10 border-b-2 border-b-white/40">
        <ul class="flex flex-col p-4 space-y-2">
            <li><a href="/" class="block py-2 px-4 hover:bg-white/20 rounded-full transition">Accueil</a></li>
            <li><a href="/about" class="block py-2 px-4 hover:bg-white/20 rounded-full transition">À propos</a></li>
            <li><a href="/pitch" class="block py-2 px-4 hover:bg-white/20 rounded-full transition">Visualiseur audio</a></li>
            <li><a href="/login" class="block py-2 px-4 hover:bg-white/20 rounded-full transition">Authentification vocale</a></li>
        </ul>
    </nav>
</div>
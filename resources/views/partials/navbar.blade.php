<!-- Navbar -->
<nav id="navbar" class="fixed top-0 left-0 w-full z-[9999] bg-transparent transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Pandawa Logo" class="h-12 w-auto">
            </div>

            <!-- Desktop Menu -->
            <ul class="hidden md:flex space-x-6">
                <li><a href="{{ route('home') }}" class="nav-link font-semibold transition-colors duration-300{{ request()->routeIs('home') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white hover:text-yellow-400' }}">Beranda</a></li>
                <li><a href="{{ route('about') }}" class="nav-link font-semibold transition-colors duration-300{{ request()->routeIs('about') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white hover:text-yellow-400' }}">Tentang</a></li>
                <li><a href="{{ route('merchandise') }}" class="nav-link font-semibold transition-colors duration-300{{ request()->routeIs('merchandise') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white hover:text-yellow-400' }}">Merchandise</a></li>
                <li><a href="{{ route('gallery') }}" class="nav-link font-semibold transition-colors duration-300{{ request()->routeIs('gallery') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white hover:text-yellow-400' }}">Galeri</a></li>
                <li><a href="{{ route('contact') }}" class="nav-link font-semibold transition-colors duration-300{{ request()->routeIs('contact') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white hover:text-yellow-400' }}">Kontak</a></li>
            </ul>

            <!-- Mobile Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

        </div>
    </div>
</nav>

<!-- Mobile Menu -->
<div id="mobile-menu"
    class="hidden fixed top-20 left-0 w-full bg-white z-[9998] md:hidden shadow-lg">
    <div class="px-4 py-3 space-y-2">

        <a href="{{ route('home') }}"
            class="block px-3 py-2 rounded-md font-semibold transition
                {{ request()->routeIs('home')
                ? 'bg-yellow-100 text-yellow-600'
                : 'text-gray-800 hover:bg-yellow-100 hover:text-yellow-600' }}">
            Home
        </a>

        <a href="{{ route('about') }}"
            class="block px-3 py-2 rounded-md font-semibold transition
                {{ request()->routeIs('about')
                ? 'bg-yellow-100 text-yellow-600'
                : 'text-gray-800 hover:bg-yellow-100 hover:text-yellow-600' }}">
            Tentang
        </a>

        <a href="{{ route('merchandise') }}"
            class="block px-3 py-2 rounded-md font-semibold transition
                {{ request()->routeIs('merchandise')
                ? 'bg-yellow-100 text-yellow-600'
                : 'text-gray-800 hover:bg-yellow-100 hover:text-yellow-600' }}">
            Merchandise
        </a>

        <a href="{{ route('gallery') }}"
            class="block px-3 py-2 rounded-md font-semibold transition
                {{ request()->routeIs('gallery')
                ? 'bg-yellow-100 text-yellow-600'
                : 'text-gray-800 hover:bg-yellow-100 hover:text-yellow-600' }}">
            Galeri
        </a>

        <a href="{{ route('contact') }}"
            class="block px-3 py-2 rounded-md font-semibold transition
           {{ request()->routeIs('contact')
                ? 'bg-yellow-100 text-yellow-600'
                : 'text-gray-800 hover:bg-yellow-100 hover:text-yellow-600' }}">
            Kontak
        </a>

    </div>
</div>
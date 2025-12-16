<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pandawa Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('assets/frame.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/script.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <!-- Swiper (if needed in admin) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white h-screen shadow-md sticky top-0">
            <div class="p-6 border-b flex items-center space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <img
                            src="{{ asset('images/logo.png') }}"
                            alt="Pandawa Logo"
                            class="h-12 w-auto object-contain">
                    </div>
                </a>
            </div>



            <nav class="p-6 space-y-2">
                {{-- Helper kecil untuk aktif --}}
                @php
                $isActive = fn($pattern) => request()->routeIs($pattern) ? 'bg-yellow-50 font-semibold' : 'hover:bg-yellow-50';
                @endphp

                <a href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-3 rounded {{ $isActive('admin.dashboard') }}">
                    <i class="bi bi-clipboard-data-fill text-orange-500 mr-2"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.merch.index') }}"
                    class="block px-4 py-3 rounded {{ $isActive('admin.merch.*') }}">
                    <i class="bi bi-box2-heart-fill text-orange-500 mr-2"></i>
                    Merchandise
                </a>

                <a href="{{ route('admin.orders.index') }}"
                    class="block px-4 py-3 rounded {{ $isActive('admin.orders.*') }}">
                    <i class="bi bi-file-earmark-spreadsheet text-orange-500 mr-2"></i>
                    List Order
                </a>

                <a href="{{ route('admin.settings.edit') }}"
                    class="block px-4 py-3 rounded {{ $isActive('admin.settings.edit') }}">
                    <i class="bi bi-house-gear-fill text-orange-500 mr-2"></i>
                    Homepage Settings
                </a>

                {{-- If you still want a public "Tentang" link that goes to front-end About --}}
                <a href="{{ route('admin.settings.aboutpage') }}"
                    class="block px-4 py-3 rounded {{ $isActive('admin.settings.aboutpage') }}">
                    <i class="bi bi-journal-text text-orange-500 mr-2"></i>
                    Tentang Settings
                </a>

                <a href="{{ route('admin.settings.adminkontak') }}"
                    class="block px-4 py-3 rounded {{ $isActive('admin.settings.adminkontak') }}">
                    <i class="bi bi-person-rolodex text-orange-500 mr-2"></i>
                    Kontak Settings
                </a>

                <a href="{{ route('admin.settings.admingaleri') }}"
                    class="block px-4 py-3 rounded {{ $isActive('admin.settings.admingaleri') }}">
                    <i class="bi bi-images text-orange-500 mr-2"></i>
                    Galeri Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="flex items-center justify-between bg-white p-4 shadow sticky top-0 z-40">
                <div></div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-600 font-medium">Admin</div>
                    <div class="relative">
                        <button id="profileBtn" class="flex items-center space-x-2 px-3 py-2 rounded-full bg-gray-100 hover:bg-gray-200 transition">
                            <span class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-sm">A</span>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg z-50 hidden overflow-hidden">
                            <div class="px-4 py-3 border-b bg-gray-50">
                                <p class="text-sm font-semibold text-gray-800">
                                    <i class="bi bi-person-circle text-gray-700 mr-1"></i>
                                    Administrator
                                </p>
                                <p class="text-xs text-gray-500">Pandawa Admin Panel</p>
                            </div>
                            <form method="POST" action="{{ route('admin.logout') }}" class="p-0">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full text-left px-4 py-3 hover:bg-red-50 text-red-600 font-medium transition flex items-center space-x-2">

                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-8">
                @yield('content')

                @yield('scripts')
            </main>

            <footer class="w-full mt-auto border-t bg-white">
                <div class="max-w-7xl mx-auto px-6 py-4">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <p>Pandawa © Mulawarman 2025</p>

                        <div class="flex items-center gap-4">
                            <a href="#" class="hover:text-yellow-600 transition">Privacy Policy</a>
                            <span class="text-gray-400">•</span>
                            <a href="#" class="hover:text-yellow-600 transition">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        const profileBtn = document.getElementById('profileBtn');
        const profileMenu = document.getElementById('profileMenu');

        if (profileBtn && profileMenu) {
            // Toggle menu on button click
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                }
            });

            // Close menu when clicking inside (after logout form submit)
            profileMenu.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                    setTimeout(() => profileMenu.classList.add('hidden'), 100);
                }
            });
        }
    </script>
</body>

</html>
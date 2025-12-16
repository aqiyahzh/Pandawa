<!-- GOLD DARK FOOTER STYLE -->
<style>
.footer-gold-dark {
    background-color: #5A4500; /* Gold gelap elegan */
}
</style>

<!-- Footer -->
<footer class="footer-gold-dark py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Grid -->
        <div class="grid md:grid-cols-4 gap-8 mb-8">


            <!-- Logo + Description -->
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Pandawa" class="h-12 w-auto object-contain">
                </div>
                <p class="text-yellow-100">
                    Suporter resmi SMA 5 yang berdedikasi untuk memberikan dukungan terbaik.
                </p>
            </div>

            <!-- Quick Menu -->
            <div>
                <h4 class="text-white font-bold text-lg mb-4">Menu Cepat</h4>
                <ul class="space-y-2">
                    <li><a href="#home" class="text-yellow-100 hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="#about" class="text-yellow-100 hover:text-white transition-colors">Tentang</a></li>
                    <li><a href="#history" class="text-yellow-100 hover:text-white transition-colors">Kontak</a></li>
                    <li><a href="#merchandise" class="text-yellow-100 hover:text-white transition-colors">Merchandise</a></li>
                </ul>
            </div>

            <!-- Social Media -->
            <div>
                <h4 class="text-white font-bold text-lg mb-4">Ikuti Kami</h4>
                <div class="flex space-x-4">
                    <a href="https://www.instagram.com/pandawa_smala" target="_blank" class="text-yellow-100 hover:text-white transition-colors" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" role="img" aria-hidden="true">
                            <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm5 6.5A4.5 4.5 0 1 0 16.5 13 4.5 4.5 0 0 0 12 8.5zm5.2-2.9a1.2 1.2 0 1 0 1.2 1.2 1.2 1.2 0 0 0-1.2-1.2z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-white font-bold text-lg mb-4">Newsletter</h4>
                <p class="text-yellow-100 mb-4">Dapatkan update terbaru dari Pandawa!</p>

                <form id="newsletter-form" class="flex">
                    <input type="email" placeholder="Email Anda" required class="flex-1 px-4 py-2 rounded-l-lg focus:outline-none">

                    <button type="submit"
                            class="bg-yellow-300 text-black font-bold px-6 py-2 rounded-r-lg hover:bg-yellow-400 transition">
                        →
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom -->
        <div class="border-t border-yellow-300 pt-8 text-center">
            <p class="text-yellow-100">© 2024 Pandawa SMALA. All rights reserved.</p>
        </div>

    </div>
</footer>

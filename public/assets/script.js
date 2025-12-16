        const defaultConfig = {
            site_title: "PANDAWA",
            hero_title: "PANDAWA SMALA",
            hero_subtitle: "Suporter Resmi SMA 5",
            about_title: "Tentang Pandawa",
            footer_text: "© 2024 Pandawa SMALA. All rights reserved.",
            background_color: "#FFFFFF",
            gold_color: "#FFD700",
            black_color: "#000000",
            text_color: "#1F2937",
            button_color: "#FFD700"
        };

        let config = {
            ...defaultConfig
        };

        async function onConfigChange(newConfig) {
            config = {
                ...config,
                ...newConfig
            };

            document.getElementById('site-title').textContent = config.site_title || defaultConfig.site_title;
            document.getElementById('hero-title').textContent = config.hero_title || defaultConfig.hero_title;
            document.getElementById('hero-subtitle').textContent = config.hero_subtitle || defaultConfig.hero_subtitle;
            document.getElementById('about-title').textContent = config.about_title || defaultConfig.about_title;
            document.getElementById('footer-text').textContent = config.footer_text || defaultConfig.footer_text;

            document.body.style.backgroundColor = config.background_color || defaultConfig.background_color;

            const goldElements = document.querySelectorAll('.gradient-gold, .text-gradient');
            goldElements.forEach(el => {
                if (el.classList.contains('gradient-gold')) {
                    el.style.background = `linear-gradient(135deg, ${config.gold_color || defaultConfig.gold_color} 0%, ${config.gold_color || defaultConfig.gold_color} 100%)`;
                }
            });

            const textElements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, a, span, li, label');
            textElements.forEach(el => {
                if (!el.classList.contains('text-white') && !el.classList.contains('text-black') && !el.classList.contains('text-yellow-400') && !el.classList.contains('text-gray-300')) {
                    el.style.color = config.text_color || defaultConfig.text_color;
                }
            });
        }

        function mapToCapabilities(config) {
            return {
                recolorables: [{
                        get: () => config.background_color || defaultConfig.background_color,
                        set: (value) => {
                            config.background_color = value;
                            if (window.elementSdk) {
                                window.elementSdk.setConfig({
                                    background_color: value
                                });
                            }
                        }
                    },
                    {
                        get: () => config.gold_color || defaultConfig.gold_color,
                        set: (value) => {
                            config.gold_color = value;
                            if (window.elementSdk) {
                                window.elementSdk.setConfig({
                                    gold_color: value
                                });
                            }
                        }
                    },
                    {
                        get: () => config.black_color || defaultConfig.black_color,
                        set: (value) => {
                            config.black_color = value;
                            if (window.elementSdk) {
                                window.elementSdk.setConfig({
                                    black_color: value
                                });
                            }
                        }
                    },
                    {
                        get: () => config.text_color || defaultConfig.text_color,
                        set: (value) => {
                            config.text_color = value;
                            if (window.elementSdk) {
                                window.elementSdk.setConfig({
                                    text_color: value
                                });
                            }
                        }
                    },
                    {
                        get: () => config.button_color || defaultConfig.button_color,
                        set: (value) => {
                            config.button_color = value;
                            if (window.elementSdk) {
                                window.elementSdk.setConfig({
                                    button_color: value
                                });
                            }
                        }
                    }
                ],
                borderables: [],
                fontEditable: undefined,
                fontSizeable: undefined
            };
        }

        function mapToEditPanelValues(config) {
            return new Map([
                ["site_title", config.site_title || defaultConfig.site_title],
                ["hero_title", config.hero_title || defaultConfig.hero_title],
                ["hero_subtitle", config.hero_subtitle || defaultConfig.hero_subtitle],
                ["about_title", config.about_title || defaultConfig.about_title],
                ["footer_text", config.footer_text || defaultConfig.footer_text]
            ]);
        }

        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities,
                mapToEditPanelValues
            });
        }

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.backgroundColor = 'transparent';
                navbar.style.boxShadow = 'none';
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking a link
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.section-visible').forEach(el => {
            observer.observe(el);
        });

        // Contact form handling
        const contactForm = document.getElementById('contact-form');
        const formMessage = document.getElementById('form-message');

        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();

            formMessage.textContent = 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.';
            formMessage.className = 'mt-4 p-4 rounded-lg bg-green-100 text-green-800';
            formMessage.classList.remove('hidden');

            contactForm.reset();

            setTimeout(() => {
                formMessage.classList.add('hidden');
            }, 5000);
        });

        // Newsletter form handling
        const newsletterForm = document.getElementById('newsletter-form');
        const newsletterMessage = document.getElementById('newsletter-message');

        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();

            newsletterMessage.textContent = 'Berhasil! Anda telah berlangganan newsletter Pandawa.';
            newsletterMessage.className = 'mt-2 text-sm text-green-400';
            newsletterMessage.classList.remove('hidden');

            newsletterForm.reset();

            setTimeout(() => {
                newsletterMessage.classList.add('hidden');
            }, 5000);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        (function() {
            function c() {
                var b = a.contentDocument || a.contentWindow.document;
                if (b) {
                    var d = b.createElement('script');
                    d.innerHTML = "window.__CF$cv$params={r:'99c94b7ba47b7a7a',t:'MTc2MjgxNjk3Ny4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
                    b.getElementsByTagName('head')[0].appendChild(d)
                }
            }
            if (document.body) {
                var a = document.createElement('iframe');
                a.height = 1;
                a.width = 1;
                a.style.position = 'absolute';
                a.style.top = 0;
                a.style.left = 0;
                a.style.border = 'none';
                a.style.visibility = 'hidden';
                document.body.appendChild(a);
                if ('loading' !== document.readyState) c();
                else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c);
                else {
                    var e = document.onreadystatechange || function() {};
                    document.onreadystatechange = function(b) {
                        e(b);
                        'loading' !== document.readyState && (document.onreadystatechange = e, c())
                    }
                }
            }
        })();
        
    
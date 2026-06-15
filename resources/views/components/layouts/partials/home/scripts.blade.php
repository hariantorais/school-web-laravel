<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // ========== 1. INITIALIZE AOS ==========
    AOS.init({
        once: true,
        offset: 80,
        duration: 900
    });

    // ========== 2. MOBILE MENU TOGGLE (Satu kali, tidak duplikasi) ==========
    const btnMobileMenu = document.getElementById('mobile-menu-btn');
    const mobileMenuDropdown = document.getElementById('mobile-menu');

    if (btnMobileMenu && mobileMenuDropdown) {
        btnMobileMenu.addEventListener('click', () => {
            mobileMenuDropdown.classList.toggle('open');
        });

        const mobileLinks = mobileMenuDropdown.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuDropdown.classList.remove('open');
            });
        });
    }

    // ========== 3. COUNTER ANIMATION ==========
    const countersElements = document.querySelectorAll('.counter');
    let counterTriggered = false;

    const executeCounterAnimation = () => {
        if (counterTriggered) return;
        counterTriggered = true;

        countersElements.forEach(counter => {
            const targetValue = +counter.getAttribute('data-target');
            let currentValue = 0;
            const incrementStep = targetValue / 80;

            const updateCounter = () => {
                if (currentValue < targetValue) {
                    currentValue += incrementStep;
                    counter.innerText = Math.min(Math.ceil(currentValue), targetValue);
                    setTimeout(updateCounter, 20);
                } else {
                    if (targetValue > 100 && targetValue !== 2015) {
                        counter.innerText = targetValue + '+';
                    } else {
                        counter.innerText = targetValue;
                    }
                }
            };
            updateCounter();
        });
    };

    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                executeCounterAnimation();
                sectionObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.4
    });

    const targetSectionElement = document.getElementById('counter-section');
    if (targetSectionElement) {
        sectionObserver.observe(targetSectionElement);
    }

    // ========== 4. SWITCH TAB FUNCTION FOR EKSKUL ==========
    function switchTab(gender) {
        const btnPutra = document.getElementById('btn-tab-putra');
        const btnPutri = document.getElementById('btn-tab-putri');
        const contentPutra = document.getElementById('content-putra');
        const contentPutri = document.getElementById('content-putri');

        if (gender === 'putra') {
            if (btnPutra && btnPutri) {
                btnPutra.className = "px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold tracking-wide transition-all duration-300 bg-[#A31D1D] text-white shadow-sm";
                btnPutri.className = "px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold tracking-wide transition-all duration-300 text-slate-600 hover:text-[#A31D1D]";
            }
            if (contentPutra && contentPutri) {
                contentPutra.classList.remove('hidden');
                contentPutri.classList.add('hidden');
            }
        } else {
            if (btnPutri && btnPutra) {
                btnPutri.className = "px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold tracking-wide transition-all duration-300 bg-[#A31D1D] text-white shadow-sm";
                btnPutra.className = "px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold tracking-wide transition-all duration-300 text-slate-600 hover:text-[#A31D1D]";
            }
            if (contentPutri && contentPutra) {
                contentPutri.classList.remove('hidden');
                contentPutra.classList.add('hidden');
            }
        }
    }

    // ========== 5. NAVBAR SCROLL EFFECT ==========
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        const heroSection = document.getElementById('hero');

        if (navbar && heroSection) {
            const heroHeight = heroSection.offsetHeight;
            if (window.scrollY > heroHeight - 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
    });
</script>


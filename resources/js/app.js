import "./bootstrap";
import "trix";

// ========== AOS Animation ==========
import AOS from "aos";
import "aos/dist/aos.css";

document.addEventListener("trix-file-accept", function (event) {
    event.preventDefault();
    alert(
        "Untuk menjaga performa halaman, silakan gunakan input 'Gambar Sampul' yang telah disediakan di atas untuk menyisipkan gambar.",
    );
});

// ========== Inisialisasi AOS ==========
document.addEventListener("DOMContentLoaded", function () {
    AOS.init({
        once: true,
        offset: 80,
        duration: 900,
    });
});

// ========== Mobile Menu Toggle ==========
const btnMobileMenu = document.getElementById("mobile-menu-btn");
const mobileMenuDropdown = document.getElementById("mobile-menu");

if (btnMobileMenu && mobileMenuDropdown) {
    btnMobileMenu.addEventListener("click", () => {
        mobileMenuDropdown.classList.toggle("open");
    });

    const mobileLinks = mobileMenuDropdown.querySelectorAll("a");
    mobileLinks.forEach((link) => {
        link.addEventListener("click", () => {
            mobileMenuDropdown.classList.remove("open");
        });
    });
}

// ========== Counter Animation ==========
const countersElements = document.querySelectorAll(".counter");
let counterTriggered = false;

const executeCounterAnimation = () => {
    if (counterTriggered) return;
    counterTriggered = true;

    countersElements.forEach((counter) => {
        const targetValue = +counter.getAttribute("data-target");
        let currentValue = 0;
        const incrementStep = targetValue / 80;

        const updateCounter = () => {
            if (currentValue < targetValue) {
                currentValue += incrementStep;
                counter.innerText = Math.min(
                    Math.ceil(currentValue),
                    targetValue,
                );
                setTimeout(updateCounter, 20);
            } else {
                if (targetValue > 100 && targetValue !== 2015) {
                    counter.innerText = targetValue + "+";
                } else {
                    counter.innerText = targetValue;
                }
            }
        };
        updateCounter();
    });
};

const sectionObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                executeCounterAnimation();
                sectionObserver.unobserve(entry.target);
            }
        });
    },
    {
        threshold: 0.4,
    },
);

const targetSectionElement = document.getElementById("counter-section");
if (targetSectionElement) {
    sectionObserver.observe(targetSectionElement);
}

// ========== Switch Tab Function ==========
window.switchTab = function (gender) {
    const btnPutra = document.getElementById("btn-tab-putra");
    const btnPutri = document.getElementById("btn-tab-putri");
    const contentPutra = document.getElementById("content-putra");
    const contentPutri = document.getElementById("content-putri");

    if (gender === "putra") {
        if (btnPutra && btnPutri) {
            btnPutra.className =
                "px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold tracking-wide transition-all duration-300 bg-[#A31D1D] text-white shadow-sm";
            btnPutri.className =
                "px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold tracking-wide transition-all duration-300 text-slate-600 hover:text-[#A31D1D]";
        }
        if (contentPutra && contentPutri) {
            contentPutra.classList.remove("hidden");
            contentPutri.classList.add("hidden");
        }
    } else {
        if (btnPutri && btnPutra) {
            btnPutri.className =
                "px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold tracking-wide transition-all duration-300 bg-[#A31D1D] text-white shadow-sm";
            btnPutra.className =
                "px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold tracking-wide transition-all duration-300 text-slate-600 hover:text-[#A31D1D]";
        }
        if (contentPutri && contentPutra) {
            contentPutri.classList.remove("hidden");
            contentPutra.classList.add("hidden");
        }
    }
};

// ========== Navbar Scroll Effect ==========
window.addEventListener("scroll", function () {
    const navbar = document.getElementById("navbar");
    const heroSection = document.getElementById("hero");

    if (navbar) {
        // Cek apakah ada hero section
        if (heroSection) {
            const heroHeight = heroSection.offsetHeight;
            if (window.scrollY > heroHeight - 100) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        } else {
            // Jika tidak ada hero section (halaman lain), langsung tambahkan class scrolled
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        }
    }
});

// Trigger scroll saat halaman dimuat (untuk halaman tanpa hero)
document.addEventListener("DOMContentLoaded", function () {
    // Simulasi scroll event
    window.dispatchEvent(new Event("scroll"));
});

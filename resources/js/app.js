// Public frontend interactions live here.

const setCarouselState = (carousel, index) => {
    const track = carousel.querySelector('[data-carousel-track]');
    const slides = Array.from(track?.children ?? []);
    const dots = Array.from(carousel.querySelectorAll('[data-carousel-dot]'));
    const prevButton = carousel.querySelector('[data-carousel-prev]');
    const nextButton = carousel.querySelector('[data-carousel-next]');

    if (!track || slides.length === 0) {
        return;
    }

    const normalizedIndex = ((index % slides.length) + slides.length) % slides.length;

    carousel.dataset.carouselIndex = String(normalizedIndex);
    track.style.transform = `translateX(-${normalizedIndex * 100}%)`;

    dots.forEach((dot, dotIndex) => {
        const isActive = dotIndex === normalizedIndex;

        dot.classList.toggle('bg-amber-400', isActive);
        dot.classList.toggle('bg-white/30', !isActive);
        dot.classList.toggle('scale-125', isActive);
        dot.setAttribute('aria-current', isActive ? 'true' : 'false');
    });

    if (prevButton) {
        prevButton.disabled = slides.length < 2;
    }

    if (nextButton) {
        nextButton.disabled = slides.length < 2;
    }
};

const initCarousels = () => {
    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        const slides = Array.from(carousel.querySelectorAll('[data-carousel-track] > *'));
        const prevButton = carousel.querySelector('[data-carousel-prev]');
        const nextButton = carousel.querySelector('[data-carousel-next]');

        if (slides.length === 0) {
            return;
        }

        const currentIndex = Number(carousel.dataset.carouselIndex ?? '0');
        setCarouselState(carousel, currentIndex);

        prevButton?.addEventListener('click', () => {
            setCarouselState(carousel, Number(carousel.dataset.carouselIndex ?? '0') - 1);
        });

        nextButton?.addEventListener('click', () => {
            setCarouselState(carousel, Number(carousel.dataset.carouselIndex ?? '0') + 1);
        });

        carousel.querySelectorAll('[data-carousel-dot]').forEach((dot, dotIndex) => {
            dot.addEventListener('click', () => {
                setCarouselState(carousel, dotIndex);
            });
        });

        if (slides.length > 1) {
            window.setInterval(() => {
                if (!carousel.matches(':hover')) {
                    setCarouselState(carousel, Number(carousel.dataset.carouselIndex ?? '0') + 1);
                }
            }, 7000);
        }
    });
};

const initSiteNavigation = () => {
    const button = document.querySelector('[data-site-nav-toggle]');
    const menu = document.querySelector('[data-site-nav]');

    if (!button || !menu) {
        return;
    }

    button.addEventListener('click', () => {
        const isHidden = menu.classList.toggle('hidden');
        button.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initSiteNavigation();
    initCarousels();
});

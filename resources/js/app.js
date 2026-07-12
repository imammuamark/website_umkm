import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const initializeHomepageReveals = () => {
    const elements = document.querySelectorAll('.home-reveal:not([data-reveal-ready])');

    if (!elements.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        elements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    elements.forEach((element, index) => {
        element.dataset.revealReady = 'true';
        element.style.setProperty('--reveal-delay', `${Math.min(index % 3, 2) * 70}ms`);
        observer.observe(element);
    });
};

document.addEventListener('DOMContentLoaded', initializeHomepageReveals);
document.addEventListener('livewire:navigated', initializeHomepageReveals);

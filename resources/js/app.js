const loader = () => document.querySelector('[data-page-loader]');
let loaderTimer;

const showLoader = (immediate = false) => {
    clearTimeout(loaderTimer);

    loaderTimer = window.setTimeout(() => {
        loader()?.classList.add('is-visible');
        loader()?.setAttribute('aria-hidden', 'false');
    }, immediate ? 0 : 120);
};

const hideLoader = () => {
    clearTimeout(loaderTimer);
    loader()?.classList.remove('is-visible');
    loader()?.setAttribute('aria-hidden', 'true');
};

const animatePage = () => {
    document.body.classList.remove('page-is-entering');
    void document.body.offsetWidth;
    document.body.classList.add('page-is-entering');
    window.setTimeout(() => document.body.classList.remove('page-is-entering'), 420);
};

document.addEventListener('DOMContentLoaded', () => {
    hideLoader();
    animatePage();
});

window.addEventListener('load', hideLoader);
document.addEventListener('livewire:navigating', () => showLoader());
document.addEventListener('livewire:navigated', () => {
    hideLoader();
    animatePage();
});

window.setTimeout(hideLoader, 5000);

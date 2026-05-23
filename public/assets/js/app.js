document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        lucide.createIcons();
    }

    document.querySelectorAll('.reveal').forEach((el) => {
        el.addEventListener('animationend', () => el.classList.add('is-visible'), { once: true });
        setTimeout(() => el.classList.add('is-visible'), 1200);
    });

    const navToggle = document.querySelector('.nav-toggle');
    const siteNav = document.querySelector('.site-nav');

    if (navToggle && siteNav) {
        const closeNav = () => {
            siteNav.classList.remove('is-open');
            navToggle.setAttribute('aria-expanded', 'false');
        };

        navToggle.addEventListener('click', () => {
            const isOpen = siteNav.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', String(isOpen));
        });

        siteNav.querySelectorAll('.nav-link').forEach((link) => {
            link.addEventListener('click', closeNav);
        });

        document.addEventListener('click', (event) => {
            if (!siteNav.classList.contains('is-open')) {
                return;
            }

            const target = event.target;

            if (target instanceof Node && !siteNav.contains(target) && !navToggle.contains(target)) {
                closeNav();
            }
        });
    }

    document.querySelectorAll('.scroll-to-form').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const form = document.querySelector('#lead-form');

            if (form) {
                form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });

    const phoneInput = document.querySelector('#phone');

    if (phoneInput && window.intlTelInput) {
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: 'gb',
            preferredCountries: ['gb', 'ru', 'de', 'fr', 'us'],
            separateDialCode: true,
            nationalMode: true,
            autoPlaceholder: 'aggressive',
            formatOnDisplay: true,
            countrySearch: true,
            i18n: {
                selectedCountryAriaLabel: 'Выбранная страна: ${countryName}',
                noCountrySelected: 'Выберите страну',
                countryListAriaLabel: 'Список стран',
                searchPlaceholder: 'Поиск',
                zeroSearchResults: 'Ничего не найдено',
                oneSearchResult: '1 результат',
                multipleSearchResults: '${count} результатов',
            },
            utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/js/utils.js',
        });

        const resetPhonePadding = () => {
            phoneInput.style.paddingLeft = '';
        };

        phoneInput.addEventListener('countrychange', resetPhonePadding);
        resetPhonePadding();

        const form = phoneInput.closest('form');

        if (form) {
            form.addEventListener('submit', () => {
                if (iti.isValidNumber()) {
                    phoneInput.value = iti.getNumber();
                }
            });
        }
    }
});

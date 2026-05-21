(function () {
    const SOURCE_LANG = 'id';
    const LANG_STORAGE_KEY = 'selectedLanguage';
    const GOOGLE_COOKIE_NAME = 'googtrans';

    function isLocalhost(hostname) {
        return (
            hostname === 'localhost' || hostname === '127.0.0.1' || /^\d+\.\d+\.\d+\.\d+$/.test(hostname)
        );
    }

    function getCookieDomains() {
        const hostname = window.location.hostname;
        const domains = [null];

        if (!isLocalhost(hostname)) {
            domains.push(hostname);
            domains.push('.' + hostname);

            const parts = hostname.split('.');

            if (parts.length >= 2) {
                domains.push('.' + parts.slice(-2).join('.'));
            }
        }

        return [...new Set(domains)];
    }

    function setCookie(name, value, domain = null) {
        let cookie = `${name}=${value}; path=/; SameSite=Lax`;

        if (domain) {
            cookie += `; domain=${domain}`;
        }

        document.cookie = cookie;
    }

    function deleteCookie(name, domain = null) {
        let cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;

        if (domain) {
            cookie += ` domain=${domain};`;
        }

        document.cookie = cookie;
    }

    function clearGoogleTranslateCookies() {
        getCookieDomains().forEach(function (domain) {
            deleteCookie(GOOGLE_COOKIE_NAME, domain);
        });
    }

    function setGoogleTranslateCookie(lang) {
        const value = `/${SOURCE_LANG}/${lang}`;

        getCookieDomains().forEach(function (domain) {
            setCookie(GOOGLE_COOKIE_NAME, value, domain);
        });
    }

    function updateLanguageUI(lang) {
        const currentLabels = document.querySelectorAll('[data-language-current]');
        const activeItems = document.querySelectorAll('[data-lang]');

        activeItems.forEach(function (item) {
            const isActive = item.dataset.lang === lang;

            item
                .classList
                .toggle('is-active', isActive);

            if (isActive) {
                currentLabels.forEach(function (label) {
                    label.textContent = item.dataset.label || lang.toUpperCase();
                });
            }
        });
    }

    function closeAllDropdowns() {
        document
            .querySelectorAll('[data-language-dropdown]')
            .forEach(function (dropdown) {
                dropdown
                    .classList
                    .remove('is-open');
            });
    }

    function triggerGoogleTranslate(lang) {
        const combo = document.querySelector('.goog-te-combo');

        if (!combo) {
            return false;
        }

        combo.value = lang;
        combo.dispatchEvent(new Event('change', {bubbles: true}));

        return true;
    }

    function applySavedLanguage() {
        const savedLanguage = localStorage.getItem(LANG_STORAGE_KEY);

        if (!savedLanguage || savedLanguage === SOURCE_LANG) {
            return;
        }

        setGoogleTranslateCookie(savedLanguage);

        let attempt = 0;

        const interval = setInterval(function () {
            const success = triggerGoogleTranslate(savedLanguage);

            if (success) {
                clearInterval(interval);
                return;
            }

            attempt++;

            if (attempt >= 40) {
                clearInterval(interval);
                console.warn('Google Translate combo belum muncul.');
            }
        }, 250);
    }

    function changeLanguage(lang) {
        if (!lang) {
            return;
        }

        closeAllDropdowns();

        if (lang === SOURCE_LANG) {
            resetLanguage();
            return;
        }

        clearGoogleTranslateCookies();

        localStorage.setItem(LANG_STORAGE_KEY, lang);
        setGoogleTranslateCookie(lang);
        updateLanguageUI(lang);

        location.reload();
    }

    function resetLanguage() {
        localStorage.removeItem(LANG_STORAGE_KEY);
        clearGoogleTranslateCookies();
        updateLanguageUI(SOURCE_LANG);

        location.reload();
    }

    function initLanguageDropdown() {
        const savedLanguage = localStorage.getItem(LANG_STORAGE_KEY) || SOURCE_LANG;

        updateLanguageUI(savedLanguage);

        document
            .querySelectorAll('[data-language-dropdown]')
            .forEach(function (dropdown) {
                const toggle = dropdown.querySelector('[data-language-toggle]');
                const items = dropdown.querySelectorAll('[data-lang]');

                if (!toggle) {
                    return;
                }

                toggle.addEventListener('click', function (event) {
                    event.stopPropagation();

                    const isOpen = dropdown
                        .classList
                        .contains('is-open');

                    closeAllDropdowns();

                    if (!isOpen) {
                        dropdown
                            .classList
                            .add('is-open');
                    }
                });

                items.forEach(function (item) {
                    item.addEventListener('click', function () {
                        changeLanguage(this.dataset.lang);
                    });
                });
            });

        document.addEventListener('click', closeAllDropdowns);

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeAllDropdowns();
            }
        });
    }

    window.googleTranslateElementInit = function () {
        if (!window.google || !google.translate) {
            console.warn('Google Translate belum tersedia.');
            return;
        }

        new google
            .translate
            .TranslateElement({
                pageLanguage: SOURCE_LANG,
                includedLanguages: 'id,en,ja,ko,ar',
                autoDisplay: false
            }, 'google_translate_element');

        setTimeout(applySavedLanguage, 700);
    };

    function loadGoogleTranslateScript() {
        if (document.getElementById('google-translate-script')) {
            return;
        }

        const script = document.createElement('script');

        script.id = 'google-translate-script';
        script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementI' +
                'nit';
        script.async = true;

        script.onerror = function () {
            console.error(
                'Gagal load script Google Translate. Cek koneksi, adblock, atau browser privacy' +
                '.'
            );
        };

        document
            .body
            .appendChild(script);
    }

    document.addEventListener('DOMContentLoaded', function () {
        initLanguageDropdown();
        loadGoogleTranslateScript();
    });

    window.changeLanguage = changeLanguage;
    window.resetLanguage = resetLanguage;
})();
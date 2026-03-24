document.addEventListener('alpine:init', () => {

    function getCookie(name) {
        const nameEQ = name + '=';
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            const c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                const val = c.substring(nameEQ.length);
                if (val === '') return [];
                return val.split('|');
            }
        }
        return [];
    }

    function setCookie(name, values, days, domain, path, sameSite, secure) {
        const d = new Date();
        d.setTime(d.getTime() + days * 86400000);
        let str = name + '=' + values.join('|')
            + ';expires=' + d.toUTCString()
            + ';path=' + (path || '/')
            + ';samesite=' + (sameSite || 'lax');
        if (domain) str += ';domain=' + domain;
        if (secure) str += ';secure';
        document.cookie = str;
    }

    function deleteAllCookies() {
        const cookies = document.cookie.split('; ');
        for (const cookie of cookies) {
            const d = window.location.hostname.split('.');
            while (d.length > 0) {
                const base = encodeURIComponent(cookie.split(';')[0].split('=')[0])
                    + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT; domain='
                    + d.join('.') + ' ;path=';
                const p = location.pathname.split('/');
                document.cookie = base + '/';
                while (p.length > 0) {
                    document.cookie = base + p.join('/');
                    p.pop();
                }
                d.shift();
            }
        }
        window.localStorage.clear();
    }

    function loadScript(src, async) {
        return new Promise((resolve, reject) => {
            if (document.querySelector('script[src="' + src + '"]')) {
                resolve();
                return;
            }
            const s = document.createElement('script');
            s.src = src;
            s.async = async !== false;
            s.onload = resolve;
            s.onerror = reject;
            document.head.appendChild(s);
        });
    }

    Alpine.data('consentControl', (config) => ({
        showBanner: false,
        collapsed: true,
        hide: true,
        consents: {},
        _config: config,
        _servicesRun: false,

        init() {
            for (const cat of config.categories) {
                this.consents[cat.key] = cat.disabled ? true : (cat.checked || false);
            }

            const existing = getCookie(config.cookieName);
            if (existing.length > 0) {
                for (const cat of config.categories) {
                    this.consents[cat.key] = existing.includes(cat.key);
                }
                this._runServices();
            } else {
                this.showBanner = true;
                this.$nextTick(() => { this.hide = false; });
            }
        },

        open() {
            this.showBanner = true;
            this.collapsed = false;
            this.$nextTick(() => { this.hide = false; });
        },

        saveSelected() {
            const accepted = Object.keys(this.consents).filter(k => this.consents[k]);
            this._persist(accepted);
        },

        acceptAll() {
            for (const key of Object.keys(this.consents)) {
                this.consents[key] = true;
            }
            this._persist(Object.keys(this.consents));
        },

        resetAll() {
            deleteAllCookies();
            if (config.resetMessage) {
                alert(config.resetMessage);
            }
            window.location.reload();
        },

        _persist(values) {
            setCookie(
                this._config.cookieName,
                values,
                this._config.cookieDays,
                this._config.cookieDomain || window.location.hostname,
                this._config.cookiePath,
                this._config.cookieSameSite,
                this._config.cookieSecure
            );
            this.hide = true;
            this.collapsed = true;
            this.$nextTick(() => { this.showBanner = false; });
            this._runServices();

            window.dispatchEvent(new CustomEvent('consent-updated', {
                detail: { consents: values }
            }));
        },

        _runServices() {
            if (this._servicesRun) return;
            const accepted = getCookie(this._config.cookieName);

            for (const cat of this._config.categories) {
                if (!accepted.includes(cat.key)) continue;

                if (cat.scripts && cat.scripts.length) {
                    for (const script of cat.scripts) {
                        loadScript(script.src, script.async);
                    }
                }

                if (cat.inlineScript) {
                    try {
                        (new Function(cat.inlineScript))();
                    } catch (e) {
                        console.error('[ConsentControl] Inline script error for "' + cat.key + '":', e);
                    }
                }
            }
            this._servicesRun = true;
        },
    }));

    Alpine.data('consentMessage', (config) => ({
        hasConsent: false,
        _config: config,

        init() {
            this._check();

            window.addEventListener('consent-updated', () => {
                this._check();
                if (this.hasConsent) {
                    this._loadIframe();
                }
            });
        },

        _check() {
            const values = getCookie(this._config.cookieName);
            this.hasConsent = values.includes(this._config.consent);
        },

        grantAndLoad() {
            const current = getCookie(this._config.cookieName);
            if (!current.includes(this._config.consent)) {
                current.push(this._config.consent);
            }
            setCookie(
                this._config.cookieName,
                current,
                this._config.cookieDays || 365,
                this._config.cookieDomain || window.location.hostname,
                this._config.cookiePath || '/',
                this._config.cookieSameSite || 'lax',
                this._config.cookieSecure || false
            );
            this.hasConsent = true;
            this._loadIframe();

            window.dispatchEvent(new CustomEvent('consent-updated', {
                detail: { consents: current }
            }));
        },

        _loadIframe() {
            const iframe = this.$el.querySelector('iframe[data-src]');
            if (iframe && !iframe.src) {
                iframe.src = iframe.dataset.src;
            }
        },
    }));

    Alpine.data('consentGate', (consent, cookieName) => ({
        hasConsent: false,

        init() {
            this._check();
            window.addEventListener('consent-updated', () => this._check());
        },

        _check() {
            const values = getCookie(cookieName);
            this.hasConsent = values.includes(consent);
        },
    }));

});

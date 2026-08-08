const storageKey = 'pterodactyl:demo-mode';

interface DemoWindow extends Window {
    PterodactylDemo?: boolean;
    PterodactylUser?: {
        uuid: string;
        username: string;
        email: string;
        root_admin: boolean;
        use_totp: boolean;
        language: string;
        created_at: string;
        updated_at: string;
    };
    SiteConfiguration?: {
        name: string;
        locale: string;
        recaptcha: {
            enabled: boolean;
            siteKey: string;
        };
    };
}

const setStoredState = (enabled: boolean) => {
    try {
        if (enabled) {
            window.localStorage.setItem(storageKey, '1');
        } else {
            window.localStorage.removeItem(storageKey);
        }
    } catch (_) {
        // Private browsing or strict storage policies can make localStorage unavailable.
    }
};

const hasStoredState = (): boolean => {
    try {
        return window.localStorage.getItem(storageKey) === '1';
    } catch (_) {
        return false;
    }
};

export const isDemoModeRequested = (url = new URL(window.location.href)): boolean => {
    const flag = url.searchParams.get('demo');

    if (flag === '0') {
        setStoredState(false);
        return false;
    }

    if (flag === '1') {
        setStoredState(true);
        return true;
    }

    return hasStoredState();
};

export const disableDemoMode = async (): Promise<void> => {
    setStoredState(false);

    if ('serviceWorker' in navigator && navigator.serviceWorker) {
        const registrations = await navigator.serviceWorker.getRegistrations();
        await Promise.all(
            registrations
                .filter((registration) => registration.active?.scriptURL.endsWith('/demo-service-worker.js'))
                .map((registration) => registration.unregister())
        );
    }
};

const setDemoGlobals = () => {
    const target = window as DemoWindow;
    const timestamp = new Date('2026-08-08T12:00:00.000Z').toISOString();

    target.PterodactylDemo = true;
    target.PterodactylUser = {
        uuid: 'c08c7039-aa11-43f5-93c7-e3bb92f9d004',
        username: 'demo',
        email: 'demo@example.test',
        root_admin: false,
        use_totp: true,
        language: 'zh-TW',
        created_at: '2026-06-01T08:00:00.000Z',
        updated_at: timestamp,
    };
    target.SiteConfiguration = {
        name: 'Pterodactyl-TW Demo',
        locale: 'zh-TW',
        recaptcha: { enabled: false, siteKey: '' },
    };
};

const redirectToDashboard = (): boolean => {
    const current = new URL(window.location.href);
    const isAuthPage = current.pathname.startsWith('/auth');

    current.searchParams.delete('demo');
    const destination = `${isAuthPage ? '/' : current.pathname}${current.search}${current.hash}`;
    window.history.replaceState(null, document.title, destination);
    return isAuthPage;
};

export type DemoBootstrapStatus = 'disabled' | 'active' | 'reloading' | 'failed';

export const startDemoMode = async (): Promise<DemoBootstrapStatus> => {
    const url = new URL(window.location.href);
    if (url.searchParams.get('demo') === '0') {
        await disableDemoMode();
        url.searchParams.delete('demo');
        window.location.replace(`${url.pathname}${url.search}${url.hash}`);
        return 'reloading';
    }

    if (!isDemoModeRequested(url)) return 'disabled';

    if (!('serviceWorker' in navigator) || !navigator.serviceWorker) {
        setStoredState(false);
        console.error('Demo mode requires Service Worker support and a secure context (HTTPS or localhost).');
        return 'failed';
    }

    try {
        await navigator.serviceWorker.register('/demo-service-worker.js', { scope: '/' });
        await navigator.serviceWorker.ready;

        // First load is not controlled by the freshly installed worker. Reload once before
        // injecting a fake authenticated user so no request can escape to the real backend.
        if (!navigator.serviceWorker.controller) {
            window.location.reload();
            return 'reloading';
        }

        setDemoGlobals();
        if (redirectToDashboard()) {
            window.location.reload();
            return 'reloading';
        }
        return 'active';
    } catch (error) {
        setStoredState(false);
        console.error('Unable to start the Pterodactyl demo backend.', error);
        return 'failed';
    }
};

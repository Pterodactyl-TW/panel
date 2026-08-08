/** @jest-environment jsdom */

import { disableDemoMode, isDemoModeRequested, startDemoMode } from './bootstrap';

const user = {
    uuid: 'existing',
    username: 'existing',
    email: 'existing@example.test',
    root_admin: true,
    use_totp: false,
    language: 'en',
    created_at: '2020-01-01T00:00:00.000Z',
    updated_at: '2020-01-01T00:00:00.000Z',
};

describe('demo bootstrap', () => {
    beforeEach(() => {
        window.localStorage.clear();
        window.history.replaceState({}, '', '/auth/login');
        (window as any).PterodactylUser = user;
        delete (window as any).PterodactylDemo;
    });

    it('only persists demo mode after an explicit opt-in and supports opt-out', () => {
        expect(isDemoModeRequested(new URL('https://panel.test/auth/login'))).toBe(false);
        expect(isDemoModeRequested(new URL('https://panel.test/auth/login?demo=1'))).toBe(true);
        expect(isDemoModeRequested(new URL('https://panel.test/auth/login'))).toBe(true);
        expect(isDemoModeRequested(new URL('https://panel.test/auth/login?demo=0'))).toBe(false);
    });

    it('waits for the worker before replacing authentication data and routing to the dashboard', async () => {
        const register = jest.fn().mockResolvedValue({ active: {} });
        const ready = Promise.resolve({ active: {} });
        Object.defineProperty(window.navigator, 'serviceWorker', {
            configurable: true,
            value: { register, ready, controller: {} },
        });
        window.history.replaceState({}, '', '/auth/login?demo=1');

        await expect(startDemoMode()).resolves.toBe('reloading');

        expect(register).toHaveBeenCalledWith('/demo-service-worker.js', { scope: '/' });
        expect(window.location.pathname).toBe('/');
        expect((window as any).PterodactylDemo).toBe(true);
        expect((window as any).PterodactylUser.username).toBe('demo');
        expect((window as any).SiteConfiguration.name).toMatch(/Demo/);
    });

    it('fails closed when service workers are unavailable', async () => {
        Object.defineProperty(window.navigator, 'serviceWorker', { configurable: true, value: undefined });
        window.history.replaceState({}, '', '/auth/login?demo=1');

        await expect(startDemoMode()).resolves.toBe('failed');

        expect(window.location.pathname).toBe('/auth/login');
        expect((window as any).PterodactylUser).toBe(user);
        expect(window.localStorage.getItem('pterodactyl:demo-mode')).toBeNull();
    });

    it('unregisters only the demo worker when opting out', async () => {
        window.localStorage.setItem('pterodactyl:demo-mode', '1');
        const demoUnregister = jest.fn().mockResolvedValue(true);
        const appUnregister = jest.fn().mockResolvedValue(true);
        Object.defineProperty(window.navigator, 'serviceWorker', {
            configurable: true,
            value: {
                getRegistrations: jest.fn().mockResolvedValue([
                    { active: { scriptURL: 'https://panel.test/demo-service-worker.js' }, unregister: demoUnregister },
                    { active: { scriptURL: 'https://panel.test/app-worker.js' }, unregister: appUnregister },
                ]),
            },
        });

        await disableDemoMode();

        expect(demoUnregister).toHaveBeenCalledTimes(1);
        expect(appUnregister).not.toHaveBeenCalled();
        expect(window.localStorage.getItem('pterodactyl:demo-mode')).toBeNull();
    });
});

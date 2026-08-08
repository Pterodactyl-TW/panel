import { DemoWebsocket } from './DemoWebsocket';

describe('DemoWebsocket', () => {
    beforeEach(() => jest.useFakeTimers({ doNotFake: ['performance'] }));
    afterEach(() => jest.useRealTimers());

    it('authenticates, emits logs and stats without opening a network socket', () => {
        const socket = new DemoWebsocket();
        const auth = jest.fn();
        const status = jest.fn();
        const output = jest.fn();
        const stats = jest.fn();

        socket.on('auth success', auth);
        socket.on('status', status);
        socket.on('console output', output);
        socket.on('stats', stats);

        socket.setToken('demo').connect('wss://demo.invalid');
        jest.advanceTimersByTime(1);
        socket.send('send logs');
        socket.send('send stats');
        jest.advanceTimersByTime(200);

        expect(auth).toHaveBeenCalledTimes(1);
        expect(status).toHaveBeenCalledWith('running');
        expect(output).toHaveBeenCalled();
        expect(JSON.parse(stats.mock.calls[0][0])).toEqual(
            expect.objectContaining({ cpu_absolute: expect.any(Number), network: expect.any(Object) })
        );
    });

    it('simulates power actions and command output', () => {
        const socket = new DemoWebsocket();
        const status = jest.fn();
        const output = jest.fn();
        socket.on('status', status);
        socket.on('console output', output);
        socket.connect('demo');
        jest.runOnlyPendingTimers();

        socket.send('set state', 'stop');
        jest.advanceTimersByTime(1000);
        expect(status).toHaveBeenLastCalledWith('offline');

        socket.send('send command', 'say hello demo');
        expect(output).toHaveBeenLastCalledWith('> say hello demo');
    });
});

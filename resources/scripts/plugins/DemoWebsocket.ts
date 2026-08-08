import { EventEmitter } from 'events';

const demoLogs = [
    '[12:00:01 INFO]: Starting minecraft server version 1.21.4',
    '[12:00:02 INFO]: Loading properties',
    '[12:00:04 INFO]: Preparing level "world"',
    '[12:00:07 INFO]: Done (5.842s)! For help, type "help"',
    '[12:00:12 INFO]: Pterodactyl-TW demo backend connected.',
];

export class DemoWebsocket extends EventEmitter {
    private connected = false;
    private status: 'offline' | 'starting' | 'running' | 'stopping' = 'running';
    private statsTimer?: ReturnType<typeof setInterval>;
    private tx = 2212495360;
    private rx = 4831838208;
    private uptime = 184320000;

    connect(_url: string): this {
        setTimeout(() => {
            this.connected = true;
            this.emit('SOCKET_OPEN');
            this.emit('auth success');
            this.emit('status', this.status);
            this.startStats();
        }, 0);

        return this;
    }

    setToken(_token: string, isUpdate = false): this {
        if (isUpdate && this.connected) this.emit('auth success');
        return this;
    }

    close() {
        this.connected = false;
        if (this.statsTimer) clearInterval(this.statsTimer);
        this.emit('SOCKET_CLOSE');
    }

    open() {
        return this.connect('demo');
    }

    reconnect() {
        return this.connect('demo');
    }

    send(event: string, payload?: string | string[]) {
        const value = Array.isArray(payload) ? payload[0] : payload;

        if (event === 'send logs') {
            demoLogs.forEach((line, index) => setTimeout(() => this.emit('console output', line), index * 35));
            return;
        }

        if (event === 'send stats') {
            this.emitStats();
            return;
        }

        if (event === 'send command' && value) {
            this.emit('console output', `> ${value}`);
            setTimeout(() => this.emit('console output', `[Demo] Executed: ${value}`), 100);
            return;
        }

        if (event === 'set state' && value) this.setPowerState(value);
    }

    private setPowerState(action: string) {
        if (action === 'start') {
            this.status = 'starting';
            this.emit('status', this.status);
            setTimeout(() => {
                this.status = 'running';
                this.emit('status', this.status);
                this.emit('console output', '[Demo] Server started successfully.');
            }, 1000);
        } else if (action === 'stop' || action === 'kill') {
            this.status = action === 'stop' ? 'stopping' : 'offline';
            this.emit('status', this.status);
            setTimeout(
                () => {
                    this.status = 'offline';
                    this.emit('status', this.status);
                    this.emit('console output', '[Demo] Server stopped.');
                },
                action === 'kill' ? 0 : 1000
            );
        } else if (action === 'restart') {
            this.status = 'stopping';
            this.emit('status', this.status);
            setTimeout(() => {
                this.status = 'starting';
                this.emit('status', this.status);
            }, 500);
            setTimeout(() => {
                this.status = 'running';
                this.emit('status', this.status);
                this.emit('console output', '[Demo] Restart complete.');
            }, 1500);
        }
    }

    private startStats() {
        if (this.statsTimer) clearInterval(this.statsTimer);
        this.emitStats();
        this.statsTimer = setInterval(() => this.emitStats(), 2000);
    }

    private emitStats() {
        if (!this.connected) return;

        const running = this.status === 'running';
        if (running) {
            this.tx += 98304;
            this.rx += 163840;
            this.uptime += 2000;
        }

        this.emit(
            'stats',
            JSON.stringify({
                memory_bytes: running ? 1717986918 : 0,
                cpu_absolute: running ? 37.42 : 0,
                disk_bytes: 6979321856,
                network: { rx_bytes: running ? this.rx : 0, tx_bytes: running ? this.tx : 0 },
                uptime: running ? this.uptime : 0,
            })
        );
    }
}

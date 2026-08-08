/*
 * Pterodactyl demo backend.
 *
 * This worker deliberately intercepts every /api/client request while demo mode is
 * enabled. Unknown endpoints fail closed with a JSON 501 response so a demo can
 * never accidentally mutate a real panel backend.
 */
(function (root, factory) {
    const api = factory();

    if (typeof module === 'object' && module.exports) {
        module.exports = api;
    }

    if (root && typeof root.addEventListener === 'function') {
        const backend = api.createDemoBackend();

        root.addEventListener('install', () => root.skipWaiting());
        root.addEventListener('activate', (event) => event.waitUntil(root.clients.claim()));
        root.addEventListener('fetch', (event) => {
            const url = new URL(event.request.url);
            if (url.origin !== root.location.origin || !url.pathname.startsWith('/api/client')) return;

            event.respondWith(
                backend.handle(event.request).then(
                    (response) => response || fetch(event.request),
                    (error) =>
                        api.jsonResponse(
                            {
                                errors: [
                                    {
                                        code: 'DemoBackendError',
                                        status: '500',
                                        detail: error instanceof Error ? error.message : 'The demo backend failed.',
                                    },
                                ],
                            },
                            500
                        )
                )
            );
        });
    }
})(typeof self !== 'undefined' ? self : null, function () {
    'use strict';

    const MiB = 1024 * 1024;
    const GiB = 1024 * MiB;
    const clone = (value) => JSON.parse(JSON.stringify(value));
    const iso = (value) => new Date(value).toISOString();

    const jsonResponse = (body, status = 200) =>
        new Response(status === 204 ? null : JSON.stringify(body), {
            status,
            headers: {
                'content-type': 'application/json; charset=utf-8',
                'cache-control': 'no-store',
                'x-pterodactyl-demo': 'true',
            },
        });

    const emptyResponse = () => jsonResponse(null, 204);
    const resource = (object, attributes) => ({ object, attributes });
    const list = (data, meta) => ({ object: 'list', data, ...(meta ? { meta } : {}) });
    const pagination = (total, page = 1, perPage = 50) => ({
        total,
        count: total,
        per_page: perPage,
        current_page: page,
        total_pages: Math.max(1, Math.ceil(total / perPage)),
    });

    const allocation = (id, ip, port, isDefault, alias = null, notes = null) =>
        resource('allocation', {
            id,
            ip,
            ip_alias: alias,
            port,
            notes,
            is_default: isDefault,
        });

    const variable = (name, description, env, defaultValue, serverValue, editable = true, rules = 'required|string') =>
        resource('egg_variable', {
            name,
            description,
            env_variable: env,
            default_value: defaultValue,
            server_value: serverValue,
            is_editable: editable,
            rules,
        });

    const makeInitialState = () => {
        const servers = [
            {
                identifier: 'demo0001',
                server_identifier: 'serv_demo0001',
                internal_id: 101,
                __deprecated_uuid_short: 'demo0001',
                uuid: 'ca7f0c8a-f8b1-4b38-92db-57f9b6c4d001',
                name: 'Taiwan Minecraft 生存服',
                node: 'Taipei-01',
                is_node_under_maintenance: false,
                status: null,
                invocation: 'java -Xms128M -Xmx{{SERVER_MEMORY}}M -jar {{SERVER_JARFILE}}',
                docker_image: 'ghcr.io/pterodactyl/yolks:java_21',
                sftp_details: { ip: 'sftp.demo.pterodactyl.tw', port: 2022 },
                description: 'Pterodactyl-TW 公開展示伺服器（資料皆為虛構）',
                limits: { memory: 4096, swap: 0, disk: 20480, io: 500, cpu: 200, threads: null },
                egg_features: ['eula', 'java_version'],
                feature_limits: { databases: 3, allocations: 3, backups: 5 },
                is_transferring: false,
                allocations: [
                    allocation(1, '203.0.113.10', 25565, true, 'play.demo.tw', 'Minecraft Java'),
                    allocation(2, '203.0.113.10', 25566, false, null, 'Voice chat'),
                ],
                variables: [
                    variable('Server Jar File', '啟動時執行的伺服器 JAR。', 'SERVER_JARFILE', 'server.jar', 'paper-1.21.4.jar'),
                    variable('Server Version', '要安裝的 Minecraft 版本。', 'MINECRAFT_VERSION', 'latest', '1.21.4'),
                    variable('Build Number', 'Paper build；latest 會自動選擇最新版。', 'BUILD_NUMBER', 'latest', 'latest'),
                ],
                state: 'running',
                resources: {
                    memory_bytes: 1717986918,
                    cpu_absolute: 37.42,
                    disk_bytes: 6979321856,
                    network_rx_bytes: 4831838208,
                    network_tx_bytes: 2212495360,
                    uptime: 184320000,
                },
            },
            {
                identifier: 'demo0002',
                server_identifier: 'serv_demo0002',
                internal_id: 102,
                __deprecated_uuid_short: 'demo0002',
                uuid: '3b95deaf-ddda-4a5d-9e55-8b936c95d002',
                name: 'Palworld 測試分流',
                node: 'Tokyo-Edge-02',
                is_node_under_maintenance: false,
                status: null,
                invocation: './PalServer.sh -port={{SERVER_PORT}} -players={{MAX_PLAYERS}}',
                docker_image: 'ghcr.io/pterodactyl/yolks:steamcmd',
                sftp_details: { ip: 'sftp-jp.demo.pterodactyl.tw', port: 2022 },
                description: '跨區備援與模組相容性測試',
                limits: { memory: 8192, swap: 0, disk: 40960, io: 500, cpu: 400, threads: null },
                egg_features: ['steam_disk_space'],
                feature_limits: { databases: 1, allocations: 2, backups: 3 },
                is_transferring: false,
                allocations: [allocation(11, '198.51.100.42', 8211, true, 'pal.demo.tw', 'Game port')],
                variables: [
                    variable('Max Players', '伺服器人數上限。', 'MAX_PLAYERS', '32', '24'),
                    variable('Auto Update', '啟動時自動更新。', 'AUTO_UPDATE', '1', '1'),
                ],
                state: 'offline',
                resources: {
                    memory_bytes: 0,
                    cpu_absolute: 0,
                    disk_bytes: 12884901888,
                    network_rx_bytes: 0,
                    network_tx_bytes: 0,
                    uptime: 0,
                },
            },
        ];

        return {
            servers,
            files: {
                [servers[0].uuid]: {
                    '/': [
                        { name: 'logs', mode: '-rw-r--r--', mode_bits: '0755', size: 4096, is_file: false, is_symlink: false, mimetype: 'inode/directory' },
                        { name: 'plugins', mode: 'drwxr-xr-x', mode_bits: '0755', size: 4096, is_file: false, is_symlink: false, mimetype: 'inode/directory' },
                        { name: 'server.properties', mode: '-rw-r--r--', mode_bits: '0644', size: 1248, is_file: true, is_symlink: false, mimetype: 'text/plain' },
                        { name: 'paper-1.21.4.jar', mode: '-rw-r--r--', mode_bits: '0644', size: 52428800, is_file: true, is_symlink: false, mimetype: 'application/java-archive' },
                        { name: 'world.zip', mode: '-rw-r--r--', mode_bits: '0644', size: 184549376, is_file: true, is_symlink: false, mimetype: 'application/zip' },
                    ],
                    '/plugins': [
                        { name: 'EssentialsX.jar', mode: '-rw-r--r--', mode_bits: '0644', size: 3312844, is_file: true, is_symlink: false, mimetype: 'application/java-archive' },
                        { name: 'LuckPerms.jar', mode: '-rw-r--r--', mode_bits: '0644', size: 1567192, is_file: true, is_symlink: false, mimetype: 'application/java-archive' },
                    ],
                },
                [servers[1].uuid]: {
                    '/': [
                        { name: 'Pal', mode: 'drwxr-xr-x', mode_bits: '0755', size: 4096, is_file: false, is_symlink: false, mimetype: 'inode/directory' },
                        { name: 'steam_appid.txt', mode: '-rw-r--r--', mode_bits: '0644', size: 8, is_file: true, is_symlink: false, mimetype: 'text/plain' },
                    ],
                },
            },
            fileContents: {
                [servers[0].uuid]: {
                    '/server.properties': 'motd=§bPterodactyl-TW Demo Server\nmax-players=60\nonline-mode=true\ndifficulty=hard\nview-distance=10\n',
                },
                [servers[1].uuid]: { '/steam_appid.txt': '2394010\n' },
            },
            databases: {
                [servers[0].uuid]: [
                    {
                        id: 'db_demo01',
                        name: 's101_luckperms',
                        username: 'u101_luckperms',
                        host: { address: 'mysql.demo.internal', port: 3306 },
                        connections_from: '%',
                        password: 'demo-only-password',
                    },
                    {
                        id: 'db_demo02',
                        name: 's101_webmap',
                        username: 'u101_webmap',
                        host: { address: 'mysql.demo.internal', port: 3306 },
                        connections_from: '10.%',
                        password: 'demo-only-password',
                    },
                ],
                [servers[1].uuid]: [],
            },
            schedules: {
                [servers[0].uuid]: [
                    {
                        id: 1,
                        name: '每日備份與重啟',
                        cron: { day_of_week: '*', month: '*', day_of_month: '*', hour: '4', minute: '0' },
                        is_active: true,
                        is_processing: false,
                        only_when_online: true,
                        last_run_at: '2026-08-08T04:00:03.000Z',
                        next_run_at: '2026-08-09T04:00:00.000Z',
                        created_at: '2026-06-01T08:00:00.000Z',
                        updated_at: '2026-08-01T09:30:00.000Z',
                        tasks: [
                            { id: 1, sequence_id: 1, action: 'backup', payload: '', time_offset: 0, is_queued: false, continue_on_failure: false, created_at: '2026-06-01T08:00:00.000Z', updated_at: '2026-06-01T08:00:00.000Z' },
                            { id: 2, sequence_id: 2, action: 'power', payload: 'restart', time_offset: 120, is_queued: false, continue_on_failure: true, created_at: '2026-06-01T08:00:00.000Z', updated_at: '2026-06-01T08:00:00.000Z' },
                        ],
                    },
                ],
                [servers[1].uuid]: [],
            },
            backups: {
                [servers[0].uuid]: [
                    { uuid: 'bkp-20260808-demo', is_successful: true, is_locked: true, name: '每日自動備份', ignored_files: 'cache\nlogs/latest.log', checksum: 'sha256:demo0000000000000000000000000000000000000000000000000000000000', bytes: 3221225472, created_at: '2026-08-08T04:00:03.000Z', completed_at: '2026-08-08T04:05:18.000Z' },
                    { uuid: 'bkp-20260807-demo', is_successful: true, is_locked: false, name: '更新前快照', ignored_files: '', checksum: 'sha256:demo1111111111111111111111111111111111111111111111111111111111', bytes: 3092376453, created_at: '2026-08-07T15:20:00.000Z', completed_at: '2026-08-07T15:24:42.000Z' },
                ],
                [servers[1].uuid]: [],
            },
            subusers: {
                [servers[0].uuid]: [
                    { uuid: '59e1fdc7-df68-449f-a17f-c71127e1d003', username: 'demo-helper', email: 'helper@example.test', image: 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp', two_factor: true, created_at: '2026-07-01T08:00:00.000Z', permissions: ['control.console', 'control.start', 'control.stop', 'file.read', 'backup.read'] },
                ],
                [servers[1].uuid]: [],
            },
            apiKeys: [
                { identifier: 'demoKey1', description: 'Demo monitoring', allowed_ips: ['203.0.113.0/24'], created_at: '2026-07-12T08:00:00.000Z', last_used_at: '2026-08-08T11:58:00.000Z' },
            ],
            sshKeys: [
                { name: 'Demo Laptop', public_key: 'ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIDemoKeyOnlyNotReal demo@example.test', fingerprint: 'SHA256:DemoFingerprintOnly', created_at: '2026-07-20T10:30:00.000Z' },
            ],
        };
    };

    const toFileResource = (entry, timestamp) =>
        resource('file_object', {
            ...entry,
            created_at: timestamp,
            modified_at: timestamp,
        });

    const toDatabaseResource = (database) =>
        resource('server_database', {
            id: database.id,
            name: database.name,
            username: database.username,
            host: database.host,
            connections_from: database.connections_from,
            relationships: { password: resource('database_password', { password: database.password }) },
        });

    const toTaskResource = (task) => resource('schedule_task', clone(task));
    const toScheduleAttributes = (schedule) => ({
        ...clone(schedule),
        relationships: { tasks: list(schedule.tasks.map(toTaskResource)) },
    });
    const toScheduleResource = (schedule) => resource('server_schedule', toScheduleAttributes(schedule));
    const toBackupResource = (backup) => resource('backup', clone(backup));
    const toAllocationResource = (entry) => clone(entry);
    const toSubuserResource = (user) =>
        resource('subuser', {
            uuid: user.uuid,
            username: user.username,
            email: user.email,
            image: user.image,
            '2fa_enabled': user.two_factor,
            created_at: user.created_at,
            permissions: clone(user.permissions),
        });

    const toServerResource = (server, includeMeta = false) => {
        const attributes = clone(server);
        delete attributes.allocations;
        delete attributes.variables;
        delete attributes.state;
        delete attributes.resources;
        attributes.relationships = {
            allocations: list(server.allocations.map(toAllocationResource)),
            variables: list(server.variables.map(clone)),
        };

        return {
            ...resource('server', attributes),
            ...(includeMeta ? { meta: { is_server_owner: true, user_permissions: [] } } : {}),
        };
    };

    const actorResource = () =>
        resource('user', {
            uuid: 'c08c7039-aa11-43f5-93c7-e3bb92f9d004',
            username: 'demo',
            email: 'demo@example.test',
            image: 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp',
            '2fa_enabled': true,
            created_at: '2026-06-01T08:00:00.000Z',
            permissions: [],
        });

    const activityResource = (id, event, description, timestamp, properties = {}) =>
        resource('activity_log', {
            id,
            batch: null,
            event,
            ip: '203.0.113.88',
            is_api: false,
            description,
            properties,
            has_additional_metadata: Object.keys(properties).length > 0,
            timestamp,
            relationships: { actor: actorResource() },
        });

    function createDemoBackend(options = {}) {
        const now = options.now || (() => new Date());
        const random = options.random || Math.random;
        const state = makeInitialState();

        const findServer = (id) =>
            state.servers.find(
                (server) => server.uuid === id || server.identifier === id || server.server_identifier === id
            );

        const body = async (request) => {
            if (!request.body) return {};
            try {
                return await request.clone().json();
            } catch (_) {
                return {};
            }
        };

        const error = (status, code, detail) =>
            jsonResponse({ errors: [{ code, status: String(status), detail }] }, status);

        const notFound = (detail = 'The requested demo resource does not exist.') => error(404, 'NotFoundHttpException', detail);

        const handleAccount = async (request, path, method) => {
            if (path === '/account/api-keys' && method === 'GET') {
                return jsonResponse(list(state.apiKeys.map((key) => resource('api_key', clone(key)))));
            }
            if (path === '/account/api-keys' && method === 'POST') {
                const input = await body(request);
                const key = {
                    identifier: `demo${String(state.apiKeys.length + 1).padStart(4, '0')}`,
                    description: input.description || 'Demo key',
                    allowed_ips: input.allowed_ips || [],
                    created_at: now().toISOString(),
                    last_used_at: null,
                };
                state.apiKeys.push(key);
                return jsonResponse({ ...resource('api_key', clone(key)), meta: { secret_token: 'ptlc_demo_token_not_real' } }, 201);
            }
            const apiKeyMatch = path.match(/^\/account\/api-keys\/([^/]+)$/);
            if (apiKeyMatch && method === 'DELETE') {
                state.apiKeys = state.apiKeys.filter((key) => key.identifier !== apiKeyMatch[1]);
                return emptyResponse();
            }
            if (path === '/account/ssh-keys' && method === 'GET') {
                return jsonResponse(list(state.sshKeys.map((key) => resource('ssh_key', clone(key)))));
            }
            if (path === '/account/ssh-keys' && method === 'POST') {
                const input = await body(request);
                const key = { name: input.name || 'Demo key', public_key: input.public_key || '', fingerprint: `SHA256:Demo${Date.now()}`, created_at: now().toISOString() };
                state.sshKeys.push(key);
                return jsonResponse(resource('ssh_key', key), 201);
            }
            if (path === '/account/ssh-keys/remove' && method === 'POST') {
                const input = await body(request);
                state.sshKeys = state.sshKeys.filter((key) => key.fingerprint !== input.fingerprint);
                return emptyResponse();
            }
            if (path === '/account/activity' && method === 'GET') {
                const entries = [
                    activityResource('act-account-1', 'auth:login', '登入控制面板', '2026-08-08T11:45:00.000Z'),
                    activityResource('act-account-2', 'account:password.updated', '更新帳號密碼', '2026-08-01T03:20:00.000Z'),
                ];
                return jsonResponse(list(entries, { pagination: pagination(entries.length) }));
            }
            if (path === '/account/two-factor' && method === 'GET') {
                return jsonResponse({ data: { image_url_data: 'data:image/svg+xml;base64,PHN2Zy8+', secret: 'DEMO2FASECRET' } });
            }
            if (path === '/account/two-factor' && method === 'POST') {
                return jsonResponse(resource('recovery_tokens', { tokens: ['demo-recovery-1', 'demo-recovery-2'] }));
            }
            if (
                (path === '/account/two-factor/disable' && method === 'POST') ||
                (path === '/account/email' && method === 'PUT') ||
                (path === '/account/password' && method === 'PUT')
            ) {
                return emptyResponse();
            }
            return null;
        };

        const handleServer = async (request, server, tail, method, url) => {
            if (!tail && method === 'GET') return jsonResponse(toServerResource(server, true));

            if (tail === '/resources' && method === 'GET') {
                const drift = server.state === 'running' ? Math.round((random() - 0.5) * 8 * MiB) : 0;
                return jsonResponse(
                    resource('stats', {
                        current_state: server.state,
                        is_suspended: false,
                        resources: { ...clone(server.resources), memory_bytes: Math.max(0, server.resources.memory_bytes + drift) },
                    })
                );
            }

            if (tail === '/websocket' && method === 'GET') {
                return jsonResponse({ data: { token: 'demo-websocket-token', socket: 'wss://demo.invalid/api/servers/demo/ws' } });
            }

            if (tail === '/files/list' && method === 'GET') {
                const directory = url.searchParams.get('directory') || '/';
                const entries = state.files[server.uuid]?.[directory] || [];
                return jsonResponse(list(entries.map((entry) => toFileResource(entry, now().toISOString()))));
            }

            if (tail === '/files/contents' && method === 'GET') {
                const file = url.searchParams.get('file') || '';
                const contents = state.fileContents[server.uuid]?.[file];
                return contents === undefined
                    ? notFound('That file does not exist in the demo filesystem.')
                    : new Response(contents, { status: 200, headers: { 'content-type': 'text/plain; charset=utf-8', 'cache-control': 'no-store', 'x-pterodactyl-demo': 'true' } });
            }

            if (tail === '/files/write' && method === 'POST') {
                const file = url.searchParams.get('file') || '';
                state.fileContents[server.uuid] = state.fileContents[server.uuid] || {};
                state.fileContents[server.uuid][file] = await request.clone().text();
                return emptyResponse();
            }

            if (tail === '/files/create-folder' && method === 'POST') {
                const input = await body(request);
                const root = input.root || '/';
                state.files[server.uuid] = state.files[server.uuid] || {};
                state.files[server.uuid][root] = state.files[server.uuid][root] || [];
                if (!state.files[server.uuid][root].some((entry) => entry.name === input.name)) {
                    state.files[server.uuid][root].push({ name: input.name, mode: 'drwxr-xr-x', mode_bits: '0755', size: 4096, is_file: false, is_symlink: false, mimetype: 'inode/directory' });
                }
                const path = `${root.replace(/\/$/, '')}/${input.name}` || '/';
                state.files[server.uuid][path] = state.files[server.uuid][path] || [];
                return emptyResponse();
            }

            if (/^\/files\/(chmod|compress|delete|decompress|rename|copy)$/.test(tail) && ['POST', 'PUT'].includes(method)) {
                return emptyResponse();
            }
            if (tail === '/files/upload' && method === 'GET') return jsonResponse({ attributes: { url: '/api/client/demo-upload' } });
            if (tail === '/files/download' && method === 'GET') return jsonResponse({ attributes: { url: 'data:text/plain,Demo%20download' } });

            if (tail === '/databases' && method === 'GET') {
                return jsonResponse(list((state.databases[server.uuid] || []).map(toDatabaseResource)));
            }
            if (tail === '/databases' && method === 'POST') {
                const input = await body(request);
                const database = {
                    id: `db_demo${Date.now()}`,
                    name: `s${server.internal_id}_${input.database || 'database'}`,
                    username: `u${server.internal_id}_demo`,
                    host: { address: 'mysql.demo.internal', port: 3306 },
                    connections_from: input.remote || '%',
                    password: 'new-demo-password',
                };
                state.databases[server.uuid].push(database);
                return jsonResponse(toDatabaseResource(database), 201);
            }
            const databaseMatch = tail.match(/^\/databases\/([^/]+)(\/rotate-password)?$/);
            if (databaseMatch) {
                const databases = state.databases[server.uuid] || [];
                const database = databases.find((item) => item.id === databaseMatch[1]);
                if (!database) return notFound();
                if (method === 'DELETE') {
                    state.databases[server.uuid] = databases.filter((item) => item.id !== database.id);
                    return emptyResponse();
                }
                if (method === 'POST' && databaseMatch[2]) {
                    database.password = 'rotated-demo-password';
                    return jsonResponse(toDatabaseResource(database));
                }
            }

            if (tail === '/schedules' && method === 'GET') {
                return jsonResponse(list((state.schedules[server.uuid] || []).map(toScheduleResource)));
            }
            if (tail === '/schedules' && method === 'POST') {
                const input = await body(request);
                const schedule = {
                    id: Math.max(0, ...state.schedules[server.uuid].map((item) => item.id)) + 1,
                    name: input.name || 'Demo schedule',
                    cron: { day_of_week: input.day_of_week, month: input.month, day_of_month: input.day_of_month, hour: input.hour, minute: input.minute },
                    is_active: input.is_active,
                    is_processing: false,
                    only_when_online: input.only_when_online,
                    last_run_at: null,
                    next_run_at: iso(now().getTime() + 3600000),
                    created_at: now().toISOString(),
                    updated_at: now().toISOString(),
                    tasks: [],
                };
                state.schedules[server.uuid].push(schedule);
                return jsonResponse(toScheduleResource(schedule), 201);
            }
            const scheduleMatch = tail.match(/^\/schedules\/(\d+)$/);
            if (scheduleMatch) {
                const schedules = state.schedules[server.uuid] || [];
                const schedule = schedules.find((item) => item.id === Number(scheduleMatch[1]));
                if (!schedule) return notFound();
                if (method === 'GET') return jsonResponse(toScheduleResource(schedule));
                if (method === 'DELETE') {
                    state.schedules[server.uuid] = schedules.filter((item) => item.id !== schedule.id);
                    return emptyResponse();
                }
                if (method === 'POST') {
                    Object.assign(schedule, await body(request), { updated_at: now().toISOString() });
                    return jsonResponse(toScheduleResource(schedule));
                }
            }
            if (/^\/schedules\/\d+\/execute$/.test(tail) && method === 'POST') return emptyResponse();
            if (/^\/schedules\/\d+\/tasks(\/\d+)?$/.test(tail)) {
                const parts = tail.split('/');
                const schedule = state.schedules[server.uuid].find((item) => item.id === Number(parts[2]));
                if (!schedule) return notFound();
                const taskId = parts[4] ? Number(parts[4]) : null;
                if (method === 'DELETE' && taskId) {
                    schedule.tasks = schedule.tasks.filter((task) => task.id !== taskId);
                    return emptyResponse();
                }
                if (method === 'POST') {
                    const input = await body(request);
                    let task = taskId ? schedule.tasks.find((item) => item.id === taskId) : null;
                    if (!task) {
                        task = { id: Math.max(0, ...schedule.tasks.map((item) => item.id)) + 1, sequence_id: schedule.tasks.length + 1, is_queued: false, created_at: now().toISOString() };
                        schedule.tasks.push(task);
                    }
                    Object.assign(task, input, { updated_at: now().toISOString() });
                    return jsonResponse(toTaskResource(task), taskId ? 200 : 201);
                }
            }

            if (tail === '/backups' && method === 'GET') {
                const backups = state.backups[server.uuid] || [];
                return jsonResponse(list(backups.map(toBackupResource), { pagination: pagination(backups.length), backup_count: backups.length }));
            }
            if (tail === '/backups' && method === 'POST') {
                const input = await body(request);
                const backup = { uuid: `bkp-demo-${Date.now()}`, is_successful: true, is_locked: !!input.is_locked, name: input.name || 'Demo backup', ignored_files: input.ignored || '', checksum: 'sha256:demo', bytes: 1048576, created_at: now().toISOString(), completed_at: now().toISOString() };
                state.backups[server.uuid].unshift(backup);
                return jsonResponse(toBackupResource(backup), 201);
            }
            const backupMatch = tail.match(/^\/backups\/([^/]+)(\/restore)?$/);
            if (backupMatch) {
                if (backupMatch[2] && method === 'POST') return emptyResponse();
                if (method === 'DELETE') {
                    state.backups[server.uuid] = state.backups[server.uuid].filter((item) => item.uuid !== backupMatch[1]);
                    return emptyResponse();
                }
            }

            if (tail === '/network/allocations' && method === 'GET') return jsonResponse(list(server.allocations.map(clone)));
            if (tail === '/network/allocations' && method === 'POST') {
                const next = allocation(Math.max(...server.allocations.map((entry) => entry.attributes.id)) + 1, '203.0.113.10', 25565 + server.allocations.length, false, null, 'Demo allocation');
                server.allocations.push(next);
                return jsonResponse(next, 201);
            }
            const allocationMatch = tail.match(/^\/network\/allocations\/(\d+)(\/primary)?$/);
            if (allocationMatch) {
                const id = Number(allocationMatch[1]);
                if (allocationMatch[2] && method === 'POST') {
                    server.allocations.forEach((entry) => { entry.attributes.is_default = entry.attributes.id === id; });
                    return emptyResponse();
                }
                if (method === 'DELETE') {
                    server.allocations = server.allocations.filter((entry) => entry.attributes.id !== id);
                    return emptyResponse();
                }
                if (method === 'POST') {
                    const input = await body(request);
                    const entry = server.allocations.find((item) => item.attributes.id === id);
                    if (entry) entry.attributes.notes = input.notes || null;
                    return emptyResponse();
                }
            }

            if (tail === '/users' && method === 'GET') return jsonResponse(list((state.subusers[server.uuid] || []).map(toSubuserResource)));
            if (tail === '/users' && method === 'POST') {
                const input = await body(request);
                const user = { uuid: `demo-user-${Date.now()}`, username: (input.email || 'demo').split('@')[0], email: input.email || 'demo@example.test', image: 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp', two_factor: false, created_at: now().toISOString(), permissions: input.permissions || [] };
                state.subusers[server.uuid].push(user);
                return jsonResponse(toSubuserResource(user), 201);
            }
            const userMatch = tail.match(/^\/users\/([^/]+)$/);
            if (userMatch) {
                const users = state.subusers[server.uuid] || [];
                const user = users.find((item) => item.uuid === userMatch[1]);
                if (!user) return notFound();
                if (method === 'DELETE') {
                    state.subusers[server.uuid] = users.filter((item) => item.uuid !== user.uuid);
                    return emptyResponse();
                }
                if (method === 'POST') {
                    Object.assign(user, await body(request));
                    return jsonResponse(toSubuserResource(user));
                }
            }

            if (tail === '/startup' && method === 'GET') {
                return jsonResponse(list(server.variables.map(clone), { startup_command: server.invocation, docker_images: { 'Java 21': 'ghcr.io/pterodactyl/yolks:java_21', 'Java 17': 'ghcr.io/pterodactyl/yolks:java_17' } }));
            }
            if (tail === '/startup/variable' && method === 'PUT') {
                const input = await body(request);
                const item = server.variables.find((entry) => entry.attributes.env_variable === input.key);
                if (item) item.attributes.server_value = input.value;
                return jsonResponse(item || resource('egg_variable', {}));
            }
            if (tail === '/settings/docker-image' && method === 'PUT') {
                const input = await body(request);
                server.docker_image = input.docker_image;
                return emptyResponse();
            }
            if (tail === '/settings/rename' && method === 'POST') {
                const input = await body(request);
                server.name = input.name || server.name;
                return emptyResponse();
            }
            if (tail === '/settings/reinstall' && method === 'POST') return emptyResponse();

            if (tail === '/activity' && method === 'GET') {
                const entries = [
                    activityResource('act-server-1', 'server:power.start', '啟動伺服器', '2026-08-08T09:15:00.000Z'),
                    activityResource('act-server-2', 'server:file.write', '更新 server.properties', '2026-08-08T08:52:00.000Z', { file: '/server.properties' }),
                    activityResource('act-server-3', 'server:backup.complete', '備份已完成', '2026-08-08T04:05:18.000Z'),
                ];
                return jsonResponse(list(entries, { pagination: pagination(entries.length) }));
            }

            return null;
        };

        return {
            snapshot: () => clone(state),
            async handle(request) {
                const url = new URL(request.url);
                const method = request.method.toUpperCase();
                const path = url.pathname.replace(/\/$/, '') || '/';
                if (!path.startsWith('/api/client')) return null;

                const relative = path.slice('/api/client'.length) || '/';
                if (relative === '/' && method === 'GET') {
                    const data = state.servers.map((server) => toServerResource(server));
                    return jsonResponse(list(data, { pagination: pagination(data.length, Number(url.searchParams.get('page') || 1), 15) }));
                }
                if (relative === '/permissions' && method === 'GET') {
                    return jsonResponse(resource('system_permissions', {
                        permissions: {
                            resource: { description: 'Server resources', keys: { view: 'View server resources' } },
                            control: { description: 'Server power', keys: { console: 'View console', start: 'Start server', stop: 'Stop server', restart: 'Restart server' } },
                            user: { description: 'Subusers', keys: { create: 'Create users', read: 'View users', update: 'Update users', delete: 'Delete users' } },
                            file: { description: 'Files', keys: { create: 'Create files', read: 'Read files', update: 'Update files', delete: 'Delete files', archive: 'Archive files', sftp: 'Use SFTP' } },
                            backup: { description: 'Backups', keys: { create: 'Create backups', read: 'View backups', delete: 'Delete backups', download: 'Download backups', restore: 'Restore backups' } },
                            database: { description: 'Databases', keys: { create: 'Create databases', read: 'View databases', update: 'Update databases', delete: 'Delete databases', view_password: 'View passwords' } },
                            schedule: { description: 'Schedules', keys: { create: 'Create schedules', read: 'View schedules', update: 'Update schedules', delete: 'Delete schedules' } },
                            allocation: { description: 'Network', keys: { read: 'View allocations', create: 'Create allocations', update: 'Update allocations', delete: 'Delete allocations' } },
                            startup: { description: 'Startup', keys: { read: 'View startup', update: 'Update startup', docker_image: 'Change Docker image' } },
                            settings: { description: 'Settings', keys: { rename: 'Rename server', reinstall: 'Reinstall server' } },
                            activity: { description: 'Activity', keys: { read: 'View activity' } },
                        },
                    }));
                }

                const accountResponse = await handleAccount(request, relative, method);
                if (accountResponse) return accountResponse;

                const match = relative.match(/^\/servers\/([^/]+)(.*)$/);
                if (match) {
                    const server = findServer(decodeURIComponent(match[1]));
                    if (!server) return notFound('That server does not exist in the demo backend.');
                    const response = await handleServer(request, server, match[2], method, url);
                    if (response) return response;
                }

                return error(501, 'DemoEndpointNotImplemented', `The demo backend does not implement ${method} ${path}.`);
            },
        };
    }

    return { createDemoBackend, jsonResponse };
});

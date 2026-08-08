const test = require('node:test');
const assert = require('node:assert/strict');

const { createDemoBackend } = require('../../../public/demo-service-worker.js');

const request = (path, init = {}) => new Request(`https://panel.example.test${path}`, init);
const json = (response) => response.json();

const makeBackend = () =>
    createDemoBackend({
        now: () => new Date('2026-08-08T12:00:00.000Z'),
        random: () => 0.42,
    });

test('ignores requests outside the client API', async () => {
    const backend = makeBackend();
    assert.equal(await backend.handle(request('/assets/app.js')), null);
});

test('returns a paginated Fractal server list and resolves identifiers', async () => {
    const backend = makeBackend();
    const listResponse = await backend.handle(request('/api/client?page=1'));
    const list = await json(listResponse);

    assert.equal(listResponse.status, 200);
    assert.equal(list.object, 'list');
    assert.equal(list.data.length, 2);
    assert.equal(list.meta.pagination.total, 2);
    assert.equal(list.data[0].attributes.name, 'Taiwan Minecraft 生存服');

    const identifier = list.data[0].attributes.identifier;
    const uuid = list.data[0].attributes.uuid;
    const byIdentifier = await json(await backend.handle(request(`/api/client/servers/${identifier}`)));
    const byUuid = await json(await backend.handle(request(`/api/client/servers/${uuid}`)));

    assert.equal(byIdentifier.attributes.uuid, uuid);
    assert.equal(byUuid.attributes.server_identifier, list.data[0].attributes.server_identifier);
    assert.equal(byIdentifier.meta.is_server_owner, true);
    assert.ok(byIdentifier.attributes.relationships.allocations.data.length > 0);
    assert.ok(byIdentifier.attributes.relationships.variables.data.length > 0);
});

test('serves resource usage, files, databases, schedules, backups, network, users, startup, and activity', async () => {
    const backend = makeBackend();
    const uuid = backend.snapshot().servers[0].uuid;

    const resources = await json(await backend.handle(request(`/api/client/servers/${uuid}/resources`)));
    assert.equal(resources.attributes.current_state, 'running');
    assert.ok(resources.attributes.resources.memory_bytes > 0);

    const files = await json(await backend.handle(request(`/api/client/servers/${uuid}/files/list?directory=/`)));
    assert.equal(files.object, 'list');
    assert.ok(files.data.some((entry) => entry.attributes.name === 'server.properties'));

    for (const endpoint of ['databases', 'schedules', 'backups', 'network/allocations', 'users', 'startup', 'activity']) {
        const response = await backend.handle(request(`/api/client/servers/${uuid}/${endpoint}`));
        assert.equal(response.status, 200, endpoint);
        const body = await json(response);
        assert.ok(Array.isArray(body.data), endpoint);
    }
});

test('persists safe demo mutations in memory', async () => {
    const backend = makeBackend();
    const server = backend.snapshot().servers[0];

    const renameResponse = await backend.handle(
        request(`/api/client/servers/${server.uuid}/settings/rename`, {
            method: 'POST',
            headers: { 'content-type': 'application/json' },
            body: JSON.stringify({ name: '改名成功的 Demo 服' }),
        })
    );
    assert.equal(renameResponse.status, 204);

    const renamed = await json(await backend.handle(request(`/api/client/servers/${server.identifier}`)));
    assert.equal(renamed.attributes.name, '改名成功的 Demo 服');

    const createDirectoryResponse = await backend.handle(
        request(`/api/client/servers/${server.uuid}/files/create-folder`, {
            method: 'POST',
            headers: { 'content-type': 'application/json' },
            body: JSON.stringify({ root: '/', name: 'plugins' }),
        })
    );
    assert.equal(createDirectoryResponse.status, 204);

    const files = await json(await backend.handle(request(`/api/client/servers/${server.uuid}/files/list?directory=/`)));
    assert.ok(files.data.some((entry) => entry.attributes.name === 'plugins' && !entry.attributes.is_file));
});

test('returns a JSON API error for an unimplemented client endpoint instead of leaking to a real backend', async () => {
    const backend = makeBackend();
    const response = await backend.handle(request('/api/client/definitely-not-real'));
    const body = await json(response);

    assert.equal(response.status, 501);
    assert.equal(body.errors[0].code, 'DemoEndpointNotImplemented');
    assert.match(body.errors[0].detail, /demo backend/i);
});

const {test} = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

function setup() {
    let change;
    const requests = [];
    const department = {value: '', dataset: {municipiosUrl: '/ajax'}, addEventListener: (_, callback) => change = callback};
    const municipality = {options: [], replaceChildren: option => municipality.options = [option], add: option => municipality.options.push(option)};
    vm.runInNewContext(fs.readFileSync('public/js/terminal-form.js', 'utf8'), {
        document: {
            addEventListener: (_, callback) => callback(),
            getElementById: id => id === 'departamento' ? department : municipality,
        },
        Option: function (text, value, defaultSelected, selected) {Object.assign(this, {text, value, selected});},
        fetch: url => new Promise(resolve => requests.push({url, resolve})),
    });
    return {
        department, municipality, requests,
        change: id => {department.value = id; return change();},
        respond: (index, items, ok = true) => requests[index].resolve({ok, json: async () => items}),
    };
}

test('latest department wins when responses arrive out of order', async () => {
    const state = setup();
    const first = state.change('1'), second = state.change('2');
    assert.equal(state.municipality.disabled, true);
    state.respond(1, [{id: 20, nombre: 'New department town'}]);
    await second;
    state.respond(0, [{id: 10, nombre: 'Old department town'}]);
    await first;
    assert.equal(state.municipality.options[1].value, 20);
    assert.equal(state.municipality.disabled, false);
});

test('clearing the department discards an outstanding response', async () => {
    const state = setup();
    const pending = state.change('1');
    await state.change('');
    state.respond(0, [{id: 10, nombre: 'Stale town'}]);
    await pending;
    assert.equal(state.municipality.options.length, 1);
    assert.equal(state.municipality.disabled, true);
    assert.equal(state.municipality.options[0].value, '');
});

test('failed request presents a retryable state', async () => {
    const state = setup();
    const pending = state.change('1');
    state.respond(0, [], false);
    await pending;
    assert.equal(state.municipality.disabled, false);
    assert.match(state.municipality.options[0].text, /No se pudieron cargar/);
});

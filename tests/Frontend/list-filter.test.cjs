const {test} = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

function setup(table) {
    let handler, scans = 0;
    const container = {contains: item => item === first};
    function item(text, ancestor = null) {
        return {
            textContent: text, hidden: false,
            parentElement: {closest: () => ancestor},
            classList: {toggle() {}},
        };
    }
    const first = item('Somoto'), second = item('Esteli'), child = item('nested content', first);
    container.querySelectorAll = selector => {
        scans++;
        return selector === 'tbody tr' ? (table ? [first, second] : []) : [first, second, child];
    };
    const notice = {classList: {toggle: (_, hidden) => notice.hidden = hidden}};
    const filter = {dataset: {filterTarget: 'list'}, querySelector: () => notice};
    const input = {matches: () => true, closest: () => filter, value: ''};
    vm.runInNewContext(fs.readFileSync('public/js/list-filter.js', 'utf8'), {
        document: {addEventListener: (_, callback) => handler = callback, getElementById: () => container},
    });
    return {first, second, child, notice, scans: () => scans, filter: term => {input.value = term; handler({target: input});}};
}

for (const table of [true, false]) {
    test(`${table ? 'table' : 'cards'}: filter, no matches, reset and cached index`, () => {
        const state = setup(table);
        state.filter(' SOMOTO ');
        assert.equal(state.first.hidden, false);
        assert.equal(state.second.hidden, true);
        assert.equal(state.child.hidden, false);
        const initialScans = state.scans();
        state.filter('does not exist');
        assert.equal(state.notice.hidden, false);
        state.filter('');
        assert.equal(state.first.hidden, false);
        assert.equal(state.second.hidden, false);
        assert.equal(state.notice.hidden, true);
        assert.equal(state.scans(), initialScans);
    });
}

const {helper, page, state} = require('js/app.js');

const empty = () => {
};

beforeEach(() => {
  jest.resetAllMocks();
});

test('local state', () => {
  page.register.mockImplementation((id, callback) => {
    callback({render: empty, show: empty, action: empty, listen: empty});
  });
  jest.isolateModules(() => {
    require('pages/files.js');
    expect(page.register.mock.calls.length).toEqual(1);
    expect(page.register.mock.calls[0][0]).toEqual('files');
    expect(state.get.mock.calls.length).toEqual(2);
    expect(state.get.mock.calls[0][0]).toEqual('files-selected');
    expect(state.get.mock.calls[1][0]).toEqual('files-available');
  });
});

test('render', () => {
  page.register.mockImplementation((id, callback) => {
    callback({render: (callback) => callback(), show: empty, action: empty, listen: empty});
  });
  helper.create.mockReturnValue(document.createElement('li'));
  helper.list.mockImplementation((selector, data, callback) => {
    const element = callback({name: 'folder', children: [{id: 0, name: 'name', original: 'original'}]});
    expect(element.innerHTML).toContain('folder');
    expect(element.innerHTML).toContain('name');
  });
  jest.isolateModules(() => {
    require('pages/files.js');
    expect(helper.list.mock.calls.length).toEqual(2);
    expect(helper.list.mock.calls[0][0]).toEqual('#files-selected');
    expect(helper.list.mock.calls[1][0]).toEqual('#files-available');
    expect(helper.style.mock.calls.length).toEqual(2);
    expect(helper.style.mock.calls[0]).toEqual(['#files p.sx-highlight', 'display', null])
    expect(helper.style.mock.calls[1]).toEqual(['#files p.sx-error', 'display', null])
  });
});

test('show', () => {
  page.register.mockImplementation((id, callback) => {
    callback({render: empty, show: (callback) => callback(), action: empty, listen: empty});
  });
  jest.isolateModules(() => {
    require('pages/files.js');
    expect(state.dispatch.mock.calls.length).toEqual(2);
    expect(state.dispatch.mock.calls[0][0]).toEqual('files-selected');
    expect(state.dispatch.mock.calls[1][0]).toEqual('files-available');
  });
});

test('action', () => {
  const event = new Event('submit');
  jest.spyOn(event, 'preventDefault');
  state.get.mockImplementation(() => [{children: [{id: 23}, {id: 42}, {id: 110}]}]);
  const target = {value: 'value', dataset: {up: '110', down: '23', remove: '42'}};
  const action = jest.fn();
  action.mockImplementation((selector, type, callback) => {
    callback(event, target);
  });
  page.register.mockImplementation((id, callback) => {
    callback({render: empty, show: empty, action, listen: empty});
  });
  jest.isolateModules(() => {
    require('pages/files.js');
    expect(action.mock.calls.length).toEqual(6);
    expect(action.mock.calls[0][0]).toEqual('#files-search');
    expect(action.mock.calls[0][1]).toEqual('input');
    expect(action.mock.calls[1][0]).toEqual('#files [data-up]');
    expect(action.mock.calls[1][1]).toEqual('click');
    expect(action.mock.calls[2][0]).toEqual('#files [data-down]');
    expect(action.mock.calls[2][1]).toEqual('click');
    expect(action.mock.calls[3][0]).toEqual('#files [data-remove]');
    expect(action.mock.calls[3][1]).toEqual('click');
    expect(action.mock.calls[4][0]).toEqual('#files-select');
    expect(action.mock.calls[4][1]).toEqual('click');
    expect(action.mock.calls[5][0]).toEqual('#files');
    expect(action.mock.calls[5][1]).toEqual('submit');
    expect(state.dispatch.mock.calls.length).toEqual(4);
    expect(state.dispatch.mock.calls[0][0]).toEqual('loading');
    expect(state.dispatch.mock.calls[0][1]).toEqual(true);
    expect(state.dispatch.mock.calls[1][0]).toEqual('files-available');
    expect(state.dispatch.mock.calls[1][1]).toEqual('value');
    expect(state.dispatch.mock.calls[2][0]).toEqual('files-select');
    expect(state.dispatch.mock.calls[3][0]).toEqual('files-save');
    expect(state.set.mock.calls.length).toEqual(3);
    expect(state.set.mock.calls[0][0]).toEqual('files-selected');
    expect(state.set.mock.calls[1][0]).toEqual('files-selected');
    expect(state.set.mock.calls[2][0]).toEqual('files-selected');
    expect(page.show.mock.calls.length).toEqual(1);
    expect(page.show.mock.calls[0][0]).toEqual('files-select');
    expect(event.preventDefault.mock.calls.length).toEqual(1);
  });
});

test('listen', () => {
  const listen = jest.fn();
  listen.mockImplementation((key, callback) => {
    if (key === 'files-save') {
      callback(true);
      callback(false);
    } else {
      callback([])
    }
  });
  page.register.mockImplementation((id, callback) => {
    callback({render: empty, show: empty, action: empty, listen});
  });
  jest.isolateModules(() => {
    require('pages/files.js');
    expect(listen.mock.calls.length).toEqual(3);
    expect(listen.mock.calls[0][0]).toEqual('files-selected');
    expect(listen.mock.calls[1][0]).toEqual('files-available');
    expect(listen.mock.calls[2][0]).toEqual('files-save');
    expect(state.dispatch.mock.calls.length).toEqual(3);
    expect(state.dispatch.mock.calls[0]).toEqual(['loading', false]);
    expect(state.dispatch.mock.calls[1]).toEqual(['loading', false]);
    expect(state.dispatch.mock.calls[2]).toEqual(['files-selected', null]);
  });
});

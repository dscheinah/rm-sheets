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
    require('pages/files/select.js');
    expect(page.register.mock.calls.length).toEqual(1);
    expect(page.register.mock.calls[0][0]).toEqual('files-select');
    expect(state.get.mock.calls.length).toEqual(3);
    expect(state.get.mock.calls[0][0]).toEqual('files-selected');
    expect(state.get.mock.calls[1][0]).toEqual('files-available');
    expect(state.get.mock.calls[2][0]).toEqual('files-select');
  });
});

test('render', () => {
  page.register.mockImplementation((id, callback) => {
    callback({render: (callback) => callback(), show: empty, action: empty, listen: empty});
  });
  helper.create.mockReturnValue(document.createElement('option'));
  helper.list.mockImplementation((selector, data, callback) => {
    const option = callback({name: 'folder'}, 42);
    expect(option.value).toEqual('42');
    expect(option.innerHTML).toEqual('folder');
  });
  jest.isolateModules(() => {
    require('pages/files/select.js');
    expect(helper.list.mock.calls.length).toEqual(1);
    expect(helper.list.mock.calls[0][0]).toEqual('#files-select-list');
  });
});

test('action', () => {
  const event = new Event('submit');
  jest.spyOn(event, 'preventDefault');
  const initialData = {
    'files-selected': [{}, {children: [{}]}],
    'files-available': [{children: [{original: 'irrelevant'}, {original: 'file-to-add'}]}],
    'files-select': ['file-to-add'],
  };
  state.get.mockImplementation(key => initialData[key]);
  page.register.mockImplementation((id, callback) => {
    const action = (selector, type, callback) => {
      expect(selector).toEqual('#files-select');
      expect(type).toEqual('submit');
      callback(event, {new: {value: 'new folder'}});
      callback(event, {new: {value: ''}, folder: {value: '1'}});
    };
    callback({render: empty, show: empty, action, listen: empty});
  });
  jest.isolateModules(() => {
    require('pages/files/select.js');
    expect(event.preventDefault.mock.calls.length).toEqual(2);
    expect(state.set.mock.calls.length).toEqual(2);
    expect(state.set.mock.calls[0][0]).toEqual('files-selected');
    const finalFolders = [
      {},
      {children: [{}, {id: 'new-1', original: 'file-to-add'}]},
      {name: 'new folder', children: [{id: 'new-0', original: 'file-to-add'}]}
    ];
    expect(state.set.mock.calls[0][1]).toEqual(finalFolders);
    expect(state.set.mock.calls[1][0]).toEqual('files-selected');
    expect(state.set.mock.calls[1][1]).toEqual(finalFolders);
    expect(page.hide.mock.calls.length).toEqual(2);
    expect(page.hide.mock.calls[0][0]).toEqual('files-select');
    expect(page.hide.mock.calls[1][0]).toEqual('files-select');
  });
});

test('listen', () => {
  const listen = jest.fn();
  listen.mockImplementation((key, callback) => callback([]));
  page.register.mockImplementation((id, callback) => {
    callback({render: empty, show: empty, action: empty, listen});
  });
  jest.isolateModules(() => {
    require('pages/files/select.js');
    expect(listen.mock.calls.length).toEqual(3);
    expect(listen.mock.calls[0][0]).toEqual('files-selected');
    expect(listen.mock.calls[1][0]).toEqual('files-available');
    expect(listen.mock.calls[2][0]).toEqual('files-select');
  });
});

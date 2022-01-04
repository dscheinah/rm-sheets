import * as files from 'js/repository/files.js';
import {loadAvailable} from 'js/repository/files.js';

beforeEach(() => {
  global.fetch = jest.fn();
});

test('loadSelected', async () => {
  global.fetch.mockResolvedValueOnce({
    ok: true,
    async json() {
      return ['success'];
    },
  });
  global.fetch.mockResolvedValueOnce({
    ok: true,
    async json() {
      return '';
    }
  });
  global.fetch.mockResolvedValueOnce({
    ok: false,
  });
  expect(await files.loadSelected()).toEqual(['success']);
  expect(global.fetch.mock.calls[0]).toEqual(['selected']);
  expect(await files.loadSelected()).toEqual([]);
  try {
    await files.loadSelected();
    expect(false).toBeTruthy();
  } catch (error) {
    expect(error.message).toEqual('Die aktuelle Auswahl konnte nicht geladen werden.');
  }
});

test('loadAvailable', async () => {
  const result = [
    {
      name: 'folder1',
      children: [{original: 'contains search'}, {original: 'SEARCH'}],
    },
    {
      name: 'folder2',
      children: [{original: 'does not contain'}],
    }
  ];
  global.fetch.mockResolvedValueOnce({
    ok: false,
  });
  global.fetch.mockResolvedValueOnce({
    ok: true,
    async json() {
      return result;
    },
  });
  try {
    await files.loadAvailable();
    expect(false).toBeTruthy();
  } catch (error) {
    expect(error.message).toEqual('Es konnten keine verfügbaren Dateien geladen werden.');
  }
  expect(await files.loadAvailable()).toEqual(result);
  expect(global.fetch.mock.calls[0]).toEqual(['available']);
  expect(await files.loadAvailable('search')).toEqual([result[0]]);
});

test('save', async () => {
  const data = ['data'];
  global.fetch.mockResolvedValueOnce({
    ok: true,
  });
  global.fetch.mockResolvedValueOnce({
    ok: false,
  });
  expect(await files.save(data)).toBeTruthy();
  expect(global.fetch.mock.calls[0]).toEqual(
    [
      'selected',
      {
        method: 'POST',
        headers: {'content-type': 'application/json'},
        body: JSON.stringify(data),
      },
    ]
  );
  expect(await files.save(data)).toBeFalsy();
});

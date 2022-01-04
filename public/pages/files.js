import {helper, page, state} from '../js/app.js';

page.register('files', ({render, show, action, listen}) => {
  let selected = state.get('files-selected');
  let available = state.get('files-available');
  let submitted = false;
  let error = false;

  render(() => {
    const selectedFiles = {};
    helper.list('#files-selected', selected || [], (folder) => {
      const li = helper.create('li');
      const list = [];
      folder.children.forEach((file) => {
        const moveUp = `<button type="button" title="nach oben" data-up="${file.id}">⇑</button>`;
        const moveDown = `<button type="button" title="nach unten" data-down="${file.id}">⇓</button>`;
        const remove = `<button type="button" title="entfernen" data-remove="${file.id}">🗑</button>`;
        list.push(`<li>${moveUp}${moveDown}<span>${file.name}</span>${remove}</li>`);
        selectedFiles[file.original] = true;
      });
      li.innerHTML = `<details open><summary>${folder.name}</summary><ol>${list.join('')}</ol></details>`;
      return li;
    });
    helper.list('#files-available', available || [], (folder) => {
      const li = helper.create('li');
      const list = [];
      folder.children.forEach((file) => {
        const disabled = selectedFiles[file.original] ? 'title="bereits ausgewählt" disabled' : '';
        const input = `<input type="checkbox" name="select" value="${file.original}" ${disabled}/>`;
        list.push(`<li class="sx-checkbox">${input}${file.name}</li>`);
      });
      li.innerHTML = `<details open><summary>${folder.name}</summary><ul>${list.join('')}</ul></details>`;
      return li;
    });
    helper.style('#files p.sx-highlight', 'display', submitted ? 'block' : null);
    helper.style('#files p.sx-error', 'display', error ? 'block' : null);
  });

  show(() => {
    if (!selected) {
      state.dispatch('files-selected', null);
    }
    if (!available) {
      state.dispatch('files-available', null);
    }
  });

  action('#files-search', 'input', (event, target) => {
    state.dispatch('loading', true);
    state.dispatch('files-available', target.value);
  });

  action('#files [data-up]', 'click', (event, target) => {
    selected.forEach((folder) => {
      const i = folder.children.findIndex(file => file.id.toString() === target.dataset.up);
      if (i >= 0) {
        [folder.children[i], folder.children[i - 1]] = [folder.children[i - 1], folder.children[i]]
      }
    });
    state.set('files-selected', selected);
  });

  action('#files [data-down]', 'click', (event, target) => {
    selected.forEach((folder) => {
      const i = folder.children.findIndex(file => file.id.toString() === target.dataset.down);
      if (i >= 0) {
        [folder.children[i], folder.children[i + 1]] = [folder.children[i + 1], folder.children[i]]
      }
    });
    state.set('files-selected', selected);
  });

  action('#files [data-remove]', 'click', (event, target) => {
    selected.forEach((folder) => {
      const i = folder.children.findIndex(file => file.id.toString() === target.dataset.remove);
      if (i >= 0) {
        folder.children.splice(i, 1);
      }
    });
    state.set('files-selected', selected);
  });

  action('#files-select', 'click', () => {
    const data = new FormData(helper.element('#files'));
    state.dispatch('files-select', data.getAll('select'));
    page.show('files-select');
  });

  action('#files', 'submit', (event) => {
    event.preventDefault();
    submitted = error = false;
    state.dispatch('files-save', selected);
  });

  listen('files-selected', (payload) => {
    selected = payload;
    state.dispatch('loading', false);
  });

  listen('files-available', (payload) => {
    available = payload;
    state.dispatch('loading', false);
  });

  listen('files-save', (success) => {
    if (success) {
      submitted = true;
      state.dispatch('files-selected', null);
    } else {
      error = true;
    }
  });
});

import {helper, page, state} from '../../js/app.js';

let counter = 0;

page.register('files-select', ({render, action, listen}) => {
  let folders = state.get('files-selected') || [];
  let available = state.get('files-available') || [];
  let selected = state.get('files-select') || [];

  render(() => {
    helper.list('#files-select-list', folders, (folder, index) => {
      const option = helper.create('option');
      option.value = index;
      option.innerHTML = folder.name;
      return option;
    });
  });

  action('#files-select', 'submit', (event, target) => {
    event.preventDefault();
    let targetFolder;
    if (target.new.value) {
      targetFolder = {name: target.new.value, children: []};
      folders.push(targetFolder)
      target.new.value = '';
    } else {
      targetFolder = folders[parseInt(target.folder.value)];
    }
    available.forEach((folder) => {
      folder.children.forEach((file) => {
        if (selected.includes(file.original)) {
          targetFolder.children.push({...file, id: `new-${counter++}`});
        }
      });
    });
    state.set('files-selected', folders);
    page.hide('files-select');
  });

  listen('files-selected', (payload) => {
    folders = payload;
  });

  listen('files-available', (payload) => {
    available = payload;
  });

  listen('files-select', (payload) => {
    selected = payload;
  });
});

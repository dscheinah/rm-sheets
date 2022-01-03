let available;

export async function loadSelected() {
  const response = await fetch('selected');
  if (!response.ok) {
    throw new Error('Die aktuelle Auswahl konnte nicht geladen werden.');
  }
  return await response.json() || [];
}

export async function loadAvailable(term) {
  if (!available) {
    const response = await fetch('available');
    if (!response.ok) {
      throw new Error('Es konnten keine verfügbaren Dateien geladen werden.');
    }
    available = await response.json();
  }
  if (term) {
    const filtered = [];
    available.forEach((folder) => {
      const filteredFolder = {name: folder.name, children: []};
      folder.children.forEach((file) => {
        if (file.original.toLowerCase().includes(term.toLowerCase())) {
          filteredFolder.children.push(file);
        }
      });
      if (filteredFolder.children.length) {
        filtered.push(filteredFolder);
      }
    });
    return filtered;
  }
  return available;
}

export async function save(data) {
  const response = await fetch('selected', {
    method: 'POST',
    headers: {
      'content-type': 'application/json',
    },
    body: JSON.stringify(data),
  });
  return response.ok;
}

let available;

export async function loadSelected() {
  return [
    {
      name: 'Ordner',
      children: [
        {
          name: 'Datei',
          original: 'Ordner/Datei',
          id: 1,
        },
        {
          name: 'Weitere Datei',
          original: 'Ordner 2/Weitere Datei',
          id: 2,
        },
      ],
    },
    {
      name: 'Nummer 2',
      children: [
        {
          name: 'Datei 2',
          original: 'Ordner/Unterordner/Datei 2',
          id: 3,
        },
      ],
    },
  ];
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

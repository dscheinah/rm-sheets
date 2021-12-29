let available = [
  {
    name: 'Ordner',
    children: [
      {
        name: 'Datei',
        original: 'Ordner/Datei',
      },
      {
        name: 'A',
        original: 'Ordner/A',
      },
    ],
  },
  {
    name: 'Ordner/Unterordner',
    children: [
      {
        name: 'Datei 2',
        original: 'Ordner/Unterordner/Datei 2',
      },
      {
        name: 'B',
        original: 'Ordner/Unterordner/B',
      },
    ],
  },
  {
    name: 'Ordner 2',
    children: [
      {
        name: 'Weitere Datei',
        original: 'Ordner 2/Weitere Datei',
      },
      {
        name: 'C',
        original: 'Ordner 2/C',
      },
    ],
  },
];

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

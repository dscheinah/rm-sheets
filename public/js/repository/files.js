export async function load() {
  return {
    selected: [
      {
        name: 'Ordner',
        children: [
          {
            name: 'Datei',
          },
          {
            name: 'Weitere Datei',
          },
        ],
      },
      {
        name: 'Nummer 2',
        children: [
          {
            name: 'Datei 2',
          },
        ],
      },
    ],
    available: [
      {
        name: 'Ordner',
        children: [
          {
            name: 'Datei',
          },
        ],
      },
      {
        name: 'Ordner/Unterordner',
        children: [
          {
            name: 'Datei 2',
          },
        ],
      },
      {
        name: 'Ordner 2',
        children: [
          {
            name: 'Weitere Datei',
          },
        ],
      },
    ],
  };
}

<?php

namespace App\Repository;

use App\Storage\SelectedStorage;
use Sx\Data\RepoInterface;

class SelectedRepository implements RepoInterface
{
	private SelectedStorage $storage;

	public function __construct(SelectedStorage $storage)
	{
		$this->storage = $storage;
	}

	public function load(): array
	{
		$data = [];
		foreach ($this->storage->fetchSelected() as $selected) {
			$folder = $selected['folder'];
			$data[$folder]['name'] = $folder;
			$data[$folder]['children'][] = [
				'id' => $selected['id'],
				'name' => basename($selected['source']),
				'original' => $selected['source'],
			];
		}
		return array_values($data);
	}

	public function save(array $data): void
	{
		$ids = [];
		foreach ($this->storage->fetchSelected() as $selected) {
			$id = $selected['id'];
			$ids[$id] = $id;
		}
		foreach ($data as $folder) {
			foreach ($folder['children'] ?? [] as $index => $file) {
				$id = $file['id'];
				$original = $file['original'];
				$folderName = $folder['name'];
				$target = sprintf(
					'%s/%s-%s.pdf',
					$folderName,
					str_pad($index, 3, '0', STR_PAD_LEFT),
					basename($original, '.pdf'),
				);
				if (isset($ids[$id])) {
					$this->storage->updateSelected($id, $original, $target, $folderName, $index);
					unset($ids[$id]);
				} else {
					$this->storage->insertSelected($original, $target, $folderName, $index);
				}
			}
		}
		foreach ($ids as $id) {
			$this->storage->deleteSelected($id);
		}
	}
}

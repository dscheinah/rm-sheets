<?php

namespace App\Repository;

class AvailableRepository
{
	private AvailableDirectoryProvider $provider;

	public function __construct(AvailableDirectoryProvider $provider)
	{
		$this->provider = $provider;
	}

	public function get(): array
	{
		$baseLength = strlen($this->provider->getBaseDirectory());
		$folders = [];
		foreach ($this->provider->getIterator() as $file) {
			if ($file->getExtension() !== 'pdf') {
				continue;
			}
			$path = trim(substr($file->getPath(), $baseLength), '/');
			$name = $file->getFilename();
			$folders[$path]['name'] = $path;
			$folders[$path]['children'][$name] = [
				'name' => $name,
				'original' => "$path/$name",
			];
		}
		ksort($folders);
		foreach ($folders as $index => $folder) {
			ksort($folder['children']);
			$folders[$index]['children'] = array_values($folder['children']);
		}
		return array_values($folders);
	}
}

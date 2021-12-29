<?php

namespace App\Repository;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AvailableRepository
{
	private string $folder;

	public function __construct(string $folder)
	{
		$this->folder = $folder;
	}

	public function get(): array
	{
		$folders = [];
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->folder)) as $file) {
			if ($file->getExtension() !== 'pdf') {
				continue;
			}
			$path = trim(substr($file->getPath(), strlen($this->folder)), '/');
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

<?php

namespace App\Repository;

use Iterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AvailableDirectoryProvider
{
	private string $folder;

	public function __construct(string $folder)
	{
		$this->folder = $folder;
	}

	public function getBaseDirectory(): string
	{
		return $this->folder;
	}

	public function getIterator(): Iterator
	{
		return new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->folder));
	}
}

<?php

namespace App\Storage;

use Generator;
use Sx\Data\Storage;

class SelectedStorage extends Storage
{
	public function fetchSelected(): Generator
	{
		yield from $this->fetch('SELECT * FROM `selected` ORDER BY `ordering`;');
	}

	public function updateSelected(int $id, string $source, string $target, string $folder, int $ordering): int
	{
		return $this->execute(
			'UPDATE `selected` SET `source` = ?, `target` = ?, `folder` = ?, `ordering` = ? WHERE `id` = ?;',
			[$source, $target, $folder, $ordering, $id]
		);
	}

	public function insertSelected(string $source, string $target, string $folder, int $ordering): int
	{
		return $this->insert(
			'INSERT INTO `selected` (`source`, `target`, `folder`, `ordering`) VALUES(?, ?, ?, ?);',
			[$source, $target, $folder, $ordering]
		);
	}

	public function deleteSelected(int $id): int
	{
		return $this->execute(
			'DELETE FROM `selected` WHERE `id` = ?;',
			[$id]
		);
	}
}

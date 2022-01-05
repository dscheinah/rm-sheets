<?php

namespace App\Repository;

use App\Storage\SelectedStorage;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Sx\Data\RepoInterface;

class SelectedRepository implements RepoInterface
{
	private SelectedStorage $storage;

	private StreamFactoryInterface $streamFactory;

	private string $folder;

	public function __construct(SelectedStorage $storage, StreamFactoryInterface $streamFactory, string $folder)
	{
		$this->storage = $storage;
		$this->streamFactory = $streamFactory;
		$this->folder = $folder;
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
		$uniq = microtime(true);

		$ids = [];
		$folders = [];
		foreach ($this->storage->fetchSelected() as $selected) {
			$id = $selected['id'];
			$folder = $selected['folder'];
			$ids[$id] = $selected;
			$folders[$folder] = $folder;
		}

		$mv = $this->streamFactory->createStreamFromFile($this->folder . '/mv_' . $uniq, 'wb');
		$restore = $this->streamFactory->createStreamFromFile($this->folder . '/restore_' . $uniq, 'wb');
		foreach ($data as $folder) {
			foreach ($folder['children'] ?? [] as $index => $file) {
				$id = $file['id'];
				$original = $file['original'];
				$folderName = $folder['name'];
				$target = sprintf(
					'%s/%s-%s',
					$folderName,
					str_pad($index, 3, '0', STR_PAD_LEFT),
					basename($original),
				);
				if (isset($ids[$id])) {
					$this->storage->updateSelected($id, $original, $target, $folderName, $index);
					$this->appendMvEntry($mv, $ids[$id]['target'], $target);
					unset($ids[$id]);
				} else {
					$this->storage->insertSelected($original, $target, $folderName, $index);
					$this->appendMvEntry($mv, $original, $target);
				}
			}
		}
		foreach ($ids as $id => $selected) {
			$this->storage->deleteSelected($id);
			$this->appendMvEntry($restore, $selected['target'], $selected['source']);
		}

		$exclude = $this->streamFactory->createStreamFromFile($this->folder . '/exclude', 'wb');
		$mkdir = $this->streamFactory->createStreamFromFile($this->folder . '/mkdir', 'wb');
		foreach ($this->storage->fetchSelected() as $selected) {
			$exclude->write($selected['source'] . "\n");
			$folder = $selected['folder'];
			$mkdir->write($folder . "\n");
			unset($folders[$folder]);
		}
		$rm = $this->streamFactory->createStreamFromFile($this->folder . '/rm_' . $uniq, 'wb');
		foreach ($folders as $folder) {
			$rm->write($folder . "\n");
		}
	}

	private function appendMvEntry(StreamInterface $stream, string $source, string $target): void
	{
		if ($source === $target) {
			return;
		}
		$source = pathinfo($source, PATHINFO_DIRNAME) . '/' . basename($source, '.pdf');
		$target = pathinfo($target, PATHINFO_DIRNAME) . '/' . basename($target, '.pdf');
		$stream->write(sprintf('"%s" "%s"', $source, $target) . "\n");
	}
}

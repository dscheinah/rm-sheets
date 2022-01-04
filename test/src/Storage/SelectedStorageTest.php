<?php

namespace AppTest\Storage;

use App\Storage\SelectedStorage;
use PHPUnit\Framework\TestCase;
use stdClass;
use Sx\Data\BackendInterface;

class SelectedStorageTest extends TestCase
{
	private SelectedStorage $storage;

	private BackendInterface $backend;

	protected function setUp(): void
	{
		parent::setUp();
		$this->backend = $this->createMock(BackendInterface::class);
		$this->storage = new SelectedStorage($this->backend);
	}

	public function testDeleteSelected(): void
	{
		$id = 23;
		$result = 42;
		$this->backend->expects($this->once())
			->method('prepare')
			->with('DELETE FROM `selected` WHERE `id` = ?;');
		$this->backend->expects($this->once())
			->method('execute')
			->with($this->anything(), [$id])
			->willReturn($result);
		self::assertEquals($result, $this->storage->deleteSelected($id));
	}

	public function testUpdateSelected(): void
	{
		$id = 23;
		$source = 'source';
		$target = 'target';
		$folder = 'folder';
		$ordering = 110;
		$result = 42;
		$this->backend->expects($this->once())
			->method('prepare')
			->with('UPDATE `selected` SET `source` = ?, `target` = ?, `folder` = ?, `ordering` = ? WHERE `id` = ?;');
		$this->backend->expects($this->once())
			->method('execute')
			->with($this->anything(), [$source, $target, $folder, $ordering, $id])
			->willReturn($result);
		self::assertEquals($result, $this->storage->updateSelected($id, $source, $target, $folder, $ordering));
	}

	public function testInsertSelected(): void
	{
		$source = 'source';
		$target = 'target';
		$folder = 'folder';
		$ordering = 110;
		$result = 42;
		$this->backend->expects($this->once())
			->method('prepare')
			->with('INSERT INTO `selected` (`source`, `target`, `folder`, `ordering`) VALUES(?, ?, ?, ?);');
		$this->backend->expects($this->once())
			->method('insert')
			->with($this->anything(), [$source, $target, $folder, $ordering])
			->willReturn($result);
		self::assertEquals($result, $this->storage->insertSelected($source, $target, $folder, $ordering));
	}

	public function testFetchSelected(): void
	{
		$data = ['entries', 'from', 'database'];
		$this->backend->expects($this->once())
			->method('prepare')
			->with('SELECT * FROM `selected` ORDER BY `folder`, `ordering`;');
		$this->backend->expects($this->once())
			->method('fetch')
			->willReturnCallback(
				function () use ($data) {
					yield from $data;
				}
			);
		self::assertEquals($data, iterator_to_array($this->storage->fetchSelected()));
	}
}

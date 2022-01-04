<?php

namespace AppTest\Repository;

use App\Repository\AvailableDirectoryProvider;
use App\Repository\AvailableRepository;
use Iterator;
use PHPUnit\Framework\TestCase;
use RecursiveIteratorIterator;
use SplFileInfo;

class AvailableRepositoryTest extends TestCase
{
	private AvailableRepository $repository;

	private AvailableDirectoryProvider $provider;

	protected function setUp(): void
	{
		parent::setUp();
		$this->provider = $this->createMock(AvailableDirectoryProvider::class);
		$this->repository = new AvailableRepository($this->provider);
	}

	public function testGet(): void
	{
		$fileFirstInFirstFolder = $this->createMock(SplFileInfo::class);
		$fileFirstInFirstFolder->method('getExtension')->willReturn('pdf');
		$fileFirstInFirstFolder->method('getPath')->willReturn('/base/folder1');
		$fileFirstInFirstFolder->method('getFilename')->willReturn('file1.pdf');
		$fileSecondInFirstFolder = $this->createMock(SplFileInfo::class);
		$fileSecondInFirstFolder->method('getExtension')->willReturn('pdf');
		$fileSecondInFirstFolder->method('getPath')->willReturn('/base/folder1');
		$fileSecondInFirstFolder->method('getFilename')->willReturn('file3.pdf');
		$fileNoPdf = $this->createMock(SplFileInfo::class);
		$fileNoPdf->method('getExtension')->willReturn('doc');
		$fileInSecondFolder = $this->createMock(SplFileInfo::class);
		$fileInSecondFolder->method('getExtension')->willReturn('pdf');
		$fileInSecondFolder->method('getPath')->willReturn('/base/folder2');
		$fileInSecondFolder->method('getFilename')->willReturn('file2.pdf');

		$iterator = $this->createMock(Iterator::class);
		$iterator->method('valid')->willReturnOnConsecutiveCalls(true, true, true, true, false);
		$iterator->method('current')->willReturnOnConsecutiveCalls(
			$fileFirstInFirstFolder, $fileNoPdf, $fileInSecondFolder, $fileSecondInFirstFolder
		);

		$this->provider->method('getBaseDirectory')->willReturn('/base');
		$this->provider->method('getIterator')->willReturn($iterator);
		self::assertEquals(
			[
				[
					'name' => 'folder1',
					'children' => [
						[
							'name' => 'file1.pdf',
							'original' => 'folder1/file1.pdf',
						],
						[
							'name' => 'file3.pdf',
							'original' => 'folder1/file3.pdf',
						],
					],
				],
				[
					'name' => 'folder2',
					'children' => [
						[
							'name' => 'file2.pdf',
							'original' => 'folder2/file2.pdf',
						],
					],
				],
			],
			$this->repository->get()
		);
	}
}

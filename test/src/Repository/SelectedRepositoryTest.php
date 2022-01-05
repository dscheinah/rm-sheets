<?php

namespace AppTest\Repository;

use App\Repository\SelectedRepository;
use App\Storage\SelectedStorage;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class SelectedRepositoryTest extends TestCase
{
	private const PERSISTED_DATA = [
		['id' => 1, 'folder' => 'folder1', 'source' => '/some/where/source1.pdf', 'target' => 'folder1/000-source1.pdf'],
		['id' => 2, 'folder' => 'folder2', 'source' => '/some/other/source2.pdf', 'target' => 'folder2/000-source2.pdf'],
		['id' => 3, 'folder' => 'folder1', 'source' => '/folder/source3.pdf', 'target' => 'folder1/001-source3.pdf'],
	];

	private SelectedRepository $repository;

	private SelectedStorage $storage;

	private StreamInterface $stream;

	protected function setUp(): void
	{
		parent::setUp();
		$this->storage = $this->createMock(SelectedStorage::class);
		$this->storage->method('fetchSelected')->willReturnCallback(
			function () {
				yield from self::PERSISTED_DATA;
			}
		);
		$this->stream = $this->createMock(StreamInterface::class);
		$streamFactory = $this->createMock(StreamFactoryInterface::class);
		$streamFactory->method('createStreamFromFile')->willReturn($this->stream);
		$this->repository = new SelectedRepository($this->storage, $streamFactory, '/output');
	}

	public function testLoad(): void
	{
		self::assertEquals(
			[
				[
					'name' => 'folder1',
					'children' => [
						[
							'id' => 1,
							'name' => 'source1.pdf',
							'original' => '/some/where/source1.pdf',
						],
						[
							'id' => 3,
							'name' => 'source3.pdf',
							'original' => '/folder/source3.pdf',
						],
					],
				],
				[
					'name' => 'folder2',
					'children' => [
						[
							'id' => 2,
							'name' => 'source2.pdf',
							'original' => '/some/other/source2.pdf',
						],
					],
				],
			],
			$this->repository->load()
		);
	}

	public function testSave(): void
	{
		$data = [
			[
				'name' => 'folder1',
				'children' => [
					[
						'id' => 1,
						'original' => '/some/where/source1.pdf',
					],
				],
			],
			[
				'name' => 'folder2',
				'children' => [
					[
						'id' => 'new-4',
						'original' => '/new/source4.pdf',
					],
					[
						'id' => 2,
						'original' => '/some/other/source2.pdf',
					],
				],
			],
		];
		$this->storage->expects($this->exactly(2))
			->method('updateSelected')
			->withConsecutive(
				[1, '/some/where/source1.pdf', 'folder1/000-source1.pdf', 'folder1', 0],
				[2, '/some/other/source2.pdf', 'folder2/001-source2.pdf', 'folder2', 1],
			);
		$this->storage->expects($this->once())
			->method('insertSelected')
			->with('/new/source4.pdf', 'folder2/000-source4.pdf', 'folder2', 0);
		$this->storage->expects($this->once())
			->method('deleteSelected')
			->with(3);
		$this->stream->expects($this->exactly(9))
			->method('write');
		$this->repository->save($data);
	}

}

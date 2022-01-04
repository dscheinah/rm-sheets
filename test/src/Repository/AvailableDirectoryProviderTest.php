<?php

namespace AppTest\Repository;

use App\Repository\AvailableDirectoryProvider;
use PHPUnit\Framework\TestCase;

class AvailableDirectoryProviderTest extends TestCase
{
	private AvailableDirectoryProvider $provider;

	private string $folder;

	protected function setUp(): void
	{
		parent::setUp();
		$this->folder = sys_get_temp_dir();
		$this->provider = new AvailableDirectoryProvider($this->folder);
	}

	public function testGetIterator(): void
	{
		$this->provider->getIterator();
		self::assertTrue(true);
	}

	public function testGetBaseDirectory(): void
	{
		self::assertEquals($this->folder, $this->provider->getBaseDirectory());
	}
}

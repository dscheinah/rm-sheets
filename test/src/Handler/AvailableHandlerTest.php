<?php

namespace AppTest\Handler;

use App\Handler\AvailableHandler;
use App\Repository\AvailableRepository;
use AppTest\Handler\Mock\Response;
use AppTest\Handler\Mock\ResponseHelper;
use PHPUnit\Framework\TestCase;
use Sx\Message\ServerRequest;

class AvailableHandlerTest extends TestCase
{
	public function testHandle(): void
	{
		$data = ['folders', 'and', 'files'];
		$repositoryMock = $this->createMock(AvailableRepository::class);
		$repositoryMock->method('get')->willReturn($data);
		$handler = new AvailableHandler(new ResponseHelper(), $repositoryMock);
		/* @var Response $response */
		$response = $handler->handle(new ServerRequest());
		self::assertEquals(200, $response->getStatusCode());
		self::assertSame($data, $response->data);
	}
}

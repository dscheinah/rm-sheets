<?php

namespace AppTest\Handler;

use App\Handler\SelectedHandler;
use App\Repository\SelectedRepository;
use AppTest\Handler\Mock\Response;
use AppTest\Handler\Mock\ResponseHelper;
use PHPUnit\Framework\TestCase;
use Sx\Message\ServerRequest;

class SelectedHandlerTest extends TestCase
{
	public function testHandle(): void
	{
		$data = ['selected', 'files'];
		$repositoryMock = $this->createMock(SelectedRepository::class);
		$repositoryMock->method('load')->willReturn($data);
		$handler = new SelectedHandler(new ResponseHelper(), $repositoryMock);
		/* @var Response $response */
		$response = $handler->handle(new ServerRequest());
		self::assertEquals(200, $response->getStatusCode());
		self::assertSame($data, $response->data);
	}
}

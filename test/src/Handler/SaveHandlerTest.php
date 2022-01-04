<?php

namespace AppTest\Handler;

use App\Handler\SaveHandler;
use App\Repository\SelectedRepository;
use AppTest\Handler\Mock\ResponseHelper;
use PHPUnit\Framework\TestCase;
use Sx\Message\ServerRequest;
use Sx\Message\StreamFactory;

class SaveHandlerTest extends TestCase
{
	public function testHandle(): void
	{
		$requestData = ['data', 'to', 'save'];

		$streamFactory = new StreamFactory();

		$request = new ServerRequest();
		/* @var ServerRequest $request */
		$request = $request->withBody($streamFactory->createStream(json_encode($requestData, JSON_THROW_ON_ERROR)));

		$repositoryMock = $this->createMock(SelectedRepository::class);
		$repositoryMock->expects($this->once())->method('save')->with($this->equalTo($requestData));

		$handler = new SaveHandler(new ResponseHelper(), $repositoryMock);
		$response = $handler->handle($request);
		self::assertEquals(204, $response->getStatusCode());
	}
}

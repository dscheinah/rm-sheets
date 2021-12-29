<?php

namespace App\Handler;

use App\Repository\AvailableRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sx\Message\Response\ResponseHelperInterface;

class AvailableHandler implements RequestHandlerInterface
{
	private ResponseHelperInterface $helper;

	private AvailableRepository $repository;

	public function __construct(ResponseHelperInterface $helper, AvailableRepository $repository)
	{
		$this->helper = $helper;
		$this->repository = $repository;
	}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		return $this->helper->create(200, $this->repository->get());
	}
}

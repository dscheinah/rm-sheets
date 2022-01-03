<?php

namespace App\Handler;

use App\Repository\SelectedRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sx\Message\Response\ResponseHelperInterface;

class SelectedHandler implements RequestHandlerInterface
{
	private ResponseHelperInterface $helper;

	private SelectedRepository $repository;

	public function __construct(ResponseHelperInterface $helper, SelectedRepository $repository)
	{
		$this->helper = $helper;
		$this->repository = $repository;
	}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		return $this->helper->create(200, $this->repository->load());
	}
}

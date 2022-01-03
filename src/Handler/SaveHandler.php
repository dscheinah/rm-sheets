<?php

namespace App\Handler;

use App\Repository\SelectedRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sx\Message\Response\ResponseHelperInterface;

class SaveHandler implements RequestHandlerInterface
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
		$this->repository->save(json_decode($request->getBody(), true, 512, JSON_THROW_ON_ERROR));
		return $this->helper->create(204);
	}
}

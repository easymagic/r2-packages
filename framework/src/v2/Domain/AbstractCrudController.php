<?php 
namespace R2Packages\Framework\v2\Domain;

use R2Packages\Framework\v2\Interfaces\RepositoryInterface;
use R2Packages\Framework\v2\Interfaces\ServiceInterface;

abstract class AbstractCrudController
{

    private RepositoryInterface $repository;
    private ServiceInterface $service;

    public function __construct(RepositoryInterface $repository, ServiceInterface $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        jsonResponse([
            'data' => $this->service->fetch($this->repository)
        ]);
    }

    public function create(){
        $response = $this->service->create($this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function update(){
        $response = $this->service->update($this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function destroy(){
        $response = $this->service->delete($this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function show(){
        $response = $this->service->find($this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function count(){
        $response = $this->service->count($this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function sum(){
        $response = $this->service->sum($this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }
}
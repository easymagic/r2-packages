<?php 
namespace R2Packages\Framework\v2\Domain;

use R2Packages\Framework\Request;
use R2Packages\Framework\v2\Interfaces\RepositoryInterface;
use R2Packages\Framework\v2\Interfaces\ServiceInterface;

abstract class AbstractCrudController
{

    private RepositoryInterface $repository;
    private Request $request;
    private ServiceInterface $service;

    public function __construct(Request $request, RepositoryInterface $repository, ServiceInterface $service)
    {
        $this->request = $request;
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        jsonResponse([
            'data' => $this->service->fetch($this->request, $this->repository)
        ]);
    }

    public function create(){
        $data = $this->service->validateCreate($this->request);
        $response = $this->service->create($data, $this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function update(){
        $data = $this->service->validateUpdate($this->request);
        $entity = $this->service->fetchById($this->request, $this->repository);
        $response = $this->service->update($data, $entity, $this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function destroy(){
        $entity = $this->service->fetchById($this->request, $this->repository);
        $response = $this->service->delete($entity, $this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function show(){
        $response = $this->service->find($this->request, $this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function count(){
        $response = $this->service->count($this->request, $this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }

    public function sum(){
        $response = $this->service->sum($this->request, $this->repository);
        return jsonResponse([
            'data' => $response
        ]);
    }
}
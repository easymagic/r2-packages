<?php

namespace R2Packages\Framework\Feature;

use R2Packages\Framework\Feature\FeatureEntity;
use R2Packages\Framework\FeatureSetting\FeatureSettingRepository;
use R2Packages\Framework\Repositories\DbRepository;

class FeatureRepository
{

    private FeatureEntity $featureEntity;
    private DbRepository $dbRepository;

    private FeatureSettingRepository $featureSettingRepository;

    private $size = 11;
    private $sql = '';
    private $params = [];
    

    public function __construct(
        FeatureEntity $featureEntity,
        DbRepository $dbRepository,
        FeatureSettingRepository $featureSettingRepository,
    ) {
        $this->featureEntity = $featureEntity;
        $this->dbRepository = $dbRepository;
        $this->featureSettingRepository = $featureSettingRepository;
        $this->sql = "SELECT * FROM features WHERE 1=1";
    }

    public function hydrate($data)
    {
        $featureSettings = $this->featureSettingRepository->filterByFeatureId($data['id'])->fetchAll();
        $feature = $this->featureEntity->newInstance($featureSettings, $data);
        return $feature;
    }

    public function find($id)
    {
        $result = $this->dbRepository->fetchOne("SELECT * FROM features WHERE id = ?", [$id]);
        return $this->hydrate($result);
    }

    public function findByName($name)
    {
        $result = $this->dbRepository->fetchOne("SELECT * FROM features WHERE name = ?", [$name]);
        return $this->hydrate($result);
    }

    function fetch()
    {
        $results = $this->dbRepository->paginate($this->sql, $this->size, $this->params);
        return array_map(function ($result) {
            return $this->hydrate($result);
        }, $results);
    }

    function count()
    {
        return $this->dbRepository->count($this->sql, $this->params);
    }

    function fetchAll()
    {
        $results = $this->dbRepository->fetchAll($this->sql, $this->params);
        return array_map(function ($result) {
            return $this->hydrate($result);
        }, $results);
    }

    function save($id, $data)
    {
        if ($id > 0) {
            $this->dbRepository->update("features", $data, ["id" => $id]);
            return $this->find($id);
        } else {
            $id = $this->dbRepository->insert("features", $data);
            return $this->find($id);
        }
    }

    function delete($id)
    {
        $this->dbRepository->delete("features", ["id" => $id]);
        return true;
    }

}

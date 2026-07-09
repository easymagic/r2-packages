<?php

namespace R2Packages\Framework\FeatureSetting;

use R2Packages\Framework\FeatureSetting\FeatureSettingEntity;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class FeatureSettingRepository
{

    private FeatureSettingEntity $featureSettingEntity;
    private DbRepository $dbRepository;
    private Request $request;

    private $sql = '';
    private $params = [];

    public function __construct(
        FeatureSettingEntity $featureSettingEntity,
        DbRepository $dbRepository,
        Request $request
    ) {
        $this->featureSettingEntity = $featureSettingEntity;
        $this->dbRepository = $dbRepository;
        $this->request = $request;
        $this->sql = "SELECT * FROM feature_settings WHERE 1=1";
        $this->params = [];

        if (!$this->request->isEmpty('feature_id')) {
          $this->filterByFeatureId($this->request->get('feature_id'));
        }
    }

    function filterByFeatureId($featureId)
    {
        $this->sql .= " AND feature_id = ?";
        $this->params[] = $featureId;
        return $this;
    }

    function filterBySettingKey($settingKey)
    {
        $this->sql .= " AND setting_key = ?";
        $this->params[] = $settingKey;
        return $this;
    }


    public function hydrate($data)
    {
        $featureSetting = $this->featureSettingEntity->newInstance($data);
        return $featureSetting;
    }

    public function find($id)
    {
        $result = $this->dbRepository->fetchOne($this->sql, [$id]);
        return $this->hydrate($result);
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

    function fetchOne()
    {
        $result = $this->dbRepository->fetchOne($this->sql, $this->params);
        return $this->hydrate($result);
    }

    function save($id, $data)
    {
        if ($id > 0) {
            $this->dbRepository->update("feature_settings", $data, ["id" => $id]);
            return $this->find($id);
        } else {
            $id = $this->dbRepository->insert("feature_settings", $data);
            return $this->find($id);
        }
    }

    function delete($id)
    {
        $this->dbRepository->delete("feature_settings", ["id" => $id]);
        return true;
    }
}

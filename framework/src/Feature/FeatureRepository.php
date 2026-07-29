<?php

namespace R2Packages\Framework\Feature;

use R2Packages\Framework\Feature\FeatureEntity;
use R2Packages\Framework\FeatureSetting\FeatureSettingRepository;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Ports\AbstractRepositoryPort;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class FeatureRepository extends AbstractRepositoryPort
{

    private FeatureEntity $featureEntity;
    protected $table = 'features';
    protected $sql = 'SELECT * FROM features WHERE 1=1';

    private FeatureSettingRepository $featureSettingRepository;


    public function __construct(
        FeatureEntity $featureEntity,
        DbRepository $dbRepository,
        FeatureSettingRepository $featureSettingRepository,
        PaginationMetta $paginationMeta,
        Request $request
    ) {
        parent::__construct($dbRepository, $paginationMeta, $request);
        $this->featureEntity = $featureEntity;
        $this->featureSettingRepository = $featureSettingRepository;
    }

    protected function applyCommonFilters()
    {
        if (!$this->request->isEmpty('name')) {
            $this->filterByName($this->request->get('name'));
        }
    }

    /**
     * Filter the features by name
     * @param string $name
     * @return $this
     */
    function filterByName($name)
    {
        $this->sql .= " AND name LIKE '%" . $name . "%'";
        $this->params[] = $name;
        return $this;
    }

    protected function hydrate($data)
    {
        $featureSettings = $this->featureSettingRepository->filterByFeatureId($data['id'])->fetchAll();
        $feature = $this->featureEntity->newInstance($featureSettings, $data);
        return $feature;
    }


    public function findByName($name)
    {
        $result = $this->dbRepository->fetchOne("SELECT * FROM {$this->table} WHERE name = ?", [$name]);
        return $this->hydrate($result);
    }

}

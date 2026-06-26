<?php 

namespace R2Packages\Framework\Repositories;

use R2Packages\Framework\Entities\SettingEntity;

class SettingsRepository {

    private SettingEntity $settingEntity;
    private DbRepository $dbRepository;

    public function __construct(SettingEntity $settingEntity, DbRepository $dbRepository)
    {
        $this->settingEntity = $settingEntity;
        $this->dbRepository = $dbRepository;
    }

    /**
     * Hydrate a setting
     * @param array $data
     * @return SettingEntity
     */
    public function hydrate($data)
    {
        return $this->settingEntity->newInstance($data);
    }

    /**
     * Find a setting by key
     * @param string $key
     * @return SettingEntity
     */
    public function findByKey($key)
    {
        $result = $this->dbRepository->fetchOne("SELECT * FROM settings WHERE `key` = ?", [$key]);
        return $this->hydrate($result);
    }

    public function findAll()
    {
        $results = $this->dbRepository->fetchAll("SELECT * FROM settings");
        return array_map(function ($result) {
            return $this->hydrate($result);
        }, $results);
    }

    /**
     * Find a setting by id
     * @param int $id
     * @return SettingEntity
     */
    public function find($id)
    {
        $result = $this->dbRepository->fetchOne("SELECT * FROM settings WHERE id = ?", [$id]);
        return $this->hydrate($result);
    }

    /**
     * Save a setting
     * @param int $id
     * @param array $data
     * @return SettingEntity
     */
    public function save($id, $data){
        if ($id > 0){
            $this->dbRepository->update("settings", $data, ["id" => $id]);
            return $this->find($id);
        }else{
            $id = $this->dbRepository->insert("settings", $data);
            return $this->find($id);
        }
    }
}
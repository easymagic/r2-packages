<?php

namespace R2Packages\Framework\Repositories;

use Exception;
use R2Packages\Framework\Container;
use R2Packages\Framework\Entities\BaseUserEntity;

class BaseUserRepository
{

    private $savedCache = [];
    private $savedCacheId = 0;

    /**
     * Find a user by email
     * @param string $email
     * @return BaseUserEntity
     * @throws Exception
     */
    public function findByEmail($email)
    {
        $result = dbFetchOne("SELECT * FROM users WHERE email = ?", [$email]);
        /** @var BaseUserEntity $user */
        $user = Container::getInstance()->get(BaseUserEntity::class, $result);
        if ($user->isEmpty()) {
            throw new Exception("User not found");
        }
        return $user;
    }

    /**
     * Find a user by id
     * @param int $id
     * @return BaseUserEntity
     * @throws Exception
     */
    function find($id)
    {
        $result = dbFetchOne("SELECT * FROM users WHERE id = ?", [$id]);
        /** @var BaseUserEntity $user */
        $user = Container::getInstance()->get(BaseUserEntity::class, $result);
        if ($user->isEmpty()) {
            throw new Exception("User not found");
        }
        return $user;
    }

    /**
     * Save a user to cache
     * @param int $id
     * @param array $data
     * @return BaseUserEntity
     * @throws Exception
     */
    function saveCache($id,$data = []){
       $this->savedCacheId = $id;
       foreach ($data as $key => $value) {
        $this->savedCache[$key] = $value;
       }
       return $this;
    }

    function getCache(){
        return $this->savedCache;
    }

    function getCacheId(){
        return $this->savedCacheId;
    }

    /**
     * Save a user
     * @param int $id
     * @param array $data
     * @return BaseUserEntity
     * @throws Exception
     */
    function save($id, $data)
    {
        if ($id > 0) {
            dbUpdate("users", $data, ["id" => $id]);
            return $this->find($id);
        } else {
            $id = dbInsert("users", $data);
            return $this->find($id);
        }
    }


}

<?php

namespace R2Packages\Framework\Repositories;

use Exception;
use R2Packages\Framework\Container;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Event;

class BaseUserRepository
{

    private $savedCache = [];
    private $savedCacheId = 0;

    const HOOK_FILTER_USERS = 'user.filter.users';
    const HOOK_SELECT_SQL = 'user.select.sql';

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

    private function commonFilters($filters){
        $sql = "SELECT * FROM users WHERE 1=1";
        Event::getInstance()->dispatch(self::HOOK_SELECT_SQL, $sql, $filters);
        $params = [];
        if(count($filters) > 0){

            if(isset($filters['email'])){
                $sql .= " AND email = ?";
                $params[] = $filters['email'];
            }
            if(isset($filters['phone'])){
                $sql .= " AND phone = ?";
                $params[] = $filters['phone'];
            }
            if(isset($filters['status'])){
                $sql .= " AND status = ?";
                $params[] = $filters['status'];
            }
            if(isset($filters['role'])){
                $sql .= " AND role = ?";
                $params[] = $filters['role'];
            }
            if(isset($filters['created_at'])){
                $sql .= " AND created_at = ?";
                $params[] = $filters['created_at'];
            }
            if(isset($filters['updated_at'])){
                $sql .= " AND updated_at = ?";
                $params[] = $filters['updated_at'];
            }

            Event::getInstance()->dispatch(self::HOOK_FILTER_USERS, $sql, $params);

        }

        return [$sql, $params];
    }

    function fetchAll($filters = []){
        [$sql, $params] = $this->commonFilters($filters);
        return dbFetchAll($sql, $params);
    }

    function fetch($filters = [],$size = 11){
        [$sql, $params] = $this->commonFilters($filters);
        return dbPaginate($sql, $size, $params);
    }

    function count($filters = []){
        [$sql, $params] = $this->commonFilters($filters);
        return dbCount($sql, $params);
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

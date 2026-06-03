<?php

namespace R2Packages\Framework\Repositories;

use Exception;
use R2Packages\Framework\Container;
use R2Packages\Framework\Criteria\BaseUserFilterCriteria;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Event;
use R2Packages\Framework\Traits\WithEvents;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Request;

class BaseUserRepository
{

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $filters = [];
    protected $size = 11;
    protected $sql = '';
    protected $params = [];

    protected BaseUserEntity $baseUserEntity;
    protected DbRepository $dbRepository;


    function __construct(
        BaseUserEntity $baseUserEntity,
        DbRepository $dbRepository,
        PaginationMetta $paginationMetta,
        BaseUserFilterCriteria $baseUserFilterCriteria
    ) {
        $this->baseUserEntity = $baseUserEntity;
        $this->dbRepository = $dbRepository;
        $this->filters = $baseUserFilterCriteria->data;
        $this->size = $paginationMetta->limit;
        $this->sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $this->params = [];
        $this->commonFilters();
    }

    /**
     * Find a user by email
     * @param string $email
     * @return BaseUserEntity
     * @throws Exception
     */
    public function findByEmail($email)
    {
        $result = $this->dbRepository->fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
        /** @var BaseUserEntity $user */
        $user = $this->hydrate($result);
        if ($user->isEmpty()) {
            // throw new Exception("User not found");
        }
        return $user;
    }

    public function hydrate($data)
    {
        return $this->baseUserEntity->newInstance($data);
    }

    /**
     * Find a user by id
     * @param int $id
     * @return BaseUserEntity
     * @throws Exception
     */
    function find($id)
    {
        $result = $this->dbRepository->fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
        /** @var BaseUserEntity $user */
        $user = $this->hydrate($result);
        if ($user->isEmpty()) {
            throw new Exception("User not found");
        }
        return $user;
    }

    protected function commonFilters()
    {
        $sql = $this->sql;
        $params = $this->params;
        if (count($this->filters) > 0) {

            if (isset($this->filters['id'])) {
                $sql .= " AND id = ?";
                $params[] = $this->filters['id'];
            }

            if (isset($this->filters['email'])) {
                $sql .= " AND email = ?";
                $params[] = $this->filters['email'];
            }
            if (isset($this->filters['phone'])) {
                $sql .= " AND phone = ?";
                $params[] = $this->filters['phone'];
            }
            if (isset($this->filters['status'])) {
                $sql .= " AND status = ?";
                $params[] = $this->filters['status'];
            }
            if (isset($this->filters['role'])) {
                $sql .= " AND role = ?";
                $params[] = $this->filters['role'];
            }
            if (isset($this->filters['created_at'])) {
                $sql .= " AND created_at = ?";
                $params[] = $this->filters['created_at'];
            }
            if (isset($this->filters['updated_at'])) {
                $sql .= " AND updated_at = ?";
                $params[] = $this->filters['updated_at'];
            }
        }

        $this->sql = $sql;
        $this->params = $params;

        // return [$sql, $params];
        return $this;
    }

    function fetchAll()
    {
        $results = $this->dbRepository->fetchAll($this->sql, $this->params);
        return array_map(function ($result) {
            return $this->hydrate($result);
        }, $results);
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
            $this->dbRepository->update("users", $data, ["id" => $id]);
            return $this->find($id);
        } else {
            $id = $this->dbRepository->insert("users", $data);
            return $this->find($id);
        }
    }
}

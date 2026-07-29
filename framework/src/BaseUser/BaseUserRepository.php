<?php

namespace R2Packages\Framework\BaseUser;

use Exception;
use R2Packages\Framework\Container;
use R2Packages\Framework\BaseUser\BaseUserFilterCriteria;
use R2Packages\Framework\BaseUser\BaseUserEntity;
use R2Packages\Framework\Event;
use R2Packages\Framework\Notification\NotificationRepository;
use R2Packages\Framework\Traits\WithEvents;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Repositories\DbRepository;
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
    protected NotificationRepository $notificationRepository;


    function __construct(
        BaseUserEntity $baseUserEntity,
        DbRepository $dbRepository,
        PaginationMetta $paginationMetta,
        Request $request,
        NotificationRepository $notificationRepository
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->baseUserEntity = $baseUserEntity;
        $this->dbRepository = $dbRepository;
        $this->filters = $request->data;
        $this->size = $paginationMetta->limit;
        $this->sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $this->params = [];

        // if(!$this->baseUserEntity->isEmpty()){
        //     $role = $this->baseUserEntity->role;
        //     // if role contains admin, then add admin filter
        //     if(strpos($role, 'admin') !== false){
        //         // do nothing , admin can see all users
        //     }else{
        //         $this->filterById($this->baseUserEntity->id); // only show the user's own data
        //     }
        // }

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

    /**
     * Hydrate a user
     * @param array $data
     * @return BaseUserEntity
     */
    public function hydrate($data)
    {
        $notifications = $this->notificationRepository->filterByUserId($data['id'])->fetch();
        return $this->baseUserEntity->newInstance($notifications, $data);
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
                $this->filterById($this->filters['id']);
            }

            if (isset($this->filters['email'])) {
                $this->filterByEmail($this->filters['email']);
            }
            if (isset($this->filters['phone'])) {
                $this->filterByPhone($this->filters['phone']);
            }
            if (isset($this->filters['status'])) {
                $this->filterByStatus($this->filters['status']);
            }
            if (isset($this->filters['role'])) {
                $this->filterByRole($this->filters['role']);
            }
            if (isset($this->filters['created_at'])) {
                $this->filterByCreatedAt($this->filters['created_at']);
            }
            if (isset($this->filters['updated_at'])) {
                $this->filterByUpdatedAt($this->filters['updated_at']);
            }
        }

        $this->sql = $sql;
        $this->params = $params;

        // return [$sql, $params];
        return $this;
    }

    /**
     * Filter by id
     * @param int $id
     * @return $this
     */
    function filterById($id)
    {
        $this->sql .= " AND id = ?";
        $this->params[] = $id;
        return $this;
    }

    /**
     * Filter by email
     * @param string $email
     * @return $this
     */
    function filterByEmail($email)
    {
        $this->sql .= " AND email = ?";
        $this->params[] = $email;
        return $this;
    }

    /**
     * Filter by phone
     * @param string $phone
     * @return $this
     */
    function filterByPhone($phone)
    {
        $this->sql .= " AND phone = ?";
        $this->params[] = $phone;
        return $this;
    }

    /**
     * Filter by status
     * @param string $status
     * @return $this
     */
    function filterByStatus($status)
    {
        $this->sql .= " AND status = ?";
        $this->params[] = $status;
        return $this;
    }

    /**
     * Filter by role
     * @param string $role
     * @return $this
     */
    function filterByRole($role)
    {
        $this->sql .= " AND role = ?";
        $this->params[] = $role;
        return $this;
    }

    /**
     * Filter by created at
     * @param string $createdAt
     * @return $this
     */
    function filterByCreatedAt($createdAt)
    {
        $this->sql .= " AND created_at >= ?";
        $this->params[] = $createdAt;
        return $this;
    }

    /**
     * Filter by updated at
     * @param string $updatedAt
     * @return $this
     */
    function filterByUpdatedAt($updatedAt)
    {
        $this->sql .= " AND updated_at >= ?";
        $this->params[] = $updatedAt;
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

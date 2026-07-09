<?php

namespace R2Packages\Framework\Notification;


use R2Packages\Framework\Notification\NotificationEntity;
use Exception;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;
use R2Packages\Framework\Utils;

class NotificationRepository
{



    protected DbRepository $dbRepository;
    protected NotificationEntity $notificationEntity;
    protected AuthUserService $authUserService;

    protected $filters = [];
    protected $size = 11;
    protected $sql = '';
    protected $params = [];

    function __construct(
        DbRepository $dbRepository,
        NotificationEntity $notificationEntity,
        PaginationMetta $paginationMeta,
        Request $request,
        AuthUserService $authUserService
    ) {
        $this->dbRepository = $dbRepository;
        $this->notificationEntity = $notificationEntity;
        $this->filters = $request->data;
        $this->size = $paginationMeta->limit;
        $this->sql = 'SELECT * FROM notifications WHERE 1=1';
        $this->authUserService = $authUserService;
        $this->commonFilter();
        
    }

    protected function commonFilter()
    {
        /** @var UserEntity $authUser */
        $authUser = $this->authUserService->getAuthUser();
        
        if (!$authUser->isEmpty()) {
          $this->filters['user_id'] = $authUser->id;
        }

        if (isset($this->filters['user_id'])) {
            $this->sql .= ' AND user_id = ?';
            $this->params[] = $this->filters['user_id'];
        }
        if (isset($this->filters['read_status'])) {
            $this->sql .= ' AND read_status = ?';
            $this->params[] = $this->filters['read_status'];
        }
        // read status filter
        if (isset($this->filters['read'])) {
            $this->sql .= ' AND read_status = ?';
            $this->params[] = 'read';
        }
        // unread status filter
        if (isset($this->filters['unread'])) {
            $this->sql .= ' AND read_status = ?';
            $this->params[] = 'unread';
        }
    }

    function find($id)
    {
        $notification = dbFetchOne('SELECT * FROM notifications WHERE id = ?', [$id]);
        return $this->hydrate($notification);
    }

    private function hydrate($notification)
    {
        $notification = $this->notificationEntity->newInstance($notification);
        return $notification;
    }

    function fetchAll()
    {
        $notifications = $this->dbRepository->fetchAll($this->sql, $this->params);
        return array_map([$this, 'hydrate'], $notifications);
    }

    function fetch()
    {
        $notifications = $this->dbRepository->paginate($this->sql, $this->size, $this->params);
        return array_map([$this, 'hydrate'], $notifications);
    }

    function count()
    {
        return $this->dbRepository->count($this->sql, $this->params);
    }

    function save($id, $data)
    {
        if ($id > 0) {
            $this->dbRepository->update('notifications', $data, ['id' => $id]);
        } else {
            $id = $this->dbRepository->insert('notifications', $data);
        }
        return $this->find($id);
    }
}

<?php 
namespace R2Packages\Framework\v2\Notification;

use R2Packages\Framework\v2\Domain\AbstractRepository;

class NotificationRepository extends AbstractRepository{

    protected $table = 'notifications';

    protected $primaryKey = 'id';

    public function filter($data){
        $sql = "";
        $params = [];

        return $this->newQuery($sql, $params);
    }

    protected function hydrate($row){
        return new NotificationEntity($row);
    }

}
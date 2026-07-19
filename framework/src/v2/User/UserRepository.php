<?php 

namespace R2Packages\Framework\v2\User;

use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\v2\Interfaces\RepositoryInterface;

class UserRepository implements RepositoryInterface
{
    private DbRepository $dbRepository;
    private UserEntity $userEntity;

    protected $table = 'users';
    protected $sql = "SELECT * FROM users";

    protected $size = 10;
    protected $params = [];

    public function __construct(DbRepository $dbRepository,UserEntity $userEntity)
    {
        $this->dbRepository = $dbRepository;
        $this->userEntity = $userEntity;
    }

    public function filter()
    {
        
    }

    public function fetch()
    {
        $rows = $this->dbRepository->paginate($this->sql,$this->size,$this->params);
        return array_map([$this,'hydrate'],$rows);
    }

    public function fetchBy($field,$value){
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = ?";
        $row = $this->dbRepository->fetchOne($sql,$value);
        return $this->hydrate($row);
    }

    public function fetchAll(){
        $rows = $this->dbRepository->fetchAll($this->sql,$this->params);
        return array_map([$this,'hydrate'],$rows);
    }

    public function count(){
        return $this->dbRepository->count($this->sql,$this->params);
    }

    public function sum($field){
        return $this->dbRepository->sum($this->sql,$field,$this->params);
    }

    public function find($id){
        $row = $this->dbRepository->fetchOne("SELECT * FROM {$this->table} WHERE id = ?",[$id]);
        return $this->hydrate($row);
    }

    private function hydrate($row){
        $this->userEntity->newInstance([],$row);
    }

    public function save($id,$data){
        if ($id > 0){
            $this->dbRepository->update($this->table,$data,['id' => $id]);
        }else{
            $id = $this->dbRepository->insert($this->table,$data);
        }
        return $this->find($id);
    }


    public function delete($id){
        return $this->dbRepository->delete($this->table,['id' => $id]);
    }
}
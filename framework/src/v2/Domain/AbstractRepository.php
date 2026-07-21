<?php 

namespace R2Packages\Framework\v2\Domain;

use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\v2\Interfaces\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    protected $table = '';
    protected $primaryKey = 'id';
    protected $params = [];
    protected $sql = "";
    // protected $sql_join = '';
    protected $size = 11;

    private DbRepository $dbRepository;


    public function __construct(DbRepository $dbRepository)
    {
        $this->dbRepository = $dbRepository;

        if (empty($this->sql)){
            $this->sql = "SELECT * FROM " . $this->table . " WHERE 1 = 1";
        }
        // if (!empty($this->sql_join)){
        //     $this->sql_join =  $this->sql; // copy the sql to the sql_join
        // }
    }


    /**
     * Filter a record
     * @param array $data
     * @return self
     */
    abstract public function filter($data);

    /**
     * Hydrate a row
     * @param array $row
     * @return mixed
     */
    abstract protected function hydrate($row);

    /**
     * Fetch a single record
     * @return mixed
     */
    public function fetchOne(){
        $row = $this->dbRepository->fetchOne($this->sql, $this->params);
        return $this->hydrate($row);
    }
    
    public function fetch(){
        $rows = $this->dbRepository->paginate($this->sql, $this->size, $this->params);
        return array_map([$this, 'hydrate'], $rows);
    }

    /**
     * Fetch a record by a field and value
     * @param string $field
     * @param string $value
     * @return mixed
     */
    public function fetchBy($field,$value){
        return $this->newQuery(" AND " . $field . " = ?", [$value]);
    }

    public function fetchAll(){
        $rows = $this->dbRepository->paginate($this->sql, $this->size, $this->params);
        return array_map([$this, 'hydrate'], $rows);
    }

    public function count(){
        return $this->dbRepository->count($this->sql, $this->params);
    }
    /**
     * Sum a field
     * @param string $field
     * @return int
     */
    public function sum($field){        
        return $this->dbRepository->sum($this->sql,$field, $this->params);
    }
    /**
     * Find a record
     * @param int $id
     * @return mixed
     */
    public function find($id){
        $this->sql.= " AND " . $this->primaryKey . " = ?";
        $this->params[] = $id;

        return $this->newQuery(" AND " . $this->primaryKey . " = ?", [$id]);        
    }

    /**
     * Create a new query
     * @param string $sql
     * @param array $params
     * @return self
     */
    protected function newQuery($sql,$params){
        $query = new static($this->dbRepository);
        $query->setSql($this->sql . $sql);
        $query->setParams($this->params + $params);

        return $query;
    }

    /**
     * Save a record
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function save($id,$data){
        if (empty($id)){
            $id = $this->dbRepository->insert($this->table, $data);
            return $this->find($id);
        } else {
            $this->dbRepository->update($this->table, $data, [
                $this->primaryKey => $id
            ]);
            return $this->find($id);
        }
    }

    /**
     * Delete a record
     * @param int $id
     * @return bool
     */
    public function delete($id){
        return $this->dbRepository->delete($this->table, [
            $this->primaryKey => $id
        ]);
    }

    /**
     * Set the SQL query
     * @param string $sql
     * @return self
     */
    public function setSql($sql){
        $this->sql = $sql;
        return $this;
    }

    /**
     * Set the parameters for the SQL query
     * @param array $params
     * @return self
     */
    public function setParams($params){
        $this->params = $params;
        return $this;
    }

}
<?php 
namespace App\Infrastructure\Framework\Db;

class QueryBuilderService implements QueryBuilderServiceInterface
{
    private $sql = '';
    private $params = [];
    private $size = 11;

    public function setSql(string $sql){
      $this->sql = $sql;
      return $this;
    }

    public function setParams(array $params){
      $this->params = $params;
      return $this;
    }

    public function getSql(){
      return $this->sql;
    }

    public function getParams(){
      return $this->params;
    }

    function appendSql(string $sql){
      $this->sql .= $sql;
      return $this;
    }

    function appendParams(array $params){
      $this->params = array_merge($this->params, $params);
      return $this;
    }

    function setSize(int $size){
      $this->size = $size;
      return $this;
    }

    function getSize(){
      return $this->size;
    }
    
}
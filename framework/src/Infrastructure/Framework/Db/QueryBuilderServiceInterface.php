<?php 
namespace App\Infrastructure\Framework\Db;

interface QueryBuilderServiceInterface
{
    public function setSql(string $sql);
    public function setParams(array $params);
    public function getSql();
    public function getParams();
    function appendSql(string $sql);
    function appendParams(array $params);
    function setSize(int $size);
    function getSize();
}
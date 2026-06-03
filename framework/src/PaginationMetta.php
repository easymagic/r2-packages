<?php 

namespace R2Packages\Framework;

class PaginationMetta
{
    public $total = 0;
    public $page = 1;
    public $limit = 10;
    public $totalPages = 0;


    public function __construct($config = [])
    {
        $this->total = $config['total'] ?? 0;
        $this->page = $config['page'] ?? 1;
        $this->limit = $config['limit'] ?? 10;
        $this->totalPages = $config['totalPages'] ?? ceil($this->total / $this->limit);
    }
}
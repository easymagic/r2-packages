<?php 

namespace R2Packages\Framework\Criteria;

use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Request;

class BaseUserFilterCriteria extends Request
{

    protected BaseUserEntity $baseUserEntity;

    /**
     * @param BaseUserEntity $baseUserEntity
     * @param array $data
     */
    function __construct(BaseUserEntity $baseUserEntity, $data)
    {
        parent::__construct($data);
        $this->baseUserEntity = $baseUserEntity;
        $this->loadFilters();
    }

    function loadFilters(){
        if(!$this->baseUserEntity->isEmpty()){
            $role = $this->baseUserEntity->role;
            // if role contains admin, then add admin filter
            if(strpos($role, 'admin') !== false){
                // do nothing , admin can see all users
            }else{
                $this->data['id'] = $this->baseUserEntity->id; // only show the user's own data
            }
        }
    }
}
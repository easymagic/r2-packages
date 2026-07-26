<?php 
namespace R2Packages\Framework\Application\Validation;

interface ValidationServiceInterface
{
    /**
     * Validate the data against the rules
     * @param array $data
     * @param array $rules array of arrays, each containing a field name and an array of ValidationRuleInterface
     * @return array
     */
    public function validate(array $data, array $rules);
}
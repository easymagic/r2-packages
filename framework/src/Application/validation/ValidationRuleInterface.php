<?php 
namespace R2Packages\Framework\Application\Validation;

interface ValidationRuleInterface
{
    public function validate(string $value);
    public function getErrorMessage();
    public function getDefaultValue();
}
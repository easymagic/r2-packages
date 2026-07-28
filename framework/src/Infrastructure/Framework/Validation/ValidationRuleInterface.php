<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Validation;

interface ValidationRuleInterface
{
    public function validate(string $value);
    public function getErrorMessage();
    public function getDefaultValue();
}
<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Validation\Rules;

use R2Packages\Framework\Infrastructure\Framework\Validation\ValidationRuleInterface;

class OptionalRule implements ValidationRuleInterface
{

    private string $fieldLabel;
    private string $defaultValue;

    public function __construct(string $fieldLabel, $defaultValue = null)
    {
        $this->fieldLabel = $fieldLabel;
        $this->defaultValue = $defaultValue;
    }

    public function validate(string $value)
    {
        if (!isset($value) || empty($value)) {
            // do nothing
        }
    }

    public function getErrorMessage()
    {
        return "";
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Validation\Rules;

use R2Packages\Framework\Infrastructure\Framework\Exceptions\AppException;
use R2Packages\Framework\Infrastructure\Framework\Validation\ValidationRuleInterface;

class NumberRule implements ValidationRuleInterface
{

    private string $fieldLabel;
    public function __construct(string $fieldLabel)
    {
        $this->fieldLabel = $fieldLabel;
    }

    public function validate(string $value)
    {
        if (!is_numeric($value)) {
            throw AppException::create(($this->getErrorMessage()));
        }
    }

    public function getErrorMessage()
    {
        return "Field $this->fieldLabel is not a valid number";
    }

    public function getDefaultValue()
    {
        return null;
    }
}
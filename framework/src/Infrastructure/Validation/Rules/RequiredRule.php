<?php 
namespace R2Packages\Framework\Infrastructure\Validation\Rules;

use R2Packages\Framework\Application\Exceptions\AppException;
use R2Packages\Framework\Application\Validation\ValidationRuleInterface;

class RequiredRule implements ValidationRuleInterface
{
    private string $fieldLabel;

    public function __construct(string $fieldLabel)
    {
        $this->fieldLabel = $fieldLabel;
    }

    public function validate(string $value)
    {
        if ( !isset($value) || empty($value)) {
            throw AppException::create(($this->getErrorMessage()));
        }
    }

    public function getErrorMessage()
    {
        return "Field $this->fieldLabel is required";
    }

    public function getDefaultValue()
    {
        return null;
    }
}
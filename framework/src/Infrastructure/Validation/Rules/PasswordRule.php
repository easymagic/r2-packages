<?php 
namespace R2Packages\Framework\Infrastructure\Validation\Rules;

use R2Packages\Framework\Application\Exceptions\AppException;
use R2Packages\Framework\Application\Validation\ValidationRuleInterface;

class PasswordRule implements ValidationRuleInterface
{

    private string $fieldLabel;
    private int $minLength;
    private int $maxLength;

    public function __construct(string $fieldLabel, int $minLength = 8, int $maxLength = 100)
    {
        $this->fieldLabel = $fieldLabel;
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    public function validate(string $value)
    {
        if (strlen($value) < $this->minLength || strlen($value) > $this->maxLength) {
            throw AppException::create(($this->getErrorMessage()));
        }
    }

    public function getErrorMessage()
    {
        return "Field $this->fieldLabel must be between $this->minLength and $this->maxLength characters";
    }

    public function getDefaultValue()
    {
        return null;
    }
}
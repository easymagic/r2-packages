<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Validation\Rules;

use R2Packages\Framework\Infrastructure\Framework\Exceptions\AppException;
use R2Packages\Framework\Infrastructure\Framework\Validation\ValidationRuleInterface;

class MaxRule implements ValidationRuleInterface
{

    private string $fieldLabel;
    private int $max;
    public function __construct(string $fieldLabel, int $max)
    {
        $this->fieldLabel = $fieldLabel;
        $this->max = $max;
    }

    public function validate(string $value)
    {
        if ((int) $value > $this->max) {
            throw AppException::create(($this->getErrorMessage()));
        }
    }

    public function getErrorMessage()
    {
        return "Field $this->fieldLabel must be less than or equal to $this->max";
    }

    public function getDefaultValue()
    {
        return null;
    }
}
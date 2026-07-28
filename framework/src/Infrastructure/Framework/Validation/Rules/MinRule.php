<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Validation\Rules;

use R2Packages\Framework\Infrastructure\Framework\Exceptions\AppException;
use R2Packages\Framework\Infrastructure\Framework\Validation\ValidationRuleInterface;

class MinRule implements ValidationRuleInterface
{

    private string $fieldLabel;
    private int $min;
    public function __construct(string $fieldLabel, int $min)
    {
        $this->fieldLabel = $fieldLabel;
        $this->min = $min;
    }

    public function validate(string $value)
    {
        if ((int) $value < $this->min) {
            throw AppException::create(($this->getErrorMessage()));
        }
    }

    public function getErrorMessage()
    {
        return "Field $this->fieldLabel must be greater than or equal to $this->min";
    }

    public function getDefaultValue()
    {
        return null;
    }
}
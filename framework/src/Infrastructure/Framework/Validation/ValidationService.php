<?php

namespace R2Packages\Framework\Infrastructure\Framework\Validation;

use R2Packages\Framework\Infrastructure\Framework\Exceptions\AppException;
use R2Packages\Framework\Infrastructure\Framework\Validation\ValidationServiceInterface;

class ValidationService implements ValidationServiceInterface
{
    public function validate(array $data, array $rules)
    {
        $input = [];
        foreach ($rules as $field => $ruleSet) {
            foreach ($ruleSet as $rule) {
                if ($rule instanceof ValidationRuleInterface) {
                    if (!isset($data[$field])) {
                        throw AppException::create("Field $field is required");
                    }
                    $rule->validate($data[$field]);
                } else {
                    throw AppException::create("Invalid rule for field $field");
                }
                $input[$field] = isset($data[$field]) ? $data[$field] : $rule->getDefaultValue();
            }
        }
        return $input;
    }
}

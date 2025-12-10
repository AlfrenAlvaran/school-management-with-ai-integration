<?php

namespace Core\Validation;

class Validator
{
    protected array $data;
    protected array $rules;
    protected array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field  => $rules) {
            foreach (explode('|', $rules) as $rule) {
                if ($rule === 'required' && empty($this->data[$field])) {
                    $this->errors[$field][] = "$field is required.";
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = explode(':', $rule)[1];
                    if (strlen($this->data[$field] ?? '') < $min) {
                        $this->errors[$field][] = "$field must be at least $min characters.";
                    }
                }

                if ($rule === 'email' && !filter_var($this->data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "Invalid email format.";
                }
            }
        }
        return empty($this->errors);
    }


    public function errors(): array
    {
        return $this->errors;
    }
}

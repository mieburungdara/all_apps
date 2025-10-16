<?php

class Validator {

    private $errors = [];

    public function validate($data, $rules) {
        foreach ($rules as $field => $rule_string) {
            $rules_array = explode('|', $rule_string);
            $value = $data[$field] ?? null;

            foreach ($rules_array as $rule) {
                $this->apply_rule($field, $value, $rule);
            }
        }
        return empty($this->errors);
    }

    private function apply_rule($field, $value, $rule) {
        $param = null;
        if (strpos($rule, '[') !== false) {
            preg_match('/(.*?)\[(.*?)\]/', $rule, $matches);
            $rule = $matches[1];
            $param = $matches[2];
        }

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->add_error($field, "The {$field} field is required.");
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->add_error($field, "The {$field} field must be a valid email address.");
                }
                break;
            case 'min_length':
                if (strlen($value) < $param) {
                    $this->add_error($field, "The {$field} field must be at least {$param} characters long.");
                }
                break;
            case 'max_length':
                if (strlen($value) > $param) {
                    $this->add_error($field, "The {$field} field cannot exceed {$param} characters.");
                }
                break;
        }
    }

    private function add_error($field, $message) {
        $this->errors[$field][] = $message;
    }

    public function get_errors() {
        return $this->errors;
    }
}

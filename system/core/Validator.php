<?php

class Validator {

    private $errors = [];
    private $db;
    private $lang;

    public function __construct() {
        require_once SYSPATH . 'core/Language.php';
        $this->lang = Language::getInstance();
    }

    public function validate($data, $rules) {
        foreach ($rules as $field => $rule_string) {
            $rules_array = explode('|', $rule_string);
            $value = $data[$field] ?? null;

            foreach ($rules_array as $rule) {
                $this->apply_rule($field, $value, $rule, $data);
            }
        }
        return empty($this->errors);
    }

    private function apply_rule($field, $value, $rule, $data) {
        $param = null;
        if (strpos($rule, '[') !== false) {
            preg_match('/(.*?)\[(.*?)\]/', $rule, $matches);
            $rule_name = $matches[1];
            $param = $matches[2];
        } else {
            $rule_name = $rule;
        }

        $message = str_replace(['{field}', '{param}'], [$field, $param], $this->lang->line($rule_name));

        switch ($rule_name) {
            case 'required':
                if (empty($value)) {
                    $this->add_error($field, $message);
                }
                break;
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->add_error($field, $message);
                }
                break;
            case 'min_length':
                if (!empty($value) && strlen($value) < $param) {
                    $this->add_error($field, $message);
                }
                break;
            case 'max_length':
                if (!empty($value) && strlen($value) > $param) {
                    $this->add_error($field, $message);
                }
                break;
            case 'matches':
                if ($value !== ($data[$param] ?? null)) {
                    $this->add_error($field, $message);
                }
                break;
            case 'is_unique':
                $this->load_db();
                $params = explode('.', $param);
                $table = $params[0];
                $column = $params[1];
                $ignore_col = $params[2] ?? null;
                $ignore_val = $params[3] ?? null;

                $where = [$column => $value];
                if ($ignore_col && $ignore_val) {
                    // This is a hacky way to add a NOT EQUAL condition.
                    // We should improve the base Model's get() method later.
                    // For now, this will work by manually adding to the where clause.
                    // This is not implemented yet, we will do it when we build the profile update page.
                }

                $result = $this->db->get($table, $where);

                if (!empty($result)) {
                    // If we are in an update case, we need to check if the found record is the one we are editing
                    if ($ignore_val && $result[0][$ignore_col] == $ignore_val) {
                        // It's the same record, so it's not a duplicate
                    } else {
                        $this->add_error($field, $message);
                    }
                }
                break;
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->add_error($field, $message);
                }
                break;
            case 'alpha':
                if (!empty($value) && !ctype_alpha($value)) {
                    $this->add_error($field, $message);
                }
                break;
            case 'alpha_space':
                if (!empty($value) && !preg_match('/^[A-Z ]+$/i', $value)) {
                    $this->add_error($field, $message);
                }
                break;
            case 'alpha_numeric':
                if (!empty($value) && !ctype_alnum($value)) {
                    $this->add_error($field, $message);
                }
                break;
            case 'valid_date':
                if (!empty($value)) {
                    $d = DateTime::createFromFormat('Y-m-d', $value);
                    if (!($d && $d->format('Y-m-d') === $value)) {
                        $this->add_error($field, $message);
                    }
                }
                break;
            case 'is_natural':
                if (!empty($value) && !preg_match('/^[0-9]+$/', $value)) {
                    $this->add_error($field, $message);
                }
                break;
        }
    }

    private function load_db() {
        if (!$this->db) {
            require_once SYSPATH . 'core/Model.php';
            $this->db = new Model();
        }
    }

    private function add_error($field, $message) {
        $this->errors[$field][] = $message;
    }

    public function get_errors() {
        return $this->errors;
    }
}

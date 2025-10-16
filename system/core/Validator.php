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
            case 'unique':
                $this->load_db();
                list($table, $column) = explode('.', $param);
                $result = $this->db->get($table, [$column => $value]);
                if (!empty($result)) {
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

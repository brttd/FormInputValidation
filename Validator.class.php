<?php

class Validator {
    public function __call($method, $args)
    {
        if ($this->methodExists($method)) {
            return call_user_func_array($this->$method, $args);
        } {
            throw new Error("{$method} is not a defined validation rule!");
        }
    }

    public function methodExists($method) {
        return true;

        return isset($this->$method) && is_callable($this->$method);
    }

    public function passesRule($value, $rule, $params = null) {
        if ($this->methodExists($rule)) {
            return $this->$rule($value, $params);
        } else {
            return false;
        }
    }

    public function ruleError($value, $rule, $params = null) {
        if ($this->passesRule($value, $rule, $params)) {
            return false;
        }

        $error_method = $rule . "_error";

        if ($this->methodExists($error_method)) {
            return $this->$error_method($value, $params);
        }

        return "did not pass {$rule} rule!";
    }

    public function addRule($rule_name, $rule_method) {
        //TODO
    }

    //=================================================================
    //Validation Rules
    //=================================================================

    //input is truthy
    protected function required($value, $param = null) {
        if ($value) {
            return true;
        }

        return false;
    }
    protected function required_error($value, $param = null) {

        return "is required";
    }

    //input equals given value
    protected function is($value, $param) {
        return $value == $param;
    }
    protected function is_error($value, $param) {
        return "must be {$param}";
    }

    //input string is longer than given length
    protected function min_length($value, $param = 0) {
        if (is_string($value)) {
            return strlen($value) >= $param;
        }

        return false;
    }
    protected function min_length_error($value, $param = 0) {
        return "must have {$param} characters or more";
    }
    //input string is shorter than given length
    protected function max_length($value, $param = 1) {
        if (is_string($value)) {
            return strlen($value) <= $param;
        }

        return false;
    }
    protected function max_length_error($value, $param = 0) {
        return "must have {$param} characters or less";
    }

    //input numeric is greater than given amount
    protected function min($value, $param = 0) {
        if (is_numeric($value)) {
            return strlen($value) >= $param;
        }

        return false;
    }
    protected function min_error($value, $param = 0) {
        return "must be {$param} or greater";
    }
    //input numeric is less than given amount
    protected function max($value, $param = 1) {
        if (is_numeric($value)) {
            return strlen($value) <= $param;
        }

        return false;
    }
    protected function max_error($value, $param = 0) {
        return "must be {$param} or smaller";
    }

    //input string contains given string(s)
    protected function contains($value, $param) {
        if (strpos($value, $param) !== false) {
            return true;
        }

        return false;
    }
    protected function contains_error($value, $param) {
        return "must contain {$param}";
    }
    

    //input is present in given list
    protected function in_list($value, $param = []) {
        if (is_string($param)) {
            return in_array($value, explode(",", $param));
        }

        return in_array($value, $param);
    }
    protected function in_list_error($value, $param) {
        if (is_array($param)) {
            $param = implode(", ", $param);
        }

        return "must be one of {$param}";
    }

    //input is not present in given list
    protected function not_in_list($value, $param = []) {
        if (is_string($param)) {
            return !in_array($value, explode(",", $param));
        }

        return !in_array($value, $param);
    }
    protected function not_in_list_error($value, $param) {
        if (is_array($param)) {
            $param = implode(", ", $param);
        }
        
        return "cannot be one of {$param}";
    }

    protected function email($value, $param = null) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    protected function email_error($value, $param = null) {
        return "must be a valid email address";
    }
}
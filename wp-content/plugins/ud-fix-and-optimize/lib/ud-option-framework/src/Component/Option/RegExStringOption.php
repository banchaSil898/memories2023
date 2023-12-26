<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Option;

use UDFixAndOptimize\UDOptionFramework\Component\Exception\InvalidOptionValueException;

class RegExStringOption extends StringOption
{
    protected $regex;

    public function __construct($id, $group_name, $default_value, $regex = '.*', $length_limit = 100)
    {
        parent::__construct($id, $group_name, $default_value, $length_limit);

        $this->regex = $regex;
    }

    public function validate($value)
    {
        $value = parent::validate($value);

        if (! preg_match($this->regex, $value)) {
            throw new InvalidOptionValueException("Invalid Input", 'udof_invalid_format_input');
        }

        return $value;
    }
}

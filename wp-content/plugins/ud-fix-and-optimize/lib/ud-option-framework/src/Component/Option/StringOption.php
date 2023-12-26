<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Option;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractOption;
use UDFixAndOptimize\UDOptionFramework\Component\Exception\InvalidOptionValueException;

class StringOption extends AbstractOption
{

    protected $length_limit;

    public function __construct($id, $group_name, $default_value, $length_limit = 100)
    {
        parent::__construct($id, $group_name, $default_value);

        $this->length_limit = $length_limit;
    }

    public function validate($value)
    {
        if ($this->length_limit >= 0 and strlen($value) > $this->length_limit) {
            throw new InvalidOptionValueException("length exceed " . $this->length_limit, 'udof_length_limit_excceeded');
        }

        return $value;
    }
}

<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Option;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractOption;
use UDFixAndOptimize\UDOptionFramework\Component\Exception\InvalidOptionValueException;

class ArrayOption extends AbstractOption
{

    public function __construct($id, $group_name, $default_value)
    {
        parent::__construct($id, $group_name, $default_value);
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws InvalidOptionValueException
     */
    public function validate($value)
    {
        if (isset($value) && ! is_array($value)) {
            throw new InvalidOptionValueException("Not array input", 'udof_invalid_format_input');
        }

        $value = array_filter($value);

        return $value;
    }
}

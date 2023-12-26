<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Option;

use UDPostViewCounter\UDOptionFramework\Component\Exception\InvalidOptionValueException;

class DigitOption extends RegExStringOption
{

    protected $length_limit;

    public function __construct($id, $group_name, $default_value, $length_limit = 0)
    {
        parent::__construct($id, $group_name, $default_value, '/^[0-9]*$/', $length_limit);
    }

    public function validate($value)
    {
        try {
            $value = parent::validate($value);
        } catch (InvalidOptionValueException $e) {
            if ('udof_invalid_format_input' === $e->getCode()) {
                throw new InvalidOptionValueException('Not Digit Input', $e->getCode());
            }
        }

        return $value;
    }
}

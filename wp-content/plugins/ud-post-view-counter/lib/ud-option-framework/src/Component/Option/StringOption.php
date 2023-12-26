<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Option;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractOption;
use UDPostViewCounter\UDOptionFramework\Component\Exception\InvalidOptionValueException;

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
        if (0 !== $this->length_limit and strlen($value) > $this->length_limit) {
            throw new InvalidOptionValueException("length exceed " . $this->length_limit, 'udof_length_limit_excceeded');
        }

        return $value;
    }
}

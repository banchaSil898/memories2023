<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Option;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractOption;

class BooleanOption extends AbstractOption
{
    public function __construct($id, $group_name, $default_value)
    {
        parent::__construct($id, $group_name, $default_value);
    }

    public function validate($value)
    {
        $value = (bool)$value;

        return $value;
    }
}

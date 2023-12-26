<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Option;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractOption;
use UDPostViewCounter\UDOptionFramework\Component\Exception\InvalidOptionValueException;

class IntegerOption extends DigitOption
{
    protected $min;
    protected $max;

    public function __construct($id, $group_name, $default_value, $min = null, $max = null)
    {
        parent::__construct($id, $group_name, $default_value);

        $this->min = $min;
        $this->max = $max;
    }

    public function validate($value)
    {
        $value = parent::validate($value);

        $value = intval($value);
        if ((! is_null($this->min) and $value < $this->min) or (! is_null($this->max) and $value > $this->max)) {
            throw new InvalidOptionValueException("Input out of range (" . $this->min . ", " . $this->max . ").", 'udof_out_of_range_value');
        }

        return $value;
    }
}

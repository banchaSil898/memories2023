<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Option;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractOption;
use UDPostViewCounter\UDOptionFramework\Component\Exception\InvalidOptionValueException;

class ChoiceOption extends AbstractOption
{

    protected $choices;

    public function __construct($id, $group_name, $default_value, $choices)
    {
        parent::__construct($id, $group_name, $default_value);

        $this->choices = $choices;
    }

    public function validate($value)
    {
        if (! in_array($value, $this->choices)) {
            throw new InvalidOptionValueException('Unknown Input', 'udof_unknown_input');
        }

        return $value;
    }
}

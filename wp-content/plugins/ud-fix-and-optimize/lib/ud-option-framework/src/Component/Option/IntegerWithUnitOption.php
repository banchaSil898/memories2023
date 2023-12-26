<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Option;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractOption;
use UDFixAndOptimize\UDOptionFramework\Component\Exception\InvalidOptionValueException;

class IntegerWithUnitOption extends AbstractOption
{
    protected $unit_options;

    public function __construct($id, $group_name, $default_value, $unit_options)
    {
        parent::__construct($id, $group_name, $default_value);

        $this->unit_options = $unit_options;
    }

    public function getValue()
    {
        $value = parent::getValue();
        $value['value'] = isset($value['value']) ? $value['value'] : 0;
        $value['unit'] = isset($value['unit']) ? $value['unit'] : array_keys($this->unit_options)[0];
        $value['real_value'] = $value['value'] * $this->unit_options[$value['unit']];

        return $value;
    }

    public function getCalculatedValue()
    {
        $value = parent::getCalculatedValue();
        return $value['real_value'];
    }

    public function validate($value)
    {
        if (isset($value['value']) and ! preg_match('/^[0-9]*$/', $value['value'])) {
            throw new InvalidOptionValueException('Not Digit Input', 'udof_invalid_format_input');
        }

        if (is_array($this->unit_options) and isset($value['unit']) and ! in_array($value['unit'], array_keys($this->unit_options))) {
            throw new InvalidOptionValueException('Invalid Unit', 'udof_unknown_input');
        }

        $value['value'] = intval($value['value']);
        unset($value['real_value']);

        return $value;
    }
}

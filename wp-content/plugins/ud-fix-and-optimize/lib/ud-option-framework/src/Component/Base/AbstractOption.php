<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Base;

use UDFixAndOptimize\UDOptionFramework\Component\Exception\InvalidOptionValueException;

if (! defined('ABSPATH')) {
    exit;
}

abstract class AbstractOption
{
    //    const SUPPORTED_FIELDS = array();

    protected $id;
    protected $default_value;
    protected $group_name;

    protected $custom_error_texts;

    /**
     * Option constructor.
     * @param $id
     * @param $group_name
     * @param $default_value
     * @param $custom_error_texts
     */
    public function __construct($id, $group_name, $default_value, $custom_error_texts = array())
    {
        $this->id = $id;
        $this->group_name = $group_name;
        $this->default_value = $default_value;
        $this->custom_error_texts = $custom_error_texts;
    }

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->id;
    }

    public function getGroupName()
    {
        return $this->group_name;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->default_value;
    }

    public function getValue()
    {
        if (empty($this->group_name)) {
            $value = get_option($this->id, $this->default_value);
        } else {
            $value = get_option($this->group_name);
            $value = isset($value[$this->id]) ? $value[$this->id] : $this->default_value;
        }

        return $value;
    }

    public function getCalculatedValue()
    {
        return $this->getValue();
    }

    //    public static function getSupportedFields(){
    //        return static::SUPPORTED_FIELDS;
    //    }


    public function sanitize($value)
    {
        try {
            $this->validate($value);
        } catch (InvalidOptionValueException $e) {
            return null;
        }

        return $value;
    }

    /**
     * validate function
     *
     * @param mixed $value
     * @return mixed
     * @throws InvalidOptionValueException
     */
    abstract public function validate($value);

}

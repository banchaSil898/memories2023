<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Field;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractField;

if (! defined('ABSPATH')) {
    exit;
}


class CheckBoxField extends AbstractField
{

    public function __construct($id, $option_group_name, $parent, $title, $description)
    {
        parent::__construct($id, $option_group_name, $parent, $title, $description);
    }

    public function renderInputTag($id, $name, $value, $aria_description_id)
    {
        echo '<input type="hidden" name="' . $name . '" id="' . $id . '-hidden"  value="0">';
        echo '<input type="checkbox" name="' . $name . '" id="' . $id . '" aria-descriptionby="' . $aria_description_id . '" value="1" ' . checked(true, $value, false) . '/>';
    }

    public function validateInput($value)
    {
        return $value;
    }
}

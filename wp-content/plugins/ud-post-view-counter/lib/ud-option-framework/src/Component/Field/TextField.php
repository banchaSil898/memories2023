<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Field;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractField;

if (! defined('ABSPATH')) {
    exit;
}


class TextField extends AbstractField
{

    public function __construct($id, $option_group_name, $parent, $title, $description)
    {
        parent::__construct($id, $option_group_name, $parent, $title, $description);
    }

    public function renderInputTag($id, $name, $value, $aria_description_id)
    {
        echo '<input class="regular-text" type="text" name="' . $name . '" id="' . $id . '" aria-descriptionby="' . $aria_description_id . '" value="' . $value . '">';
    }

    public function validateInput($value)
    {
        $value = sanitize_text_field($value);

        return $value;
    }
}

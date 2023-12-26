<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Field;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractField;

if (! defined('ABSPATH')) {
    exit;
}


class TextField extends AbstractField
{

    public function __construct($id, $option_group_name, $parent, $title, $description)
    {
        parent::__construct($id, $option_group_name, $parent, $title, $description);
    }

    public function renderInputTag($id, $name, $value)
    {
        $aria_description_id = $id . '-description';
        $description = $this->description;

        echo '<input class="regular-text" type="text" name="' . $name . '" id="' . $id . '" aria-descriptionby="' . $aria_description_id . '" value="' . $value . '">';
        echo '<p class="description" id="' . $aria_description_id . '">' . $description . '</p>';
    }
}

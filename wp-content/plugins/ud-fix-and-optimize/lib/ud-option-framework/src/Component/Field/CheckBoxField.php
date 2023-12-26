<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Field;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractField;
use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractSection;

if (! defined('ABSPATH')) {
    exit;
}


class CheckBoxField extends AbstractField
{

    /**
     * constructor.
     *
     * @param string          $id
     * @param AbstractSection $parent
     * @param string          $option_group_name
     * @param string          $title
     * @param string          $description
     * @type callable         $callback A callback function that sanitizes the option's value.
     * @type mixed[]          $args     Arguments to pass to callback function
     *                                  }
     *
     * @throws \Exception
     */
    public function __construct($id, $option_group_name, $parent, $title, $description)
    {
        parent::__construct($id, $option_group_name, $parent, $title, $description);
    }

    public function renderInputTag($id, $name, $value)
    {
        $aria_description_id = $id . '-description';
        $description = $this->description;

        echo '<input type="hidden" name="' . $name . '" id="' . $id . '-hidden"  value="0">';
        echo '<input type="checkbox" name="' . $name . '" id="' . $id . '" aria-descriptionby="' . $aria_description_id . '" value="1" ' . checked(true, $value, false) . '/>';
        echo '<p class="description" id="' . $aria_description_id . '">' . $description . '</p>';
    }
}

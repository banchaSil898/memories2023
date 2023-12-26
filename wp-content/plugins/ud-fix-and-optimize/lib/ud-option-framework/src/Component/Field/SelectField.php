<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Field;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractField;
use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractSection;

if (! defined('ABSPATH')) {
    exit;
}


class SelectField extends AbstractField
{
    protected $option_labels;

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
    public function __construct($id, $option_group_name, $parent, $title, $description, $option_labels)
    {
        parent::__construct($id, $option_group_name, $parent, $title, $description);

        $this->option_labels = $option_labels;
    }

    public function renderInputTag($id, $name, $value)
    {
        $aria_description_id = $id . '-description';
        $description = $this->description;

        echo '<select type="checkbox" name="' . $name . '" id="' . $id . '" aria-descriptionby="' . $aria_description_id . '" />';
        if (! empty($this->option_labels)) {
            foreach ($this->option_labels as $val => $label) {
                echo '<option value="' . $val . '" ' . selected($val, $value, false) . '>' . $label . '</option>';
            }
        }
        echo '</select>';
        echo '<p class="description" id="' . $aria_description_id . '">' . $description . '</p>';
    }
}

<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Field;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractField;
use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractSection;

if (! defined('ABSPATH')) {
    exit;
}


class IntegerWithUnitField extends AbstractField
{

    private $unit_labels;

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
    public function __construct($id, $option_group_name, $parent, $title, $description, $unit_labels)
    {
        parent::__construct($id, $option_group_name, $parent, $title, $description);

        $this->unit_labels = $unit_labels;
    }

    public function renderInputTag($id, $name, $value)
    {
        $aria_description_id = $id . '-description';
        $description = $this->description;

        $unit = $value['unit'];
        $int_val = $value['value'];

        echo '<input class="small-text" type="number" name="' . $name . '[value]" id="' . $id . '-value" aria-descriptionby="' . $aria_description_id . '" value="' . $int_val . '" style="vertical-align:middle">';

        if (! empty($this->unit_labels) && is_array($this->unit_labels)) {
            echo '<select type="checkbox" name="' . $name . '[unit]" id="' . $id . '-unit" aria-descriptionby="' . $aria_description_id . '" />';
            if (! empty($this->unit_labels)) {
                foreach ($this->unit_labels as $val => $label) {
                    echo '<option value="' . $val . '" ' . selected($val, $unit, false) . '>' . $label . '</option>';
                }
            }
        }
        echo '</select>';

        echo '<p class="description" id="' . $aria_description_id . '">' . $description . '</p>';
    }
}

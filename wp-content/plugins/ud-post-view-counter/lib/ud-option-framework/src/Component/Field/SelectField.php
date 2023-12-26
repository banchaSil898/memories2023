<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Field;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractField;

if (! defined('ABSPATH')) {
    exit;
}


class SelectField extends AbstractField
{
    protected $option_labels;

    public function __construct($id, $option_group_name, $parent, $title, $description, $option_labels)
    {
        parent::__construct($id, $option_group_name, $parent, $title, $description);

        $this->option_labels = $option_labels;
    }

    public function renderInputTag($id, $name, $value, $aria_description_id)
    {
        echo '<select type="checkbox" name="' . $name . '" id="' . $id . '" aria-descriptionby="' . $aria_description_id . '" />';
        if (! empty($this->option_labels)) {
            foreach ($this->option_labels as $val => $label) {
                echo '<option value="' . $val . '" ' . selected($val, $value, false) . '>' . $label . '</option>';
            }
        }
        echo '</select>';
    }

    public function validateInput($value)
    {
        return $value;
    }
}

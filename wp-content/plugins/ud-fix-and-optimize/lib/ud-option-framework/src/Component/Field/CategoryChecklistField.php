<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Field;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractField;
use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractSection;
use UDFixAndOptimize\UDOptionFramework\Util\CategoryChecklistWalker;

if (! defined('ABSPATH')) {
    exit;
}


class CategoryChecklistField extends AbstractField
{

    protected $rows;

    protected $cols;

    /**
     * constructor.
     *
     * @param string          $id
     * @param AbstractSection $parent
     * @param string          $option_group_name
     * @param string          $title
     * @param string          $description
     *
     * @throws \Exception
     */
    public function __construct($id, $option_group_name, $parent, $title, $description, $rows = 10, $cols = 50)
    {
        parent::__construct($id, $option_group_name, $parent, $title, $description);

        $this->rows = $rows;
        $this->cols = $cols;
    }

    public function renderInputTag($id, $name, $value)
    {
        echo '<ul class="udof-checklist">';
        echo '<input type="hidden" name="' . $name . '[]" id="' . $id . '-hidden" >';
        wp_terms_checklist(0, array(
            'taxonomy'      => 'category',
            'selected_cats' => $value,
            'checked_ontop' => false,
            'echo'          => true,
            'walker'        => new CategoryChecklistWalker($id, $name),
        ));
        echo '</ul>';

        //        echo '<textarea class="udof-textarea" name="' . $name . '" id="' . $id . '" rows="' . $this->rows . '" cols="' . $this->cols . '"></textarea>';
    }
}

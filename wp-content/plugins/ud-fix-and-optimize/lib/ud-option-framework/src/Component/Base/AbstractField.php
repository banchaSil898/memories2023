<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Base;

use UDFixAndOptimize\UDOptionFramework\Component\Exception\InvalidOptionValueException;
use UDFixAndOptimize\UDOptionFramework\Core\OptionManager;

if (! defined('ABSPATH')) {
    exit;
}


abstract class AbstractField extends AbstractComponent
{
    /**
     * @var
     */
    protected $title;

    protected $option;

    /**
     * @var AbstractSection $parent
     */
    protected $parent;


    protected $description;


    /**
     * AbstractField constructor.
     *
     * @param string          $id
     * @param AbstractSection $parent
     * @param string          $option_group_name
     * @param string          $title
     * @param string          $description
     *
     * @throws \Exception
     */
    public function __construct($id, $option_group_name, $parent, $title, $description)
    {
        if (! $parent instanceof AbstractSection) {
            throw new \Exception('Field accept only Section parent');
        }

        $this->option = OptionManager::getOption($id, $option_group_name);
        //
        //        if (!in_array(get_class($this), $option::getSupportedFields())) {
        //            throw new \Exception(array_pop(explode('\\', get_class($this))). ' not supported on '. array_pop(explode('\\', get_class($option))));
        //        }

        $this->title = $title;
        $this->description = $description;


        parent::__construct($id, $parent);
    }

    public function init()
    {
        $option_id = $this->option->getID();
        $option_group_name = $this->option->getGroupName();
        $id = str_replace('_', '-', $option_group_name . '-' . str_replace('_', '-', $option_id));

        $page = $this->getRootComponent();

        add_settings_field(
            $id,
            $this->title,
            array($this, 'render'),
            $page->getID(),
            $this->parent->getID(),
            array(
                'label_for' => $id,
            )
        );
    }

    protected function addComponent($component)
    {
        throw new \Exception('Field not accept any children');
    }

    public function getInputTagID()
    {
        $option_id = $this->option->getID();
        $option_group_name = $this->option->getGroupName();
        $input_tag_id = str_replace('_', '-', $option_id);

        if (! empty($option_group_name)) {
            $input_tag_id = str_replace('_', '-', $option_group_name) . '-' . $input_tag_id;
        }

        return $input_tag_id;
    }


    public function getInputTagName()
    {
        $option_id = $this->option->getID();
        $option_group_name = $this->option->getGroupName();
        $input_tag_name = $option_id;

        if (! empty($option_group_name)) {
            $input_tag_name = $option_group_name . '[' . $option_id . ']';
        }

        return $input_tag_name;
    }

    public function getOption()
    {
        return $this->option;
    }

    public function render()
    {
        $id = $this->getInputTagID();
        $name = $this->getInputTagName();
        $value = $this->option->getValue();

        $this->renderInputTag($id, $name, $value);
    }

    abstract public function renderInputTag($id, $name, $value);
}

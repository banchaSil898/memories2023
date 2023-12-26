<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Base;

use UDPostViewCounter\UDOptionFramework\Component\Exception\InvalidOptionValueException;
use UDPostViewCounter\UDOptionFramework\Core\OptionManager;

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
     * @type callable         $callback A callback function that sanitizes the option's value.
     * @type mixed[]          $args     Arguments to pass to callback function
     *                                  }
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

        $aria_description_id = $id . '-description';
        $description = $this->description;

        $this->renderInputTag($id, $name, $value, $aria_description_id);
        echo '<p class="description" id="' . $aria_description_id . '">' . $description . '</p>';
    }

    abstract public function renderInputTag($id, $name, $value, $aria_description_id);

    abstract public function validateInput($value);


    /**
     * validate function
     *
     * @param mixed $value
     * @return mixed
     * @throws InvalidOptionValueException
     */
    public function validate($value)
    {
        try {
            $value = $this->validateInput($value);
            $value = $this->option->validate($value);
        } catch (InvalidOptionValueException $e) {
            throw new InvalidOptionValueException($this->title . ' - ' . $e->getMessage(), $e->getCode());
        }

        return $value;
    }
}

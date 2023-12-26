<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Base;

if (! defined('ABSPATH')) {
    exit;
}

abstract class AbstractComponent
{
    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var AbstractComponent $parent
     */
    protected $parent;

    /**
     * @var AbstractComponent[] $components
     */
    protected $components;

    protected $root;


    /**
     * AbstractComponent constructor.
     * @param string            $id
     * @param AbstractComponent $parent
     */
    public function __construct($id, $parent)
    {
        $this->id = $id;
        $this->parent = $parent;
        if (! empty($parent)) {
            $this->parent->addComponent($this);
        }

        $current = $this;
        while ($current->parent instanceof AbstractComponent) {
            $current = $current->parent;
        }

        $this->root = $current;

        $this->components = array();
    }


    protected function addComponent($component)
    {
        array_push($this->components, $component);
        if ($this->parent instanceof AbstractComponent) {
            $this->parent->notifyChildAdd($component);
        }
    }

    protected function renderAll()
    {
        foreach ($this->components as $component) {
            $component->render();
        }
    }

    protected function initAll()
    {
        foreach ($this->components as $component) {
            $component->init();
        }
    }

    protected function getRootComponent()
    {
        return $this->root;
    }

    protected function notifyChildAdd($component)
    {
        if ($this->parent instanceof AbstractComponent) {
            $this->parent->notifyChildAdd($component);
        }
    }


    /**
     * @return string
     */
    public function getID()
    {
        return $this->id;
    }

    abstract public function render();

    abstract public function init();
}

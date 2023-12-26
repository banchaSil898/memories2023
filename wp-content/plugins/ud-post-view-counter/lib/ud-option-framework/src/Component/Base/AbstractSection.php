<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Base;

if (! defined('ABSPATH')) {
    exit;
}

abstract class AbstractSection extends AbstractComponent
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $description;

    /**
     * Section constructor.
     * @param string            $id
     * @param AbstractComponent $parent
     * @param string            $title
     * @param string            $description
     */
    public function __construct($id, $parent, $title, $description = '')
    {
        $this->title = $title;
        $this->description = $description;
        parent::__construct($id, $parent);
        $this->slug = $this->getAbsoluteSlug();
    }

    public function init()
    {
        $page = $this->getRootComponent();

        add_settings_section(
            $this->id,
            $this->title,
            array($this, 'renderHeadSection'),
            $page->id
        );
        parent::initAll();
    }

    public function render()
    {
        $page = $this->getRootComponent();

        if (! empty($this->title)) {
            echo "<h2>{$this->title}</h2>\n";
        }

        echo "<p>{$this->description}</p>";

        echo '<table class="form-table">';
        do_settings_fields($page->id, $this->id);
        echo '</table>';

        //        do_settings_sections($this->slug);
        $this->renderAll();
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    private function getAbsoluteSlug()
    {
        $slug = str_replace('_', '-', $this->id);
        $current_obj = $this;
        while (! (empty($current_obj) or $current_obj instanceof AbstractPage)) {
            $slug = str_replace('_', '-', $current_obj->parent->id) . '-' . $slug;
            $current_obj = $current_obj->parent;
        }

        return $slug;
    }

    protected function renderAll()
    {
        foreach ($this->components as $component) {
            if ($component instanceof AbstractSection) {
                $component->render();
            }
        }
    }

    abstract public function renderHeadSection();
}

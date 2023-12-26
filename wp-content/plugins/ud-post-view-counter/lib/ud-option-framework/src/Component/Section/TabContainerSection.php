<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Section;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractSection;

if (! defined('ABSPATH')) {
    exit;
}

class TabContainerSection extends AbstractSection
{
    private $tabs;

    public function __construct($id, $parent)
    {
        parent::__construct($id, $parent, '', '');
    }

    //    public function init() {
    //        $this->initAll();
    //    }

    public function setTabTitle($section, $title)
    {
        if (! in_array($section, $this->components, true)) {
            throw new \Exception('section is not exist');
        }

        $this->tabs[$section->id] = $title;
    }

    protected function addComponent($section)
    {
        if (! $section instanceof Section) {
            throw new \Exception('TabContainerSection accept only Section');
        }

        $this->tabs[$section->id] = $section->title;
        parent::addComponent($section);
    }

    public function render()
    {
        if (empty($this->components)) {
            return;
        }

        echo '<div id ="' . $this->id . '"class="udof-tab-section">';
        echo '<div class="nav-tab-wrapper wp-clearfix hide-if-no-js">';
        foreach ($this->components as $section) {
            $slug = $section->id;
            $label = $this->tabs[$section->id];

            echo '<a href="#' . $slug . '" class="nav-tab" id="' . $slug . '-nav">' . esc_html($label) . '</a>';
        }
        echo '</div>';

        foreach ($this->components as $component) {
            echo '<div id="' . $component->id . '" class="tab-content hide-if-js">';
            $component->render();
            echo '</div>';
        }
        echo '</div>';
    }

    public function renderHeadSection()
    {
        ?>
        <?php
    }
}

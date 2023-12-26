<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Section;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractComponent;
use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractSection;

if (! defined('ABSPATH')) {
    exit;
}

class Section extends AbstractSection
{

    /**
     * Section constructor.
     * @param string            $id
     * @param AbstractComponent $parent
     * @param string            $title
     * @param string            $description
     */
    public function __construct($id, $parent, $title, $description = '')
    {
        parent::__construct($id, $parent, $title, $description);
    }

    public function renderHeadSection()
    {
        ?>
        <?php
    }
}

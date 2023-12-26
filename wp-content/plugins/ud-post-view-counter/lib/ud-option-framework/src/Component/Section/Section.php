<?php

namespace UDPostViewCounter\UDOptionFramework\Component\Section;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractComponent;
use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractSection;

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

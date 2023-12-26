<?php

namespace UDFixAndOptimize\Core;

use UDFixAndOptimize\UDFixAndOptimize;
use UDFixAndOptimize\UDOptionFramework\OptionFramework;

if (! defined('ABSPATH')) {
    exit;
}

class MediaLibraryCategories
{
    public function __construct()
    {
        if (true === OptionFramework::getOptionValue('mlc_disable_post_category_binding', UDFixAndOptimize::OPTION_KEY)) {
            add_filter('wpmediacategory_taxonomy', array($this, 'wpmediacategoryTaxonomyHook'));
        }
    }

    public function wpmediacategoryTaxonomyHook($title)
    {
        return 'ud_media_category';
    }
}

<?php

namespace UDStickyPostManager\Admin;

if (! defined('ABSPATH')) {
    exit;
}

class Admin
{

    /**
     * Admin constructor.
     */
    public function __construct()
    {
        new MetaBox();
        new Ajax();
    }
}

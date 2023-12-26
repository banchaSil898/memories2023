<?php

namespace UDPostViewCounter\Admin;

if (! defined('ABSPATH')) {
    exit;
}

use UDPostViewCounter\UDOptionFramework\Component\Option\StringOption;
use UDPostViewCounter\UDPostViewCounter;
use UDPostViewCounter\UDOptionFramework\OptionFramework;

class Setting
{
    public function __construct()
    {
        OptionFramework::registerOption(
            new StringOption(
                'secret_key',
                UDPostViewCounter::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new StringOption(
                'url',
                UDPostViewCounter::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new StringOption(
                'slug',
                UDPostViewCounter::OPTION_KEY,
                false
            )
        );
    }
}

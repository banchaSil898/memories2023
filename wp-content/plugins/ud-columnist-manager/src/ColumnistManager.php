<?php
namespace UDColumnistManager;

use UDColumnistManager\Admin\ColumnistMetaBox;
use UDColumnistManager\Admin\ColumnistTermMeta;
use UDColumnistManager\Admin\PluginActivator;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Class ColumnistManager
 */
class ColumnistManager
{
    const POST_TYPE_NAME = 'ud_columnist_profile';
    const TAXONOMY_NAME = 'ud_columnist';
    const COLUMNIST_CAT_TAXONOMY_NAME = 'ud_columnist_category';

    const LATEST_POST_PUBLISH_TIME_META_KEY = 'ud_columnist_latest_post_post_time';
    const POST_COUNT_META_KEY = 'ud_columnist_post_count';

    const PROFILE_INFO_META_KEY = 'ud_columnist_profile_info_meta';

    const OPTION_KEY = 'ud_columnist_option';

    public function __construct()
    {
        new ColumnistMetaBox();
        new ColumnistTermMeta();
        new ColumnistPostType();
        new PluginActivator();
    }

}
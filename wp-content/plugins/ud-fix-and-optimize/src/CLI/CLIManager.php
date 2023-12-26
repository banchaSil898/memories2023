<?php

namespace UDFixAndOptimize\CLI;

use UDFixAndOptimize\Admin\Setting;
use UDFixAndOptimize\Core\DuracelltomiGTM;
use UDFixAndOptimize\Core\InstantArticlesForWP;
use UDFixAndOptimize\Core\MediaLibraryCategories;
use UDFixAndOptimize\Core\Nobuna;
use UDFixAndOptimize\Core\PageSpeed;
use UDFixAndOptimize\Core\WPCore;
use UDFixAndOptimize\Core\WPRSSAggregator;
use UDFixAndOptimize\Core\YoastSEO;
use UDFixAndOptimize\Util\ImageEditor;

if (! class_exists('\WP_CLI')) {
    return;
}

/**
 * Unixdev Fix And Optimize Commands
 *
 * @package wp-cli
 */
class CLIManager extends \WP_CLI_Command
{
    //    const CLEAR_OBJECT_CACHE_OPTIMIZE_IMAGE_INTERVAL = 200;

    private $wpcore;
    private $instant_article_for_wp;
    private $wp_rss_aggregator;
    private $yoast_seo;
    private $media_library_categories;
    private $nobuna;
    private $duracelltomi_gtm;
    private $pagespeed;

    public function __construct()
    {
        new Setting();

        $this->wpcore = new WPCore();
        $this->instant_article_for_wp = new InstantArticlesForWP();
        $this->wp_rss_aggregator = new WPRSSAggregator();
        $this->yoast_seo = new YoastSEO();
        $this->media_library_categories = new MediaLibraryCategories();
        $this->nobuna = new Nobuna();
        $this->duracelltomi_gtm = new DuracelltomiGTM();
        $this->pagespeed = new PageSpeed();

        \WP_CLI::add_command('udfao pagespeed', new PageSpeedCommand($this->pagespeed));
        \WP_CLI::add_command('udfao revision', new RevisionCommand());
    }
}

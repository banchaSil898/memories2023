<?php

namespace UDAdsManager\Core;

use UDAdsManager\Core\AdRenderer\AbstractAdRenderer;
use UDAdsManager\Core\AdRenderer\CustomAdRenderer;
use UDAdsManager\Core\AdRenderer\DFPAdRenderer;
use UDAdsManager\Plugin;

class AdsManager implements WPIntegrationInterface
{
    /**
     * @var AbstractAdRenderer[]
     */
    private $renderers;

    /**
     * @var \WP_Post[]
     */
    private $ads;

    private $ad_id_renderers_map;

    private $setting;

    public function __construct($setting)
    {
        $this->renderers = [];
        $this->ads = [];
        $this->ad_id_renderers_map = [];
        $this->setting = $setting;
    }

    public function register()
    {
        add_action('wp_head', [$this, 'renderHeader']);
        add_action('wp_footer', [$this, 'renderFooter']);
        add_shortcode('ud_ad_pos', [$this, 'shortcodeHandler']);
        $this->loadAllEnableAds();
    }

    public function renderHeader()
    {
        foreach ($this->renderers as $renderer) {
            $renderer->renderHead();
        }
    }

    public function renderFooter()
    {
        foreach ($this->renderers as $renderer) {
            $renderer->renderFooter();
        }
    }

    public function shortcodeHandler($atts)
    {
        $buffy = '';

        extract(shortcode_atts([
            'id' => '',
        ], $atts));

        if (empty($id) or empty($this->ads)) {
            return '';
        }

        if(! array_key_exists($id, $this->ad_id_renderers_map)){
            return '';
        }

        $buffy .= $this->ad_id_renderers_map[$id]->getAdItemTag($id);

        return $buffy;
    }

    private function loadAllEnableAds()
    {
        $args = [
            'post_type'      => Plugin::ADS_ITEM_POST_TYPE_NAME,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
        ];

        $this->ads = get_posts($args);

        $ad_items = array_map(function ($item) {
            $result = (array)$item;
            $result[Plugin::AD_INFO_OPTION_KEY] = AdInfo::fromPostID($item->ID);
            return $result;
        }, $this->ads);

        $this->renderers['dfp'] = new DFPAdRenderer($this->setting);
        $this->renderers['custom'] = new CustomAdRenderer($this->setting);

        $dfp_enable = $this->setting->dfp_enable;

        //@todo we only got dfp at this moment, in the future we'll have many type of ads
        foreach ($ad_items as $ad_item) {
            $ad_info = $ad_item[Plugin::AD_INFO_OPTION_KEY];
            if(($ad_info->ad_type === 'dfp' && $dfp_enable) || $ad_info->ad_type !== 'dfp'){
                $this->renderers[$ad_info->ad_type]->addAdItem($ad_item);
                $this->ad_id_renderers_map[$ad_info->name] = $this->renderers[$ad_info->ad_type];
            }
        }
    }
}

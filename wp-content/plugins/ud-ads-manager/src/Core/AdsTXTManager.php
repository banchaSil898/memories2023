<?php

namespace UDAdsManager\Core;

use UDAdsManager\Plugin;

class AdsTXTManager implements WPIntegrationInterface
{
    private $setting;

    public function __construct($setting)
    {
        $this->setting = $setting;
    }

    public function register()
    {
        add_action('init', [$this, 'initHook'], 1);
        add_action('pre_get_posts', [$this, 'preGetPostsHook']);
        add_action('after_setup_theme', [$this, 'reduceQueryLoad'], 99);

        add_action("update_option_". Plugin::GLOBAL_OPTION_KEY, [$this, 'doUpdateGlobalOption'], 10, 3);
        add_action("add_option_". Plugin::GLOBAL_OPTION_KEY, [$this, 'doAddGlobalOption'], 10, 2);
    }

    public function initHook()
    {
        global $wp;

        //register url params
        $wp->add_query_var('udam_ads_txt');

        $this->addRoutes();
    }

    public function addRoutes()
    {
        if ($this->setting->ads_txt_enable) {
            add_rewrite_rule('ads\.txt$', 'index.php?udam_ads_txt=1', 'top');
        }
    }

    public function removeRoutes()
    {
        add_filter('rewrite_rules_array', function ($rules) {
            if (isset($rules['ads\.txt$'])) {
                unset($rules['ads\.txt$']);
            }

            return $rules;
        }, 10, 1);
    }

    public function doUpdateGlobalOption($old_value, $value, $option)
    {
        $old_ads_txt_enable = isset($old_value['ads_txt_enable']) ? $old_value['ads_txt_enable'] : false;
        $new_ads_txt_enable = isset($value['ads_txt_enable']) ? $value['ads_txt_enable'] : false;
        if ($new_ads_txt_enable !== $old_ads_txt_enable) {
            if ($new_ads_txt_enable === true) {
                $this->addRoutes();
            } else {
                $this->removeRoutes();
            }

            flush_rewrite_rules();
        }
    }

    public function doAddGlobalOption($option, $value)
    {
        $new_ads_txt_enable = isset($value['ads_txt_enable']) ? $value['ads_txt_enable'] : false;
        if ($new_ads_txt_enable === true) {
            $this->addRoutes();
        } else {
            $this->removeRoutes();
        }
        flush_rewrite_rules();
    }

    /**
     * @param \WP_Query $wp_query
     */
    public function preGetPostsHook($wp_query)
    {
        if (! $wp_query->is_main_query()) {
            return;
        }

        $ads_txt_enable = $this->setting->ads_txt_enable;
        if (! $ads_txt_enable) {
            $wp_query->set_404();
            status_header(404);

            return;
        }

        $udam_ads_txt = get_query_var('udam_ads_txt');
        if (! empty($udam_ads_txt)) {
            $this->renderAdsTXT();
            $this->adsTXTClose();

            return;
        }

        return;
    }

    private function renderAdsTXT()
    {
        header('HTTP/1.1 200 OK', true, 200);
        header('X-Robots-Tag: noindex, follow', true); // Prevent the search engines from indexing the XML Sitemap.
        header('Content-Type: text/plain; charset=' . esc_attr(get_bloginfo('charset')));

        echo esc_html($this->setting->ads_txt_content);
    }

    private function adsTXTClose()
    {
        remove_all_actions('wp_footer');
        die();
    }

    public function reduceQueryLoad()
    {
        if (! $this->setting->ads_txt_enable) {
            return;
        }

        if (! isset($_SERVER['REQUEST_URI'])) {
            return;
        }

        $request_uri = $_SERVER['REQUEST_URI'];

        if (false !== stripos($request_uri, 'ads.txt')) {
            remove_all_actions('widgets_init');
        }
    }
}

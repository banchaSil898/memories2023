<?php

namespace UDAdsManager\Core;

use UDAdsManager\Plugin;

class Setting implements WPIntegrationInterface
{
    private $global_defaults;

    public function __construct()
    {
        $this->global_defaults = [
            'dfp_enable'               => true,
            'dfp_collapse_empty_div'   => 'none',
            'dfp_interstitial_ad_unit' => '',
            'ads_txt_enable'           => true,
            'ads_txt_content'          => '',
        ];
    }

    public function register()
    {
        add_filter("sanitize_option_".Plugin::GLOBAL_OPTION_KEY, [$this, 'filterSanitizeGlobalOption']);
        add_filter("default_option_".Plugin::GLOBAL_OPTION_KEY, [$this, 'filterDefaultGlobalOption'], 10, 3);
    }

    public function filterSanitizeGlobalOption($values)
    {
        $results = [];
        foreach ($values as $key => $value) {
            if (!array_key_exists($key, $this->global_defaults)) {
                continue;
            }

            switch ($key) {
                case 'dfp_enable':
                case 'ads_txt_enable':
                    $value = (bool)$value;
                    break;
                case 'ads_txt_content':
                    $value = trim($value);
                    $sanitized_value = sanitize_textarea_field($value);

                    if ($value !== $sanitized_value) {
                        $error = __('invalid ads.txt content', UD_ADS_MANAGER_TEXT_DOMAIN);
                    }

                    $value = $sanitized_value;
                    break;
                case 'dfp_collapse_empty_div':
                    $sanitized_value = sanitize_text_field($value);
                    if (!in_array($sanitized_value, ["none", "collapse", "collapse_before"])) {
                        $error = __('invalid DFP Collapse Mode', UD_ADS_MANAGER_TEXT_DOMAIN);
                    }
                    $value = $sanitized_value;
                    break;
                case 'dfp_interstitial_ad_unit':
                    $value = trim($value);
                    $sanitized_value = sanitize_text_field($value);
                    if ($value !== $sanitized_value) {
                        $error = __('invalid DFP interstitial ad unit', UD_ADS_MANAGER_TEXT_DOMAIN);
                    }

                    $value = $sanitized_value;
                    break;
                }

            if (! empty($error)) {
                $value = get_option(Plugin::GLOBAL_OPTION_KEY, $this->global_defaults)[$key];
                if (function_exists('add_settings_error')) {
                    add_settings_error(Plugin::GLOBAL_OPTION_KEY, "invalid_{Plugin::GENERAL_OPTION_KEY}", $error);
                }
            }
            $error = '';
            $results[$key] = $value;
        }
        return $results;
    }

    public function filterDefaultGlobalOption($default, $option, $passed_default)
    {
        if ($passed_default) {
            return $default;
        }

        return $this->global_defaults;
    }

    public function __get($key)
    {
        if (! $key) {
            return;
        }

        if (!array_key_exists($key, $this->global_defaults)) {
            return;
        }

        return array_merge($this->global_defaults, get_option(Plugin::GLOBAL_OPTION_KEY, []))[$key];
    }

    public function __isset($key)
    {
        return null !== $this->__get($key);
    }
}

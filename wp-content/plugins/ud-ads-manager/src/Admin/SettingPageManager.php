<?php

namespace UDAdsManager\Admin;

use UDAdsManager\Core\WPIntegrationInterface;
use UDAdsManager\Plugin;

class SettingPageManager implements WPIntegrationInterface
{
    public const SETTING_PAGE_SLUG = 'udam-setting';

    private $setting;

    public function __construct($setting)
    {
        $this->setting = $setting;
    }

    public function register()
    {
        add_action('admin_init', [$this , 'registerOption']);
        add_action('admin_init', [$this , 'registerOptionField']);
        add_action('admin_menu', [$this , 'registerMenuPage']);
    }

    public function registerOption()
    {
        register_setting(Plugin::GLOBAL_OPTION_KEY, Plugin::GLOBAL_OPTION_KEY);
    }

    public function registerOptionField()
    {
        $main_section_id = 'main_section';
        add_settings_section(
            $main_section_id,
            '',
            [$this, 'renderMainSection'],
            self::SETTING_PAGE_SLUG
        );

        $this->addSettingsField(
            'dfp_enable',
            __('Enable DFP Ads', UD_ADS_MANAGER_TEXT_DOMAIN),
            [$this, 'renderSingleCheckboxField'],
            self::SETTING_PAGE_SLUG,
            $main_section_id,
            ['description' => 'check this box to enable DFP ads'],
        );

        $this->addSettingsField(
            'dfp_collapse_empty_div',
            __('Set Collapse Empty Div Mode', UD_ADS_MANAGER_TEXT_DOMAIN),
            [$this, 'renderSelectField'],
            self::SETTING_PAGE_SLUG,
            $main_section_id,
            [
                'description' => 'set global collapse empty div mode',
                'options'     => [
                    "none"            => "None",
                    "collapse"        => "Collapse",
                    "collapse_before" => "Collapse before fetching ads"
                ]
            ],
        );

        $this->addSettingsField(
            'ads_txt_enable',
            __('Enable ads.txt', UD_ADS_MANAGER_TEXT_DOMAIN),
            [$this, 'renderSingleCheckboxField'],
            self::SETTING_PAGE_SLUG,
            $main_section_id,
            [ 'description' => 'check this box to enable /ads.txt'],
        );

        $this->addSettingsField(
            'ads_txt_content',
            __('Ads.txt Content', UD_ADS_MANAGER_TEXT_DOMAIN),
            [$this, 'renderTextAreaField'],
            self::SETTING_PAGE_SLUG,
            $main_section_id,
            [
                'description' => 'put the ads.txt content here',
                'rows'        => 20,
                'cols'        => 100
            ],
        );

        $this->addSettingsField(
            'dfp_interstitial_ad_unit',
            __('DFP Interstitial Ad Unit', UD_ADS_MANAGER_TEXT_DOMAIN),
            [$this, 'renderStringField'],
            self::SETTING_PAGE_SLUG,
            $main_section_id,
            [
                'description' => 'put DFP interstitial ad unit here',
            ],
        );
    }

    public function registerMenuPage()
    {
        // $menu_icon = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxOC4yIDE0LjE0Ij4KICAgIDxwYXRoIGZpbGw9InJnYmEoMjQwLDI0NSwyNTAsLjYpIiBzdHJva2U9Im5vbmUiIGQ9Ik0zLjU5IDkuMjNoMi43M0E5LjM0IDkuMzQgMCAwMTEuNCA1LjQ4YTkgOSAwIDAxNC43LTMuNSAzLjk0IDMuOTQgMCAwMC0xIDIuNyA0LjA1IDQuMDUgMCAwMDguMS4xdi0uMWEzLjg2IDMuODYgMCAwMC0xLjEtMi43IDkgOSAwIDAxNC43IDMuNSA5LjM0IDkuMzQgMCAwMS00LjkyIDMuNzVoMi43M2wuMzktLjI1YTEwLjkyIDEwLjkyIDAgMDAyLjQtMi40Yy4zLS40LjUtLjguOC0xLjJhMTAuNDcgMTAuNDcgMCAwMC0uOC0xLjFBMTAuMjcgMTAuMjcgMCAwMDMgMS45OGExMi4yNCAxMi4yNCAwIDAwLTMgMy40IDguMzQgOC4zNCAwIDAwLjggMS4yIDEwLjE2IDEwLjE2IDAgMDAyLjc5IDIuNjV6TTkuMiAxLjk4YTEuMjcgMS4yNyAwIDExMCAxLjggMS4yNyAxLjI3IDAgMDEwLTEuOHpNNC4zNCA5LjYzaC4yNmwuMi4wNmEuOTEuOTEgMCAwMS4xNi4xMSAxLjQgMS40IDAgMDEuMTMuMTggMi42NyAyLjY3IDAgMDEuMjcgMS4zNnYyLjc4aC0uNzF2LTIuOWExIDEgMCAwMC0uMDgtLjQ5LjI4LjI4IDAgMDAtLjI3LS4xNGgtLjZ2My41NUgzVjkuNjN6TTYuNzEgMTQuMTRoLS43VjkuNjNoLjd6TTcuMSA5LjYzaC43N2wuNTQgMS4zNC41Mi0xLjM0aC44MmwtLjk1IDIuMjguOTUgMi4yM2gtLjgxbC0uNTItMS4yOS0uNTQgMS4yOUg3LjFsMS0yLjIzek0xNS4xIDEyLjM2aC0xLjUyYTEuODkgMS44OSAwIDAwMCAuNDEuNjYuNjYgMCAwMC4xNC4yNi4zNi4zNiAwIDAwLjE2LjEyLjY1LjY1IDAgMDAuMjYgMGguOTZ2Ljk1aC0xYTEuMTYgMS4xNiAwIDAxLS40OS0uMDkgMSAxIDAgMDEtLjM2LS4zNCAzLjEyIDMuMTIgMCAwMS0uNDYtMS44OCA0LjI1IDQuMjUgMCAwMS4xMy0xLjEgMiAyIDAgMDEuMzYtLjc3Ljg1Ljg1IDAgMDEuMzMtLjI1IDEuMTUgMS4xNSAwIDAxLjQ0LS4wOGgxdjFoLS45NWEuNjEuNjEgMCAwMC0uMjQgMCAuMzMuMzMgMCAwMC0uMTYuMTMgMSAxIDAgMDAtLjEuMjUgMy41OCAzLjU4IDAgMDAwIC40aDEuNXpNMTcuNDYgOS42M2guNzRsLTEuMSA0LjUxaC0uNzVsLTEtNC41MWguNzVsLjY0IDMuMXpNMS4wMSAxNC4xNEguNzVhLjY2LjY2IDAgMDEtLjItLjA2LjcyLjcyIDAgMDEtLjE1LS4xMSAxLjI2IDEuMjYgMCAwMS0uMTMtLjE3QTIuNzEgMi43MSAwIDAxMCAxMi40MVY5LjYzaC43djIuOTNhMS4wOSAxLjA5IDAgMDAuMDguNDguMy4zIDAgMDAuMjguMTVoLjU5VjkuNjNoLjcxdjQuNTF6TTEwLjY2IDEzLjMzaC41M2EuNTYuNTYgMCAwMC4zLS4wOC40OC40OCAwIDAwLjItLjIyIDIuMDggMi4wOCAwIDAwLjEyLS40OCAzLjUzIDMuNTMgMCAwMDAtLjY2IDQuMjIgNC4yMiAwIDAwLS4wNi0uNzUgMS4yNCAxLjI0IDAgMDAtLjE3LS40OS4zOS4zOSAwIDAwLS4xOC0uMTYuNDguNDggMCAwMC0uMjYtLjA1aC0uNTN6bS41My0zLjY5YTEuMjggMS4yOCAwIDAxLjYzLjE0IDEgMSAwIDAxLjM4LjQ4IDEuNSAxLjUgMCAwMS4xLjMgMy44MiAzLjgyIDAgMDEuMDguMzljMCAuMTQgMCAuMjkuMDYuNDVzMCAuMzMgMCAuNDl2LjYyYTQgNCAwIDAxLS4wNy41NyAxLjg4IDEuODggMCAwMS0uMjEuNjEuOS45IDAgMDEtLjMzLjM0bC0uMTMuMDRoLTEuNlY5LjY0eiIvPgo8L3N2Zz4K';
        add_menu_page(
            __('Ads Manager Setting', UD_ADS_MANAGER_TEXT_DOMAIN),
            __('Ads Manager Setting', UD_ADS_MANAGER_TEXT_DOMAIN),
            'manage_options',
            self::SETTING_PAGE_SLUG,
            [$this, 'renderMainSettingPage'],
            'dashicons-admin-tools', // 'data:image/svg+xml;base64,'. $menu_icon,
        );
    }

    public function renderMainSettingPage()
    {
        ?>
        <div class="wrap">
            <h2><?php echo __('Unixdev Ads Manager Setting', UD_ADS_MANAGER_TEXT_DOMAIN) ?></h2>
            <?php settings_errors(); ?>
            <form method="post" action="options.php">
                <?php
        settings_fields(Plugin::GLOBAL_OPTION_KEY);
        do_settings_sections(self::SETTING_PAGE_SLUG);
        submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function renderMainSection()
    {
    }

    public function renderSingleCheckboxField($args)
    {
        $id = $args['id'];
        $name = $args['name'];
        $description = $args['description'];
        $aria_description_id = $id . '-description';
        $value = $this->setting->$id;

        echo '<input type="hidden" name="' . $name . '" id="' . $id . '-hidden"  value="0">';
        echo '<input type="checkbox" name="' . $name . '" id="' . $id . '" aria-descriptionby="' . $aria_description_id . '" value="1" ' . checked(true, $value, false) . '/>';
        echo '<p class="description" id="' . $aria_description_id . '">' . $description . '</p>';
    }

    public function renderStringField($args)
    {
        $id = $args['id'];
        $name = $args['name'];
        $description = $args['description'];
        $aria_description_id = $id . '-description';
        $value = $this->setting->$id;

        echo "<input class='regular-text ltr' type='text' name='{$name}' value='{$value}' aria-descriptionby='{$aria_description_id}' />";
        echo "<p class='description' id='{$aria_description_id}'>{$description}</p>";
    }

    public function renderSelectField($args)
    {
        $id = $args['id'];
        $name = $args['name'];
        $description = $args['description'];
        $aria_description_id = $id . '-description';
        $value = $this->setting->$id;
        $options = $args['options'];

        echo "<select type='checkbox' name='{$name}' id='{$id}' aria-descriptionby='{$aria_description_id}'/>";
        foreach ($options as $val => $label) {
            echo "<option value='{$val}'" . selected($val, $value, false) . ">{$label}</option>";
        }
        echo "</select>";
        echo "<p class='description' id='{$aria_description_id}'>{$description}</p>";
    }


    public function renderTextAreaField($args)
    {
        $id = $args['id'];
        $name = $args['name'];
        $description = $args['description'];
        $rows = $args['rows'];
        $cols = $args['cols'];
        $aria_description_id = $id . '-description';
        $value = $this->setting->$id;

        echo "<textarea name='{$name}' id='{$id}' rows='{$rows}' cols='{$cols}' aria-descriptionby='{$aria_description_id}'>{$value}</textarea>";
        echo "<p class='description' id='{$aria_description_id}'>{$description}</p>";
    }

    private function addSettingsField($id, $title, $callback, $page, $section, $args = [])
    {
        $main_option_key = Plugin::GLOBAL_OPTION_KEY;
        $defaults = [
            'id'   => $id,
            'name' => "{$main_option_key}[{$id}]"
        ];
        $args = array_merge($defaults, $args);
        add_settings_field($id, $title, $callback, $page, $section, $args);
    }
}

<?php

namespace UDAdsManager\Admin;

use UDAdsManager\Core\AdInfo;
use UDAdsManager\Core\DFPAdSize\DeserializeMultiSize;
use UDAdsManager\Core\WPIntegrationInterface;
use UDAdsManager\Plugin;

class AdItemMetaBox implements WPIntegrationInterface
{
    private $options;

    private $view_port_sizes = [
        'd'  => '[1140,0]',
        'tl' => '[1019,0]',
        'tp' => '[768,0]',
        'm'  => '[0,0]',
    ];

    private $setting;

    public function __construct($setting)
    {
        $this->setting = $setting;
    }

    public function register()
    {
        $this->initMetaboxOption();

        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
        add_action('do_meta_boxes', [$this, 'removeOtherMetaBoxes']);
        add_action('do_meta_boxes', [$this, 'retrieveMetaBoxesOptions'], 10, 3);
        add_action('save_post', [$this, 'savePost']);
        add_filter('wp_insert_post_data', [$this, 'updateSlugAndTitle'], 10, 2);
        add_action('admin_print_scripts', [$this, 'disableAutosave']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts'], 10, 1);
    }

    public function retrieveMetaBoxesOptions($post_type, $context, $post)
    {
        if ($post_type !== Plugin::ADS_ITEM_POST_TYPE_NAME) {
            return;
        }

        if ($context !== 'normal') {
            return;
        }

        $this->initOptionValue($post);
        $this->initOptionHTMLs($post);
    }

    private function initMetaboxOption()
    {
        $collapse_mode = $this->setting->dfp_collapse_empty_div;

        $default_collapse_mode_label = [
            ""                => "None",
            "none"            => "None",
            "collapse"        => "Collapse",
            "collapse_before" => "Collapse before fetching ads"
        ][$collapse_mode];

        $this->options = [
            'enable' => [
                'label'         => 'Enable',
                'type'          => 'checkbox',
            ],
            'ad_type' => [
                'label'   => 'Ad Type',
                'type'    => 'dropdown',
                'options' => [
                    "dfp"         => "DFP Ad",
                    "custom"      => "Custom Ad",
                ],
            ],
            'excluded_cats' => [
                'label'   => 'Exclude Categories (if this field is not empty, ignore "Include Categories")',
                'type'    => 'category_list_tagify',
            ],
            'included_cats' => [
                'label'   => 'Include Categories',
                'type'    => 'category_list_tagify',
            ],
            'enable_ad_box' => [
                'label'         => 'Enable Ad Box',
                'type'          => 'checkbox',
            ],
            'ad_box_width' => [
                'label' => 'Ad Box Width (px)',
                'type'  => 'number',
            ],
            'ad_box_height' => [
                'label' => 'Ad Box Height (px)',
                'type'  => 'number',
            ],
            'ad_box_mobile_width' => [
                'label' => 'Ad Box Mobile Width (px)',
                'type'  => 'number',
            ],
            'ad_box_mobile_height' => [
                'label' => 'Ad Box Mobile Height (px)',
                'type'  => 'number',
            ],
            'ad_box_padding' => [
                'label' => 'Ad Box Padding (px)',
                'type'  => 'number',
            ],
            'ad_box_background_color' => [
                'label' => 'Background Color',
                'type'  => 'color',
            ],
            'dfp_ad_collapse_empty_div' => [
                'label'   => 'Collapse Empty Div',
                'type'    => 'dropdown',
                'options' => [
                    ""                => "Default ($default_collapse_mode_label)",
                    "none"            => "None",
                    "collapse"        => "Collapse",
                    "collapse_before" => "Collapse before fetching ads"
                ],
            ],
            'dfp_ad_unit_path' => [
                'label'         => 'Unit Path',
                'type'          => 'text',
            ],
            'dfp_ad_size_global' => [
                'label'         => 'Global Ad Size',
                'type'          => 'ad_size',
            ],
            'dfp_ad_size_d' => [
                'label'         => 'Desktop Ad Size',
                'type'          => 'ad_size',
            ],
            'dfp_ad_size_tl' => [
                'label'         => 'Tablet Landscape Ad Size',
                'type'          => 'ad_size',
            ],
            'dfp_ad_size_tp' => [
                'label'         => 'Tablet Portrait Ad Size',
                'type'          => 'ad_size',
            ],
            'dfp_ad_size_m' => [
                'label'         => 'Mobile Ad Size',
                'type'          => 'ad_size',
            ],
            'dfp_ad_custom_css_global' => [
                'label'         => 'Custom CSS',
                'type'          => 'custom_css',
            ],
            'dfp_ad_use_responsive_custom_css' => [
                'label'         => 'Use responsive custom css',
                'type'          => 'checkbox',
            ],
            'dfp_ad_custom_css_d' => [
                'label'         => 'Custom CSS for Desktop Ad',
                'type'          => 'custom_css',
            ],
            'dfp_ad_custom_css_tl' => [
                'label'         => 'Custom CSS for Tablet Landscape Ad',
                'type'          => 'custom_css',
            ],
            'dfp_ad_custom_css_tp' => [
                'label'         => 'Custom CSS for Tablet Portrait Ad',
                'type'          => 'custom_css',
            ],
            'dfp_ad_custom_css_m' => [
                'label'         => 'Custom CSS for Mobile Ad',
                'type'          => 'custom_css',
            ],
            'dfp_ad_fallback_image_global' => [
                'label'         => 'Fallback Image',
                'type'          => 'image',
            ],
            'dfp_ad_use_responsive_fallback_image' => [
                'label'         => 'Use responsive fallback image',
                'type'          => 'checkbox',
            ],
            'dfp_ad_fallback_image_d' => [
                'label'         => 'Fallback Image for Desktop Ad',
                'type'          => 'image',
            ],
            'dfp_ad_fallback_image_tl' => [
                'label'         => 'Fallback Image for Tablet Landscape Ad',
                'type'          => 'image',
            ],
            'dfp_ad_fallback_image_tp' => [
                'label'         => 'Fallback Image for Tablet Portrait Ad',
                'type'          => 'image',
            ],
            'dfp_ad_fallback_image_m' => [
                'label'         => 'Fallback Image for Mobile Ad',
                'type'          => 'image',
            ],
            'dfp_ad_custom_html' => [
                'label'         => 'Custom Html',
                'type'          => 'custom_html',
            ],
            'custom_ad_custom_html' => [
                'label'         => 'Custom Html',
                'type'          => 'custom_html',
            ],
        ];
    }

    private function initOptionValue($post)
    {
        $ad_info = AdInfo::fromPostID($post->ID);

        foreach ($this->options as $key => $option_info) {
            if (property_exists($ad_info, $key)) {
                $option_info['value'] = $ad_info->$key;
            }

            $this->options[$key] = $option_info;
        }

        $device_sizes = ['global', 'd', 'tl', 'tp', 'm'];
        foreach($device_sizes as $device_size){
            $this->options['dfp_ad_size_'. $device_size]['value'] = substr(json_encode($ad_info->dfp_ad_size_of_device_size[$device_size]['ad_sizes']), 1, -1);
            $this->options['dfp_ad_custom_css_'. $device_size]['value'] = $ad_info->dfp_ad_custom_css_of_device_size[$device_size];
            $this->options['dfp_ad_fallback_image_'. $device_size]['value'] = $ad_info->dfp_ad_fallback_image_of_device_size[$device_size];
        }
    }


    public function updateSlugAndTitle($data, $postarr)
    {
        if (Plugin::ADS_ITEM_POST_TYPE_NAME === $data['post_type'] && ! in_array($data['post_status'], ['draft', 'pending', 'auto-draft'])) {
            $data['post_title'] = str_replace('-', '_', $data['post_title']);
            $data['post_name'] = wp_unique_post_slug(sanitize_title($data['post_title']), $postarr['ID'], $data['post_status'], $data['post_type'], $data['post_parent']);
            $data['post_name'] = str_replace('-', '_', $data['post_name']);
            $data['post_title'] = $data['post_name'];
        }

        return $data;
    }

    /**
     * Hooks into WordPress' add_meta_boxes function.
     * Goes through screens (post types) and adds the meta box.
     */
    public function addMetaBoxes()
    {
        add_meta_box(
            'general-ad-setting',
            __('General Ad Setting', UD_ADS_MANAGER_TEXT_DOMAIN),
            [$this, 'renderGeneralAdSettingMetaBox'],
            Plugin::ADS_ITEM_POST_TYPE_NAME,
            'normal',
            'core'
        );
    }

    public function removeOtherMetaBoxes()
    {
        // @todo can we do it in more general way?
        remove_meta_box('mymetabox_revslider_0', Plugin::ADS_ITEM_POST_TYPE_NAME, 'normal');
        remove_meta_box('td_post_theme_settings_metabox', Plugin::ADS_ITEM_POST_TYPE_NAME, 'normal');
    }

    public function disableAutosave()
    {
        $screen = get_current_screen();
        if (! empty($screen->post_type) && $screen->post_type === Plugin::ADS_ITEM_POST_TYPE_NAME) {
            wp_dequeue_script('autosave');
        }
    }

    public function renderGeneralAdSettingMetaBox($post)
    {
        $shortcode_text = '[ud_ad_pos id=\'' . $post->post_title . '\']';
        load_template(UD_ADS_MANAGER_PATH .'/templates/general-ad-setting-meta-box.php', true, [
            'ad_shortcode'  => $shortcode_text,
            'options'       => $this->options
        ]);
    }

    /**
     * Generates the HTML for table rows.
     */
    public function rowFormat($label, $input)
    {
        return sprintf(
            '<tr><th scope="row">%s</th><td>%s</td></tr>',
            $label,
            $input
        );
    }

    private function initOptionHTMLs($post)
    {
        $option_id = Plugin::AD_INFO_OPTION_KEY;

        foreach ($this->options as $key => $option_info) {
            $label = '<label for="' . $key . '">' . $option_info['label'] . '</label>';
            $value = $option_info['value'];
            $name = "{$option_id}[{$key}]";
            $id = "{$option_id}_{$key}";

            switch ($option_info['type']) {
                case 'ad_size':
                    $input = sprintf(
                        '<input class="regular-text" id="%s" name="%s" type="text" value="%s">',
                        esc_attr($id),
                        $name,
                        esc_attr($value),
                    );
                    break;
                case 'custom_html':
                case 'custom_css':
                    $input = sprintf(
                        '<textarea class="large-text" id="%s" name="%s" rows="15">%s</textarea>',
                        esc_attr($id),
                        $name,
                        $value,
                    );
                    break;
                case 'checkbox':
                    $input = sprintf('<input type="hidden" id="%s-hidden" name="%s" value="0">', $id, $name);
                    $input .= sprintf(
                        '<input type="checkbox" id="%s" name="%s" value="1" %s>',
                        esc_attr($id),
                        $name,
                        checked(true, $value, false),
                    );
                    break;
                case 'image' :
                    $url_input = sprintf(
                        '<input class="regular-text" id="%s" name="%s" type="text" value="%s">',
                        esc_attr($id.  '_url'),
                        $name. "[url]",
                        esc_attr($value['url']),
                    );
                    $link_input = sprintf(
                        '<input class="regular-text" id="%s" name="%s" type="text" value="%s">',
                        esc_attr($id.  '_link'),
                        $name. "[link]",
                        esc_attr($value['link']),
                    );
                    $alt_input = sprintf(
                        '<input class="regular-text" id="%s" name="%s" type="text" value="%s">',
                        esc_attr($id.  '_alt'),
                        $name. "[alt]",
                        esc_attr($value['alt']),
                    );
                    $input = '<table style="border: 1px solid #c3c4c7; padding: 10px; background: #f9f9f9;"><tbody>';
                    $input .= $this->rowFormat('URL', $url_input);
                    $input .= $this->rowFormat('Link', $link_input);
                    $input .= $this->rowFormat('Alt', $alt_input);
                    $input .= '</tbody></table>';
                    break;
                case 'dropdown':
                    if (! empty($option_info['options'])) {
                        $input = '<select id="' . esc_attr($id) . '" name="' . $name . '">';
                        foreach ($option_info['options'] as $option_value => $option_label) {
                            $selected = '';
                            if ($value === $option_value || (empty($value) && $option_value === "dfp")) {
                                $selected = 'selected="selected"';
                            }

                            $input .= '<option ' . $selected . ' value="' . esc_attr($option_value) . '">' . $option_label . '</option>';
                        }
                        $input .= '</select>';
                    }
                    break;
                case 'category_list_tagify':
                    $value = array_map(function ($item) {
                        $cat = get_category_by_slug($item);
                        if (!$cat) {
                            return null;
                        }
                        return ['value' => $item, 'label' => $cat->name];
                    }, $value);
                    $value = json_encode($value, JSON_UNESCAPED_SLASHES);

                    $input = sprintf(
                        '<input %s id="%s" name="%s" type="%s" value="%s" style="display:none";>',
                        $option_info['type'] !== 'color' ? 'class="regular-text ud_category_list_tagify"' : 'class="regular-text ud_category_list_tagify"',
                        esc_attr($id),
                        $name,
                        'text',
                        esc_attr($value)
                    );
                    break;
                default:
                    $input = sprintf(
                        '<input %s id="%s" name="%s" type="%s" value="%s">',
                        $option_info['type'] !== 'color' ? 'class="regular-text"' : '',
                        esc_attr($id),
                        $name,
                        $option_info['type'],
                        esc_attr($value)
                    );
            }
            $this->options[$key]['html'] = $this->rowFormat($label, $input);
        }
    }

    /**
     * Hooks into WordPress' save_post function
     */
    public function savePost($post_id)
    {
        if (! isset($_POST['ad_info_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['ad_info_nonce'];
        if (! wp_verify_nonce($nonce, 'ad_info_data')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        $option_id = Plugin::AD_INFO_OPTION_KEY;
        $deserializeMultiSize = new DeserializeMultiSize();
        $data = [];

        foreach ($this->options as $field_id => $field) {
            if (isset($_POST[$option_id][$field_id])) {
                $value = $_POST[$option_id][$field_id];

                switch ($field['type']) {
                    case 'email':
                        $value = sanitize_email($value);
                        break;
                    case 'checkbox':
                        $value = $value === '1' ? true : false;
                        break;
                    case 'ad_size':
                        $value = stripslashes(trim($value));
                        $value = array_unique($deserializeMultiSize('[' . $value . ']'), SORT_REGULAR);
                        usort($value, function ($a, $b) {
                            return $b->compareTo($a);
                        });
                        $value = substr(json_encode($value), 1, -1);
                        break;
                    case 'image':
                        $value = [
                            'url'  => isset($value['url']) ? esc_url_raw($value['url']) : '',
                            'link' => isset($value['link']) ? esc_url_raw($value['link']) : '',
                            'alt'  => isset($value['alt']) ? sanitize_text_field($value['alt']) : '',
                        ];
                        break;
                    case 'category_list_tagify':
                        $value = json_decode(stripslashes($value), true);
                        $value = wp_list_pluck($value, 'value');
                        $value = array_filter($value, function ($item) {
                            return !is_null($item) && $item !== '';
                        });
                        break;
                    case 'custom_html':
                    case 'custom_css':
                        break;
                    default:
                        $value = sanitize_text_field($value);
                        break;
                }
                $data[$field_id] = $value;
            }
        }


        foreach ($this->view_port_sizes as $device => $size) {
            $ad_sizes = isset($data['dfp_ad_size_' . $device]) ? $data['dfp_ad_size_' . $device] : '';

            $data['dfp_size_mapping_rules'][$device]['view_port_size'] = $size;
            $data['dfp_size_mapping_rules'][$device]['ad_sizes'] = $ad_sizes;

            unset($data['dfp_ad_size_' . $device]);
        }

        update_post_meta($post_id, $option_id, $data);

        return $post_id;
    }

    public function enqueueScripts($hook)
    {
        if (($hook !== 'post.php' && $hook !== 'post-new.php') || get_current_screen()->post_type !== Plugin::ADS_ITEM_POST_TYPE_NAME) {
            return;
        }

        wp_enqueue_script('ud-ads-manager-post', UD_ADS_MANAGER_URL . '/assets/js/ud-ads-manager-post.js', ['wp-api-fetch'], UD_ADS_MANAGER_VERSION, true);
        wp_enqueue_style('ud-ads-manager-post', UD_ADS_MANAGER_URL. '/assets/css/ud-ads-manager-post.css', [], UD_ADS_MANAGER_VERSION);
    }
}

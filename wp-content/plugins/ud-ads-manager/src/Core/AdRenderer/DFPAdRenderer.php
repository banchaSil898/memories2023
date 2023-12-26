<?php

namespace UDAdsManager\Core\AdRenderer;

use UDAdsManager\Core\AdInfo;
use UDAdsManager\Plugin;

class DFPAdRenderer extends AbstractAdRenderer
{
    private $rendered_ad_items;

    private $setting;

    public function __construct($setting)
    {
        $this->rendered_ad_items = [];
        $this->setting = $setting;
    }

    public function renderHead()
    {
        echo '<script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>';
        echo '<script>window.googletag = window.googletag || {cmd: []};</script>';
    }

    public function renderFooter()
    {
        $responsive_device_sizes = array_filter(AdInfo::$device_sizes, function ($size) {
            return $size !== 'global';
        });

        // filter out empty ad unit path
        $ad_items = array_filter($this->rendered_ad_items, function ($item) {
            return !empty($item[Plugin::AD_INFO_OPTION_KEY]) && !empty($item[Plugin::AD_INFO_OPTION_KEY]->dfp_ad_unit_path);
        });


        // resolve global ad size
        $dfp_global_ad_size_of_ad_name = [];
        foreach ($ad_items as $ad_item) {
            $ad_info = $ad_item[Plugin::AD_INFO_OPTION_KEY];
            if ($ad_info->dfp_ad_use_responsive_ad_size === true && empty($ad_info->dfp_ad_size_of_device_size['global']['ad_sizes'])) {
                $dfp_global_ad_size_of_ad_name[$ad_info->name] = array_unique(array_merge(
                    $ad_info->dfp_ad_size_of_device_size['d']['ad_sizes'],
                    $ad_info->dfp_ad_size_of_device_size['tp']['ad_sizes'],
                    $ad_info->dfp_ad_size_of_device_size['tl']['ad_sizes'],
                    $ad_info->dfp_ad_size_of_device_size['m']['ad_sizes'],
                ), SORT_REGULAR);

                usort($dfp_global_ad_size_of_ad_name[$ad_info->name], fn ($a, $b) => $b->compareTo($a));
            } else {
                $dfp_global_ad_size_of_ad_name[$ad_info->name] = $ad_info->dfp_ad_size_of_device_size['global']['ad_sizes'];
            }
        }

        // filter out ad without size;
        $ad_items = array_filter($ad_items, function ($item) {
            return !empty($item[Plugin::AD_INFO_OPTION_KEY]->dfp_ad_size_of_device_size['global']) || !empty($item[Plugin::AD_INFO_OPTION_KEY]->dfp_ad_use_responsive_ad_size);
        });

        // make fallback image html
        $dfp_fallback_image_html_of_ad_name = [];
        foreach ($ad_items as $ad_name => $ad_item) {
            $ad_info = $ad_item[Plugin::AD_INFO_OPTION_KEY];
            $dfp_fallback_image_html_of_ad_name[$ad_info->name] = [];
            foreach (AdInfo::$device_sizes as $size) {
                $image = $ad_info->dfp_ad_fallback_image_of_device_size[$size];
                $html = '';
                if (!empty($image['url'])) {
                    $html = '<img src="' . esc_attr($image['url']) . '"  alt="' . esc_attr($image['alt']).'">';
                    if (!empty($image['link'])) {
                        $html = '<a href="' . esc_attr($image['link']) . '">' . $html . '</a>';
                    }
                }
                $dfp_fallback_image_html_of_ad_name[$ad_info->name][$size] = $html;
            }
        }

        // make ad device mapping
        $ad_item_device_size_mapping = array_map(fn () => [], array_flip(AdInfo::$device_sizes));

        foreach ($ad_items as $ad_name => $ad_item) {
            $ad_info = $ad_item[Plugin::AD_INFO_OPTION_KEY];
            if ($ad_info->dfp_ad_use_responsive_ad_size) {
                foreach ($responsive_device_sizes as $device_size) {
                    if (!empty($ad_info->dfp_ad_size_of_device_size[$device_size]['ad_sizes'])) {
                        $ad_item_device_size_mapping[$device_size][$ad_name] = $ad_item;
                    }
                }
            } else {
                $ad_item_device_size_mapping['global'][$ad_name] = $ad_item;
            }
        }

        $interstitial_ad_unit = !empty($this->setting->dfp_interstitial_ad_unit) ? $this->setting->dfp_interstitial_ad_unit : '';
        $targets = $this->getTargetingKeyValues();

        load_template(UD_ADS_MANAGER_PATH .'/templates/dfp-footer.php', true, [
            'ad_items'                            => $ad_items,
            'ad_item_device_size_mapping'         => $ad_item_device_size_mapping,
            'interstitial_ad_unit'                => $interstitial_ad_unit,
            'targets'                             => $targets,
            'dfp_global_ad_size_of_ad_name'       => $dfp_global_ad_size_of_ad_name,
            'dfp_fallback_image_html_of_ad_name'  => $dfp_fallback_image_html_of_ad_name,
        ]);
    }

    public function getCustomCSS($ad_item)
    {
        $ad_info = $ad_item[Plugin::AD_INFO_OPTION_KEY];
        $div_id = $this->getDFPDivID($ad_info->name);

        $buffy = '';

        if (!$ad_info->dfp_ad_use_responsive_custom_css) {
            if (empty($ad_info->dfp_ad_custom_css_of_device_size['global'])) {
                return $buffy;
            }

            $custom_css = $ad_info->dfp_ad_custom_css_of_device_size['global'];

            $buffy .= '<style>';
            $buffy .= "#{$div_id} { {$custom_css} }";
            $buffy .= '</style>';
        } else {
            if (empty($ad_info->dfp_ad_custom_css_of_device_size['d']) &&
                empty($ad_info->dfp_ad_custom_css_of_device_size['tl']) &&
                empty($ad_info->dfp_ad_custom_css_of_device_size['tp']) &&
                empty($ad_info->dfp_ad_custom_css_of_device_size['m'])
            ) {
                return $buffy;
            }
            $custom_css_d = $ad_info->dfp_ad_custom_css_of_device_size['d'];
            $custom_css_tl = $ad_info->dfp_ad_custom_css_of_device_size['tl'];
            $custom_css_tp = $ad_info->dfp_ad_custom_css_of_device_size['tp'];
            $custom_css_m = $ad_info->dfp_ad_custom_css_of_device_size['m'];

            $buffy .= '<style>';
            if (!empty($custom_css_d)) {
                $buffy .= "@media (min-width: 1139.98px) { #{$div_id} { {$custom_css_d} } }";
            }

            if (!empty($custom_css_tl)) {
                $buffy .= "@media (min-width: 1018.98px) and (max-width: 1140px) { #{$div_id} { {$custom_css_tl} } }";
            }

            if (!empty($custom_css_tp)) {
                $buffy .= "@media (min-width: 767.98px) and (max-width: 1019px) { #{$div_id} { {$custom_css_tp} } }";
            }

            if (!empty($custom_css_m)) {
                $buffy .= "@media (max-width: 768px) { #{$div_id} { {$custom_css_m} } }";
            }
            $buffy .= '</style>';
        }

        return $buffy;
    }

    public function getAdItemTag($ad_id)
    {
        $buffy = '';
        if (! key_exists($ad_id, $this->ad_items)) {
            return $buffy;
        }

        $ad_item = $this->ad_items[$ad_id];
        $ad_info = $ad_item[Plugin::AD_INFO_OPTION_KEY];

        if (!$ad_info->enable) {
            return $buffy;
        }

        if (is_single()) {
            $cats = get_the_category();
            $cat_slugs = wp_list_pluck($cats, 'slug');
            if (!empty($ad_info->excluded_cats)) {
                if (!empty(array_intersect($cat_slugs, $ad_info->excluded_cats))) {
                    return $buffy;
                }
            } elseif (!empty($ad_info->included_cats)) {
                if (empty(array_intersect($cat_slugs, $ad_info->included_cats))) {
                    return $buffy;
                }
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            $current_term_obj = get_queried_object();
            $targets['taxonomy'] = $current_term_obj->taxonomy;
            $targets['term'] = $current_term_obj->slug;
            if (!empty($ad_info->excluded_cats)) {
                if (in_array($current_term_obj->slug, $ad_info->excluded_cats)) {
                    return $buffy;
                }
            } elseif (!empty($ad_info->included_cats)) {
                if (!in_array($current_term_obj->slug, $ad_info->included_cats)) {
                    return $buffy;
                }
            }
        } elseif (is_author()) {
        } elseif (is_post_type_archive()) {
        } elseif (is_archive()) {
        } elseif (is_404()) {
        } else {
        }

        $this->rendered_ad_items[$ad_id] = $ad_item;
        $div_id = $this->getDFPDivID($ad_id);

        $buffy .= $this->getCustomCSS($ad_item);
        if ( $ad_info->enable_ad_box ) {
			if ( ( $ad_info->ad_box_mobile_width !== 0 && $ad_info->ad_box_mobile_height !== 0 ) && ( $ad_info->ad_box_width === 0 && $ad_info->ad_box_height === 0 ) ) {
				$buffy .= wp_sprintf(
					'<style>
                        #%s {
                            width: %dpx;
                            height: %dpx;
                            padding: %dpx;
                            background-color: %s;
                            margin-inline: auto;
                        }
                    </style>',
					esc_attr( $div_id ),
					esc_attr( $ad_info->ad_box_mobile_width ),
					esc_attr( $ad_info->ad_box_mobile_height ),
					esc_attr( $ad_info->ad_box_padding ),
					esc_attr( $ad_info->ad_box_background_color )
				);
			}

			if ( ( $ad_info->ad_box_mobile_width === 0 && $ad_info->ad_box_mobile_height === 0 ) && ( $ad_info->ad_box_width !== 0 && $ad_info->ad_box_height !== 0 ) ) {
				$buffy .= wp_sprintf(
					'<style>
                        #%s {
                            width: %dpx;
                            height: %dpx;
                            padding: %dpx;
                            background-color: %s;
                            margin-inline: auto;
                        }
                    </style>',
					esc_attr( $div_id ),
					esc_attr( $ad_info->ad_box_width ),
					esc_attr( $ad_info->ad_box_height ),
					esc_attr( $ad_info->ad_box_padding ),
					esc_attr( $ad_info->ad_box_background_color )
				);
			}

			if ( ( $ad_info->ad_box_mobile_width !== 0 && $ad_info->ad_box_mobile_height !== 0 ) && ( $ad_info->ad_box_width !== 0 && $ad_info->ad_box_height !== 0 ) ) {
				$buffy .= wp_sprintf(
					'<style>
                        #%s {
                            width: %dpx;
                            height: %dpx;
                            padding: %dpx;
                            background-color: %s;
                            margin-inline: auto;
                        }
                        @media ( min-width: 992px ) {
                            #%s {
                                width: %dpx;
                                height: %dpx;
                            }
                        }
                    </style>',
					esc_attr( $div_id ),
					esc_attr( $ad_info->ad_box_mobile_width ),
					esc_attr( $ad_info->ad_box_mobile_height ),
					esc_attr( $ad_info->ad_box_padding ),
					esc_attr( $ad_info->ad_box_background_color ),
					esc_attr( $div_id ),
					esc_attr( $ad_info->ad_box_width ),
					esc_attr( $ad_info->ad_box_height ),
				);
			}
		}
        $buffy .= '<div id="' . esc_attr($div_id) . '" class="skeleton-ad"></div>';

        return $buffy;
    }

    private function getTargetingKeyValues()
    {
        $targets = [];
        if (is_page() or is_single()) {
            $post = get_post();

            $targets['post_id'] = (string) $post->ID;
            $targets['post_name'] = $post->post_name;
            $targets['post_title'] = $post->post_title;
            $targets['post_type'] = $post->post_type;

            $taxonomy_names = get_post_taxonomies($post);
            foreach ($taxonomy_names as $tax_name) {
                $terms = get_the_terms($post, $tax_name);
                $target_terms = [];
                if (is_array($terms)) {
                    foreach ($terms as $term) {
                        array_push($target_terms, urldecode($term->slug));
                    }

                    $targets[$tax_name] = $target_terms;
                }
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            $current_term_obj = get_queried_object();
            $targets['taxonomy'] = $current_term_obj->taxonomy;
            $targets['term'] = $current_term_obj->slug;
        } elseif (is_author()) {
        } elseif (is_post_type_archive()) {
        } elseif (is_archive()) {
        } elseif (is_404()) {
        } else {
        }

        return $targets;
    }

    private function getDFPDivID($ad_id)
    {
        return 'ud-dfp-ad-pos-' . $ad_id;
    }
}

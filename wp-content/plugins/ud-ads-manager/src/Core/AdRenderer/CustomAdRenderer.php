<?php

namespace UDAdsManager\Core\AdRenderer;

use UDAdsManager\Core\AdInfo;
use UDAdsManager\Plugin;

class CustomAdRenderer extends AbstractAdRenderer {

	private $setting;

	public function __construct( $setting ) {
		$this->setting = $setting;
	}

	public function renderHead() {
	}

	public function renderFooter() {
	}

	public function getAdItemTag( $ad_id ) {
		$buffy = '';
		if ( ! key_exists( $ad_id, $this->ad_items ) ) {
			return $buffy;
		}

		$ad_item = $this->ad_items[ $ad_id ];
		$ad_info = $ad_item[ Plugin::AD_INFO_OPTION_KEY ];

		if ( ! $ad_info->enable ) {
			return $buffy;
		}

		if ( is_single() ) {
			$cats      = get_the_category();
			$cat_slugs = wp_list_pluck( $cats, 'slug' );
			if ( ! empty( $ad_info->excluded_cats ) ) {
				if ( ! empty( array_intersect( $cat_slugs, $ad_info->excluded_cats ) ) ) {
					return $buffy;
				}
			} elseif ( ! empty( $ad_info->included_cats ) ) {
				if ( empty( array_intersect( $cat_slugs, $ad_info->included_cats ) ) ) {
					return $buffy;
				}
			}
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$current_term_obj    = get_queried_object();
			$targets['taxonomy'] = $current_term_obj->taxonomy;
			$targets['term']     = $current_term_obj->slug;
			if ( ! empty( $ad_info->excluded_cats ) ) {
				if ( in_array( $current_term_obj->slug, $ad_info->excluded_cats ) ) {
					return $buffy;
				}
			} elseif ( ! empty( $ad_info->included_cats ) ) {
				if ( ! in_array( $current_term_obj->slug, $ad_info->included_cats ) ) {
					return $buffy;
				}
			}
		} elseif ( is_author() ) {
		} elseif ( is_post_type_archive() ) {
		} elseif ( is_archive() ) {
		} elseif ( is_404() ) {
		} else {
		}

		$div_id = $this->getDFPDivID( $ad_id );
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
		$buffy .= '<div id="' . esc_attr( $div_id ) . '" class="skeleton-ad">';
		$buffy .= $ad_info->custom_ad_custom_html;
		$buffy .= '</div>';

		return $buffy;
	}

	private function getDFPDivID( $ad_id ) {
		return 'ud-custom-ad-pos-' . $ad_id;
	}
}

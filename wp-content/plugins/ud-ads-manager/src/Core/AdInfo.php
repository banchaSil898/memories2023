<?php

namespace UDAdsManager\Core;

use UDAdsManager\Core\DFPAdSize\DeserializeMultiSize;
use UDAdsManager\Plugin;

class AdInfo {

	public static array $device_sizes = array( 'd', 'tl', 'tp', 'm', 'global' );

	public bool $enable;
	public string $name;
	public string $ad_type;
	public array $excluded_cats;
	public array $included_cats;

	public bool $enable_ad_box;
	public int $ad_box_width;
	public int $ad_box_height;
	public int $ad_box_mobile_width;
	public int $ad_box_mobile_height;
	public int $ad_box_padding;
	public string $ad_box_background_color;

	public string $dfp_ad_collapse_empty_div;
	public string $dfp_ad_unit_path;
	public string $dfp_ad_custom_html;

	public bool $dfp_ad_use_responsive_ad_size;
	public bool $dfp_ad_use_responsive_custom_css;
	public bool $dfp_ad_use_responsive_fallback_image;

	public array $dfp_ad_size_of_device_size;
	public array $dfp_ad_custom_css_of_device_size;
	public array $dfp_ad_fallback_image_of_device_size;

	public string $custom_ad_custom_html;

	public function __construct( string $name ) {
		$this->name          = $name;
		$this->enable        = true;
		$this->ad_type       = 'dfp';
		$this->excluded_cats = array();
		$this->included_cats = array();

		$this->enable_ad_box           = false;
		$this->ad_box_width            = 0;
		$this->ad_box_height           = 0;
		$this->ad_box_mobile_width     = 0;
		$this->ad_box_mobile_height    = 0;
		$this->ad_box_padding          = 0;
		$this->ad_box_background_color = '#cccccc';

		$this->dfp_ad_collapse_empty_div = '';
		$this->dfp_ad_unit_path          = '';
		$this->dfp_ad_custom_html        = '';

		$this->dfp_ad_use_responsive_ad_size        = false;
		$this->dfp_ad_use_responsive_custom_css     = false;
		$this->dfp_ad_use_responsive_fallback_image = false;

		$this->dfp_ad_size_of_device_size = array_map(
			function () {
				return array(
					'view_port_size' => '',
					'ad_sizes'       => array(),
				);
			},
			array_flip( self::$device_sizes )
		);

		$this->dfp_ad_size_of_device_size['d']['view_port_size']  = '[1140, 0]';
		$this->dfp_ad_size_of_device_size['tl']['view_port_size'] = '[1019, 0]';
		$this->dfp_ad_size_of_device_size['tp']['view_port_size'] = '[768, 0]';
		$this->dfp_ad_size_of_device_size['m']['view_port_size']  = '[0, 0]';

		$this->dfp_ad_custom_css_of_device_size = array_map(
			function () {
				return '';
			},
			array_flip( self::$device_sizes )
		);

		$this->dfp_ad_fallback_image_of_device_size = array_map(
			function () {
				return array(
					'url'  => '',
					'link' => '',
					'alt'  => '',
				);
			},
			array_flip( self::$device_sizes )
		);

		$this->custom_ad_custom_html = '';
	}

	public static function fromPostID( int $post_id ): AdInfo {
		$post = get_post( $post_id );
		if ( empty( $post ) ) {
			return null;
		}
		$post_meta    = get_post_meta( $post_id, Plugin::AD_INFO_OPTION_KEY, true );
		$data         = ! empty( $post_meta ) ? $post_meta : array();
		$data['name'] = $post->post_name;
		$ad_info      = self::hydrate( $data );
		return $ad_info;
	}

	public static function hydrate( $data ): AdInfo {
		// init tool
		$deserializeMultiSize    = new DeserializeMultiSize();
		$responsive_device_sizes = array_filter(
			self::$device_sizes,
			function ( $item ) {
				return $item !== 'global';
			}
		);

		$ad_info = new AdInfo( $data['name'] ?? '' );

		if ( isset( $data['enable'] ) && is_bool( $data['enable'] ) ) {
			$ad_info->enable = $data['enable'];
		}

		if ( isset( $data['ad_type'] ) && in_array( $data['ad_type'], array( 'dfp', 'custom' ) ) ) {
			$ad_info->ad_type = $data['ad_type'];
		}

		if ( isset( $data['excluded_cats'] ) && is_array( $data['excluded_cats'] ) ) {
			$ad_info->excluded_cats = array_filter(
				$data['excluded_cats'],
				function ( $item ) {
					return ! empty( $item ) && is_string( $item );
				}
			);
		}

		if ( isset( $data['included_cats'] ) && is_array( $data['included_cats'] ) ) {
			$ad_info->included_cats = array_filter(
				$data['included_cats'],
				function ( $item ) {
					return ! empty( $item ) && is_string( $item );
				}
			);
		}

		if ( isset( $data['enable_ad_box'] ) && is_bool( $data['enable_ad_box'] ) ) {
			$ad_info->enable_ad_box = $data['enable_ad_box'];
		}

		$ad_info->ad_box_width         = isset( $data['ad_box_width'] ) ? (int) $data['ad_box_width'] : 0;
		$ad_info->ad_box_height        = isset( $data['ad_box_height'] ) ? (int) $data['ad_box_height'] : 0;
		$ad_info->ad_box_mobile_width  = isset( $data['ad_box_mobile_width'] ) ? (int) $data['ad_box_mobile_width'] : 0;
		$ad_info->ad_box_mobile_height = isset( $data['ad_box_mobile_height'] ) ? (int) $data['ad_box_mobile_height'] : 0;
		$ad_info->ad_box_padding       = isset( $data['ad_box_padding'] ) ? (int) $data['ad_box_padding'] : 0;

		if ( isset( $data['ad_box_background_color'] ) && is_string( $data['ad_box_background_color'] ) ) {
			$ad_info->ad_box_background_color = $data['ad_box_background_color'];
		}

		if ( isset( $data['dfp_ad_collapse_empty_div'] ) && in_array( $data['dfp_ad_collapse_empty_div'], array( '', 'none', 'collapse', 'collapse_before' ) ) ) {
			$ad_info->dfp_ad_collapse_empty_div = $data['dfp_ad_collapse_empty_div'];
		}

		if ( isset( $data['dfp_ad_unit_path'] ) && is_string( $data['dfp_ad_unit_path'] ) ) {
			$ad_info->dfp_ad_unit_path = $data['dfp_ad_unit_path'];
		}

		if ( isset( $data['dfp_ad_custom_html'] ) && is_string( $data['dfp_ad_custom_html'] ) ) {
			$ad_info->dfp_ad_custom_html = $data['dfp_ad_custom_html'];
		}

		if ( ! empty( $data['dfp_ad_size_global'] ) && is_string( $data['dfp_ad_size_global'] ) ) {
			$ad_info->dfp_ad_size_of_device_size['global']['ad_sizes'] = array_unique( $deserializeMultiSize( '[' . $data['dfp_ad_size_global'] . ']' ), SORT_REGULAR );
		}

		if ( ! empty( $data['dfp_size_mapping_rules'] ) && is_array( $data['dfp_size_mapping_rules'] ) ) {
			foreach ( $responsive_device_sizes as $device_size ) {
				if ( ! empty( $data['dfp_size_mapping_rules'][ $device_size ]['ad_sizes'] ) ) {
					$ad_info->dfp_ad_size_of_device_size[ $device_size ]['ad_sizes'] = array_unique( $deserializeMultiSize( '[' . $data['dfp_size_mapping_rules'][ $device_size ]['ad_sizes'] . ']' ), SORT_REGULAR );
				}
			}
		}

		foreach ( self::$device_sizes as $device_size ) {
			if ( ! empty( $data[ 'dfp_ad_fallback_image_' . $device_size ] ) && is_array( $data[ 'dfp_ad_fallback_image_' . $device_size ] ) ) {
				$ad_info->dfp_ad_fallback_image_of_device_size[ $device_size ] = array_merge( $ad_info->dfp_ad_fallback_image_of_device_size[ $device_size ], $data[ 'dfp_ad_fallback_image_' . $device_size ] );
			}
		}

		foreach ( self::$device_sizes as $device_size ) {
			$value = $data[ 'dfp_ad_custom_css_' . $device_size ] ?? '';
			if ( ! empty( $value ) && is_string( $value ) ) {
				$ad_info->dfp_ad_custom_css_of_device_size[ $device_size ] = $value;
			}
		}

		foreach ( $responsive_device_sizes as $device_size ) {
			if ( ! empty( $ad_info->dfp_ad_size_of_device_size[ $device_size ]['ad_sizes'] ) ) {
				$ad_info->dfp_ad_use_responsive_ad_size = true;
				break;
			}
		}

		if ( isset( $data['dfp_ad_use_responsive_custom_css'] ) && is_bool( $data['dfp_ad_use_responsive_custom_css'] ) ) {
			$ad_info->dfp_ad_use_responsive_custom_css = $data['dfp_ad_use_responsive_custom_css'];
		}

		if ( isset( $data['dfp_ad_use_responsive_fallback_image'] ) && is_bool( $data['dfp_ad_use_responsive_fallback_image'] ) ) {
			$ad_info->dfp_ad_use_responsive_fallback_image = $data['dfp_ad_use_responsive_fallback_image'];
		}

		if ( isset( $data['custom_ad_custom_html'] ) && is_string( $data['custom_ad_custom_html'] ) ) {
			$ad_info->custom_ad_custom_html = $data['custom_ad_custom_html'];
		}

		return $ad_info;
	}
}

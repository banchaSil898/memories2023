<?php
/**
 * Plugin Name: PDPA for Matichon
 * Plugin URI: http://unixdev.co.th
 * description: Plugin for installing pdpa on your website
 * Version: 3.0
 * Author: Unixdev
 * Author URI: http://unixdev.co.th
 * License: GPL2
 *
 * @package matichon
 */

/**
 * Prevent Direct Access
 */
defined( 'ABSPATH' ) || die( 'Restricted access!' );

require_once __DIR__ . '/class-custom-pdpa-settings-page.php';

/**
 * Enqueue Script for this plugin.
 *
 * @return void
 */
function pdpa_script() {
	wp_enqueue_style(
		'pdpa-style',
		plugins_url() . '/plugin-pdpa/css/pdpa.css',
		array(),
		filemtime( plugin_dir_path( __FILE__ ) . 'css/pdpa.css' ),
		'all'
	);
}
add_action( 'wp_enqueue_scripts', 'pdpa_script', 1 );

function display_matichon_pdpa() {
	if ( isset( $_COOKIE['pdpa_accept'] ) || ! empty( $_COOKIE['pdpa_accept'] ) ) {
		return;
	}

	$pdpa_options = get_option( 'custom_pdpa' );

	if ( $pdpa_options ) {
		if ( ! $pdpa_options['matichon_pdpa_active'] ) {
			return;
		}

		$background_color = isset( $pdpa_options['matichon_pdpa_background_color'] ) ? $pdpa_options['matichon_pdpa_background_color'] : '';

		if ( ! empty( $background_color ) ) {
			?>
			<style>
			#pdpa-popup {
				background: <?php echo esc_attr( $background_color ); ?>cc;
			}
			</style>
			<?php
		}
		?>
		<div id="pdpa-popup" class="matichon-pdpa-wrapper">
			<div class="wrapper">
				<button class="close-pdpa-popup" onclick="pdpaClose();">x</button>
				<div class="pdpa-wrapper pdpa-text">
					<?php echo wp_kses_post( get_option( 'custom_pdpa' )['matichon_pdpa_text'] ); ?>
				</div>
				<div class='pdpa-button'>
					<button type="button" class="pdpa-btn pdpa-btn-1" onclick="pdpaAccept(); return false;">
						<?php esc_html_e( 'ยอมรับ', 'matichon-pdpa' ); ?>
					</button>
				</div>
			</div>
		</div>

		<script type="text/javascript">
		(function() {
			var popupElement = document.querySelector("#pdpa-popup");

			if ( getCookie("pdpa_accept") ) {
				popupElement.classList.add('is-hide');
				popupElement.classList.remove('is-show');
			} else {
				popupElement.classList.add('is-show');
				popupElement.classList.remove('is-hide');
			}

			function getCookie(name) {
				var dc = document.cookie;
				var prefix = name + "=";
				var begin = dc.indexOf("; " + prefix);
				if (begin == -1) {
					begin = dc.indexOf(prefix);
					if (begin != 0) return null;
				} else {
					begin += 2;
					var end = document.cookie.indexOf(";", begin);
					if (end == -1) {
						end = dc.length;
					}
				}
				return decodeURI(dc.substring(begin + prefix.length, end));
			}

			function pdpaAccept() {
				var CookieDate = new Date;
				CookieDate.setFullYear(CookieDate.getFullYear() + 1);
				document.cookie = "pdpa_accept=1; expires=" + CookieDate.toGMTString() + ";";
				pdpaClose();
			}

			function pdpaClose() {
				popupElement.classList.add('is-hide');
				popupElement.classList.remove('is-show');
			}

			window.pdpaAccept = pdpaAccept;
			window.pdpaClose = pdpaClose;
		})();
		</script>
		<?php
	}
}
add_action( 'wp_body_open', 'display_matichon_pdpa' );

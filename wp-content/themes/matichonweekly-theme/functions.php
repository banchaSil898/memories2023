<?php

/**
 * Load the speed booster framework + theme specific files
 */

// load the deploy mode
require_once 'td_deploy_mode.php';

// load the config
require_once 'includes/td_config.php';
add_action( 'td_global_after', array( 'td_config', 'on_td_global_after_config' ), 9 ); // we run on 9 priority to allow plugins to updage_key our apis while using the default priority of 10

// Unixdev ---------------------
require_once 'includes/ud_ref_to_magazine/ud-ref-to-magazine.php';
// -----------------------------

// load the wp booster
require_once 'includes/wp_booster/td_wp_booster_functions.php';


require_once 'includes/td_css_generator.php';
require_once 'includes/shortcodes/td_misc_shortcodes.php';
require_once 'includes/widgets/td_page_builder_widgets.php'; // widgets

// Unixdev Book
require_once 'includes/ud-book/ud-book.php';

require_once 'functions-manage-columns.php';

require_once 'includes/dable.php';

/*
 * mobile theme css generator
 * in wp-admin the main theme is loaded and the mobile theme functions are not included
 * required in td_panel_data_source
 * @todo - look for a more elegant solution(ex. generate the css on request)
 */
require_once 'mobile/includes/td_css_generator_mob.php';


/*
 ----------------------------------------------------------------------------
 * Woo Commerce
 */

// breadcrumb
add_filter( 'woocommerce_breadcrumb_defaults', 'td_woocommerce_breadcrumbs' );
function td_woocommerce_breadcrumbs() {
	return array(
		'delimiter'   => ' <i class="td-icon-right td-bread-sep"></i> ',
		'wrap_before' => '<div class="entry-crumbs" itemprop="breadcrumb">',
		'wrap_after'  => '</div>',
		'before'      => '',
		'after'       => '',
		'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
	);
}

// use own pagination
if ( ! function_exists( 'woocommerce_pagination' ) ) {
	// pagination
	function woocommerce_pagination() {
		echo td_page_generator::get_pagination();
	}
}

// Override theme default specification for product 3 per row


// Number of product per page 8
function col_loop_shop_per_page( $cols ) {
	return 4;
}
add_filter( 'loop_shop_per_page', 'col_loop_shop_per_page' );

if ( ! function_exists( 'woocommerce_output_related_products' ) ) {
	// Number of related products
	function woocommerce_output_related_products() {
		woocommerce_related_products(
			array(
				'posts_per_page' => 4,
				'columns'        => 4,
				'orderby'        => 'rand',
			)
		); // Display 4 products in rows of 1
	}
}




/*
 ----------------------------------------------------------------------------
 * bbPress
 */
// change avatar size to 40px
function td_bbp_change_avatar_size( $author_avatar, $topic_id, $size ) {
	$author_avatar = '';
	if ( $size == 14 ) {
		$size = 40;
	}
	$topic_id = bbp_get_topic_id( $topic_id );
	if ( ! empty( $topic_id ) ) {
		if ( ! bbp_is_topic_anonymous( $topic_id ) ) {
			$author_avatar = get_avatar( bbp_get_topic_author_id( $topic_id ), $size );
		} else {
			$author_avatar = get_avatar( get_post_meta( $topic_id, '_bbp_anonymous_email', true ), $size );
		}
	}
	return $author_avatar;
}
add_filter( 'bbp_get_topic_author_avatar', 'td_bbp_change_avatar_size', 20, 3 );
add_filter( 'bbp_get_reply_author_avatar', 'td_bbp_change_avatar_size', 20, 3 );
add_filter( 'bbp_get_current_user_avatar', 'td_bbp_change_avatar_size', 20, 3 );



// add_action('shutdown', 'test_td');

function test_td() {
	if ( ! is_admin() ) {
		td_api_base::_debug_get_used_on_page_components();
	}

}


/**
 * tdStyleCustomizer.js is required
 */
if ( TD_DEBUG_LIVE_THEME_STYLE ) {
	add_action( 'wp_footer', 'td_theme_style_footer' );
		// new live theme demos
	function td_theme_style_footer() {
		?>
				<div id="td-theme-settings" class="td-live-theme-demos td-theme-settings-small">
					<div class="td-skin-body">
						<div class="td-skin-wrap">
							<div class="td-skin-container td-skin-buy"><a target="_blank" href="http://themeforest.net/item/newspaper/5489609?ref=tagdiv">BUY NEWSPAPER NOW!</a></div>
							<div class="td-skin-container td-skin-header">GET AN AWESOME START!</div>
							<div class="td-skin-container td-skin-desc">With easy <span>ONE CLICK INSTALL</span> and fully customizable options, our demos are the best start you'll ever get!!</div>
							<div class="td-skin-container td-skin-content">
								<div class="td-demos-list">
								<?php
								$td_demo_names = array();

								foreach ( td_global::$demo_list as $demo_id => $stack_params ) {
									$td_demo_names[ $stack_params['text'] ] = $demo_id;
									?>
										<div class="td-set-theme-style"><a href="<?php echo td_global::$demo_list[ $demo_id ]['demo_url']; ?>" class="td-set-theme-style-link td-popup td-popup-<?php echo $td_demo_names[ $stack_params['text'] ]; ?>" data-img-url="<?php echo td_global::$get_template_directory_uri; ?>/demos_popup/large/<?php echo $demo_id; ?>.jpg"><span></span></a></div>
									<?php } ?>
									<div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty1"></a></div>
									<div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty2"></a></div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="td-skin-scroll"><i class="td-icon-read-down"></i></div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="td-set-hide-show"><a href="#" id="td-theme-set-hide"></a></div>
					<div class="td-screen-demo" data-width-preview="380"></div>
				</div>
				<?php
	}
}

// td_demo_state::update_state("art_creek", 'full');

// print_r(td_global::$all_theme_panels_list);
function taxonomy_checklist_checked_ontop_filter( $args ) {

	$args['checked_ontop'] = false;
	return $args;

}
add_filter( 'wp_terms_checklist_args', 'taxonomy_checklist_checked_ontop_filter' );

// Unixdev MOD: add "publish on current time" button in edit post page"
function ud_post_submitbox_misc_actions() {
	global $post;
	$data = array();
	if ( 'publish' == $post->post_status ) {
		$data['udCurStampText']         = '&#9' . __( 'Published on: <b>current time</b>' );
		$data['forceCurtimeButtonText'] = __( 'Set to Current' );
	} else {
		$data['udCurStampText']         = '&#9' . __( 'Publish <b>immediately</b>' );
		$data['forceCurtimeButtonText'] = __( 'Immediately' );
	}
	if ( 'post' == $post->post_type ) {
		?>
		<div class="misc-pub-section hide-if-js">
			<input type="checkbox" id="ud_force_curtime" name="ud_force_curtime" value="force">Force Current Time
		</div>
		<script>
			jQuery(document).ready(function ($) {
				var timestampdiv = $('#timestampdiv');
				var saveTimestampButton = timestampdiv.find('.save-timestamp');
				var setCurTimestampButton = $('<a href="#edit_timestamp" class="ud-set-cur-timestamp hide-if-no-js button">Set to Current</a>');
				setCurTimestampButton.insertBefore(saveTimestampButton);
				setCurTimestampButton.click(function (e) {
					$('#aa').val($('#cur_aa').val());
					$('#mm').val($('#cur_mm').val());
					$('#jj').val($('#cur_jj').val());
					$('#hh').val($('#cur_hh').val());
					$('#mn').val($('#cur_mn').val());

					saveTimestampButton.click();
					e.preventDefault();
				});
			});
		</script>
		<?php
	}
}
add_action( 'post_submitbox_misc_actions', 'ud_post_submitbox_misc_actions' );

// Unixdev MOD: facebook instant article force author
function ud_force_fb_instant_article_author( $authors, $post_id ) {
	$author = $authors[0];

	if ( td_global::$is_ud_columnist_manager_activated ) {
		$terms = get_the_terms( $post_id, UDColumnistManager\ColumnistManager::TAXONOMY_NAME );
		if ( ! empty( $terms[0] ) ) {
			$term                 = $terms[0];
			$columnist_profile_id = get_term_meta( $term->term_id, 'ud_columnist_profile_id', true );
			$columnist_info_meta  = get_post_meta(
				$columnist_profile_id,
				UDColumnistManager\ColumnistManager::PROFILE_INFO_META_KEY,
				true
			);

			$author->ID            = 1;
			$author->display_name  = $term->name;
			$author->first_name    = $term->name;
			$author->last_name     = '';
			$author->user_login    = $term->name;
			$author->user_nicename = '';
			$author->user_email    = '';
			$author->user_url      = ! empty( $columnist_info_meta['info_url'] ) ? $columnist_info_meta['info_url'] : '';
			$author->bio           = '';

			return $authors;
		}
	}

	$ud_article_references = get_post_meta( $post_id, 'ud_article_references', true );
	if ( ! empty( $ud_article_references['author_name'] ) ) {
		$author->ID            = 1;
		$author->display_name  = $ud_article_references['author_name'];
		$author->first_name    = $ud_article_references['author_name'];
		$author->last_name     = '';
		$author->user_login    = $ud_article_references['author_name'];
		$author->user_nicename = '';
		$author->user_email    = '';
		$author->user_url      = isset( $ud_article_references['author_url'] ) ? $ud_article_references['author_url'] : '';
		$author->bio           = '';

		return $authors;
	}

	if ( ! empty( td_global::$ud_force_author ) and is_a( td_global::$ud_force_author, 'WP_User' ) ) {
		$user = td_global::$ud_force_author;

		$author->ID            = 1;
		$author->display_name  = $user->get( 'display_name' );
		$author->first_name    = $user->get( 'user_firstname' );
		$author->last_name     = $user->get( 'user_lastname' );
		$author->user_login    = $user->get( 'user_login' );
		$author->user_nicename = $user->get( 'user_nicename' );
		$author->user_email    = $user->get( 'user_email' );
		$author->user_url      = $user->get( 'user_url' );
		$author->bio           = $user->get( 'user_description' );

		return $authors;
	}

	return $authors;
}

add_filter( 'instant_articles_authors', 'ud_force_fb_instant_article_author', 10, 2 );


// Unixdev MOD: force WordPress seo plugin's article:author
function ud_force_wpseo_article_author( $author ) {
	if ( is_single() ) {
		$post = get_post();

		if ( td_global::$is_ud_columnist_manager_activated ) {
			$terms = get_the_terms( $post->ID, UDColumnistManager\ColumnistManager::TAXONOMY_NAME );
		}

		if ( ! empty( $terms[0] ) ) {
			// Use <meta name="author"> in hook_wp_head() in td_wp_booster_function.php instead;
			return '';
		} else {
			$ud_article_references = get_post_meta( $post->ID, 'ud_article_references', true );
			if ( ! empty( $ud_article_references['author_name'] ) ) {
				// Use <meta name="author"> in hook_wp_head() in td_wp_booster_function.php instead;
				return '';
			}
		}
	}

	// try get facebook contact (by tagdiv) on forced author
	if ( ! empty( td_global::$ud_force_author ) and is_a( td_global::$ud_force_author, 'WP_User' ) ) {
		$user         = td_global::$ud_force_author;
		$facebook_url = get_user_meta( $user->ID, 'facebook', true );
		if ( ! empty( $facebook_url ) ) {
			return $facebook_url;
		}
	}

	if ( class_exists( 'WPSEO_Options' ) ) {
		$options = WPSEO_Options::get_option( 'wpseo_social' );
		if ( ! empty( $options['facebook_site'] ) ) {
			return $options['facebook_site'];
		}
	}

	if ( ! empty( td_global::$ud_force_author ) and is_a( td_global::$ud_force_author, 'WP_User' ) ) {
		$user = td_global::$ud_force_author;
		return $user->get( 'display_name' );
	}

	return $author;
}

add_filter( 'wpseo_opengraph_author_facebook', 'ud_force_wpseo_article_author' );

// Unixdev MOD: hide category on tdc_hide_on_post option ( override WordPress seo)
function ud_post_link_category( $category, $categories, $post ) {

	$td_post_theme_settings = td_util::get_post_meta_array( $post->ID, 'td_post_theme_settings' );
	if ( ! empty( $td_post_theme_settings['td_primary_cat'] ) ) {
		$cat = get_category( $td_post_theme_settings['td_primary_cat'] );
		if ( ! empty( $cat ) and ! is_wp_error( $cat ) ) {
			return $cat;
		}
	}

	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$primary_term_object = new WPSEO_Primary_Term( 'category', $post->ID );
		$cat_id              = $primary_term_object->get_primary_term();
		$cat                 = get_category( $cat_id );

		if ( ! empty( $cat ) and ! is_wp_error( $cat ) and $cat->name != TD_FEATURED_CAT and 'hide' !== td_util::get_category_option( $cat_id, 'tdc_hide_on_post' )
		) {
			return $cat;
		}
	}

	foreach ( $categories as $cat ) {
		if ( $cat->name != TD_FEATURED_CAT and 'hide' !== td_util::get_category_option( $cat->cat_ID, 'tdc_hide_on_post' ) ) { // ignore the featured category name and hide category
			return $cat;
		}
	}

	return $category;
}
add_filter( 'post_link_category', 'ud_post_link_category', 11, 3 );

function ud_disable_ajax_search() {
	td_js_buffer::add_variable( 'udDisableAjaxSearch', 'yes' === td_util::get_option( 'ud_disable_ajax_search' ) );
}
add_action( 'wp_head', 'ud_disable_ajax_search' );


function ud_render_after_body_custom_html() {
	$uds_after_body_custom_html = stripslashes( td_util::get_option( 'uds_after_body_custom_html' ) );
	if ( ! empty( $uds_after_body_custom_html ) ) {
		echo $uds_after_body_custom_html;
	}
}
add_action( 'ud_after_body_tag', 'ud_render_after_body_custom_html' );

// Unixdev MOD: Buddhist YEAR
function ud_get_the_date_buddhist_year( $formated_date_str, $d, $post ) {

	if ( 'th' !== get_locale() ) {
		return $formated_date_str;
	}

	if ( '' == $d ) {
		$d = get_option( 'date_format' );
	}

	$post_date = $post->post_date;
	$timestamp = strtotime( $post_date );

	$d = td_util::insert_buddhist_year_to_date_format( $d, $timestamp );

	$the_date = mysql2date( $d, $post_date );

	return $the_date;
}

add_filter( 'get_the_date', 'ud_get_the_date_buddhist_year', 10, 3 );

function ud_get_the_time_buddhist_year( $formated_date_str, $d, $post ) {

	if ( 'th' !== get_locale() ) {
		return $formated_date_str;
	}

	if ( '' == $d ) {
		$d = get_option( 'time_format' );
	}

	$post_date = $post->post_date;
	$timestamp = strtotime( $post_date );

	$d = td_util::insert_buddhist_year_to_date_format( $d, $timestamp );

	$the_date = get_post_time( $d, false, $post, true );

	return $the_date;
}

add_filter( 'get_the_time', 'ud_get_the_time_buddhist_year', 10, 3 );

// -------------------------

function ud_header_ads_generator() {
	$ud_technologychaoban_logo_url = td_util::get_option( 'ud_technologychaoban_logo_url', '#' );
	$ud_silpa_mag_logo_url         = td_util::get_option( 'ud_silpa_mag_logo_url', '#' );
	$ud_sentangsedtee_logo_url     = td_util::get_option( 'ud_sentangsedtee_logo_url', '#' );
	?>
	<div class="td-pb-row ud-header-ads-wrap">
		<div class="td-pb-span12">
			<?php
			if ( td_util::is_ad_spot_enabled( td_global::$ud_under_menu_ad_spot_id ) ) {
				$ud_under_menu_ad_title = td_util::get_option( 'tds_' . td_global::$ud_under_menu_ad_spot_id . '_title' );
				echo td_global_blocks::get_instance( 'td_block_ad_box' )->render(
					array(
						'spot_id'    => td_global::$ud_under_menu_ad_spot_id,
						'spot_title' => $ud_under_menu_ad_title,
					)
				);
			}
			?>
		</div>
	</div>
	<?php
	/*
	<div class="td-pb-row ud-header-ads-wrap">
		<div class="td-pb-span4">
			<div class="ud-matichon-icon-group">
				<a href="<?php echo esc_url( $ud_technologychaoban_logo_url ); ?>" target="_blank" class="ud-icon ud-icon-technologychaoban">
					<span class="path1"></span><span class="path2"></span>
				</a>
				<a href="<?php echo esc_url( $ud_silpa_mag_logo_url ); ?>" target="_blank" class="ud-icon ud-icon-silpa-mag">
					<span class="path1"></span><span class="path2"></span>
				</a>
				<a href="<?php echo esc_url( $ud_sentangsedtee_logo_url ); ?>" target="_blank" class="ud-icon ud-icon-sentangsedtee">
					<span class="path1"></span><span class="path2"></span>
				</a>
			</div>
		</div>
		<div class="td-pb-span8">
			<div class="td-header-sp-recs">
				<?php locate_template('parts/header/ads.php', true); ?>
			</div>
		</div>
	</div>
	<?php
	*/
}

add_filter( 'ud_header_ads', 'ud_header_ads_generator' );

function ud_get_global_ad_ids() {
	if ( is_page() ) {
		$post = get_post();
		if ( 'page-pagebuilder-latest.php' === get_post_meta( $post->ID, '_wp_page_template', true ) ) {
			$ud_td_page_option = td_util::get_post_meta_array( $post->ID, 'td_homepage_loop' );
		} else {
			$ud_td_page_option = td_util::get_post_meta_array( $post->ID, 'td_page' );
		}

		if ( ! empty( $ud_td_page_option['ud_header_ad_spot_id'] ) ) {
			td_global::$ud_header_ad_spot_id = $ud_td_page_option['ud_header_ad_spot_id'];
		}

		if ( ! empty( $ud_td_page_option['ud_footer_ad_spot_id'] ) ) {
			td_global::$ud_footer_ad_spot_id = $ud_td_page_option['ud_footer_ad_spot_id'];
		}

		if ( ! empty( $ud_td_page_option['ud_takeover_ad_spot_id'] ) ) {
			td_global::$ud_takeover_ad_spot_id = $ud_td_page_option['ud_takeover_ad_spot_id'];
		}

		if ( ! empty( $ud_td_page_option['ud_under_menu_ad_spot_id'] ) ) {
			td_global::$ud_under_menu_ad_spot_id = $ud_td_page_option['ud_under_menu_ad_spot_id'];
		}
	} elseif ( is_single() ) {
		td_global::$ud_header_ad_spot_id     = 'header';
		td_global::$ud_footer_ad_spot_id     = 'ud_footer_sticky_post_ad';
		td_global::$ud_takeover_ad_spot_id   = 'ud_takeover_post_ad';
		td_global::$ud_under_menu_ad_spot_id = 'ud_under_menu_post_ad';
	} elseif ( is_category() or is_tag() ) {
		td_global::$ud_header_ad_spot_id     = 'ud_b1x_cat_ad';
		td_global::$ud_footer_ad_spot_id     = 'ud_footer_sticky_cat_ad';
		td_global::$ud_takeover_ad_spot_id   = 'ud_takeover_cat_ad';
		td_global::$ud_under_menu_ad_spot_id = 'ud_under_menu_cat_ad';
	} else {
		td_global::$ud_header_ad_spot_id     = 'header';
		td_global::$ud_footer_ad_spot_id     = 'ud_footer_sticky_post_ad';
		td_global::$ud_takeover_ad_spot_id   = 'ud_takeover_post_ad';
		td_global::$ud_under_menu_ad_spot_id = 'ud_under_menu_post_ad';
	}
}
add_action( 'wp_head', 'ud_get_global_ad_ids' );

function ud_render_aiqua_head() {
	echo '
    <script type="text/javascript">
        !function(q,g,r,a,p,h,js) {
            if(q.qg)return;
            js = q.qg = function() {
            js.callmethod ? js.callmethod.call(js, arguments) : js.queue.push(arguments);
            };
            js.queue = [];
            p=g.createElement(r);p.async=!0;p.src=a;h=g.getElementsByTagName(r)[0];
            h.parentNode.insertBefore(p,h);
        } (window,document,"script","https://cdn.qgr.ph/qgraph.73ad50a3dfb780a32ec4.js");
    </script>';
}
add_action( 'wp_head', 'ud_render_aiqua_head', 10, 1 );

function ud_render_aiqua_tracking() {
	$qg = array();
	if ( is_single() ) {
		$post     = get_post();
		$category = get_category( td_global::get_primary_category_id() );

		$content_type = 'article';
		if (
			get_post_format( $post->ID ) == 'video'
			or ! empty( td_util::get_post_meta_array( $post->ID, 'td_post_video' )['td_video'] )
			or $category->slug === 'clips'
		) {
			$content_type = 'video';
		}

		$qg = array(
			'event',
			'content_viewed',
			array(
				'content_type'     => $content_type,
				'content_id'       => $post->ID,
				'content_category' => $category->slug,
				'content_name'     => $post->post_title,
				'content_url'      => get_permalink(),
				'content_author'   => $post->post_author,
			),
		);
	} elseif ( is_category() ) {
		$category = get_category( get_query_var( 'cat' ) );

		$qg = array(
			'event',
			'category_viewed',
			array(
				'category_name' => $category->slug,
			),
		);
	} elseif ( is_search() ) {
		$qg = array(
			'event',
			'searched',
			array(
				'search_term' => get_search_query(),
			),
		);
	} elseif ( is_page(
		array(
			'column',
			'columnists',
			'intrend',
			'cartoon',
			'qoute',
			'intrend',
			'magazine',
			'in-depth',
			'culture',
			'religion',
			'art',
			'%e0%b9%84%e0%b8%a5%e0%b8%9f%e0%b9%8c%e0%b8%aa%e0%b9%84%e0%b8%95%e0%b8%a5%e0%b9%8c',
		)
	) ) {
		// category page
		$post = get_post();
		$qg   = array(
			'event',
			'category_viewed',
			array(
				'category_name' => urldecode( $post->post_name ),
			),
		);
	} elseif ( is_archive() ) {
		$qg = array(
			'event',
			'category_viewed',
			array(
				'category_name' => get_query_var( 'term' ),
			),
		);
	}

	if ( ! empty( $qg ) ) {
		echo '<script>qg(' . substr( json_encode( $qg ), 1, -1 ) . ')</script>' . PHP_EOL;
	}
}
add_action( 'wp_head', 'ud_render_aiqua_tracking', 10, 1 );

function ud_render_aiqua_social_sharing_tracking() {
	if ( is_single() ) {
		$post     = get_post();
		$category = get_category( td_global::get_primary_category_id() );

		$content_type = 'article';
		if (
			get_post_format( $post->ID ) == 'video'
			or ! empty( td_util::get_post_meta_array( $post->ID, 'td_post_video' )['td_video'] )
			or $category->slug === 'clips'
		) {
			$content_type = 'video';
		}
		?>
	<script>
		(function() {
			var facebookButtons = document.querySelectorAll(".td-social-sharing-buttons.td-social-facebook");
			for (var i = 0; i < facebookButtons.length; i++) {
				facebookButtons[i].addEventListener("click", function() {
					qg("event", "content_shared", {
						social_network: "Facebook",
						content_category: "<?php echo $category->slug; ?>",
						content_type: "<?php echo $content_type; ?>"
					});
				});
			}

			var twitterButtons = document.querySelectorAll(".td-social-sharing-buttons.td-social-twitter");
			for (var i = 0; i < twitterButtons.length; i++) {
				twitterButtons[i].addEventListener("click", function() {
					qg("event", "content_shared", {
						social_network: "Twitter",
						content_category: "<?php echo $category->slug; ?>",
						content_type: "<?php echo $content_type; ?>"
					});
				});
			}

			var googleButtons = document.querySelectorAll(".td-social-sharing-buttons.td-social-google");
			for (var i = 0; i < googleButtons.length; i++) {
				googleButtons[i].addEventListener("click", function() {
					qg("event", "content_shared", {
						social_network: "Google",
						content_category: "<?php echo $category->slug; ?>",
						content_type: "<?php echo $content_type; ?>"
					});
				});
			}

			var lineButtons = document.querySelectorAll(".td-social-sharing-buttons.ud-social-sharing-line");
			for (var i = 0; i < lineButtons.length; i++) {
				lineButtons[i].addEventListener("click", function() {
					qg("event", "content_shared", {
						social_network: "Line",
						content_category: "<?php echo $category->slug; ?>",
						content_type: "<?php echo $content_type; ?>"
					});
				});
			}
		})();
	</script>
		<?php
	}
}
add_action( 'wp_footer', 'ud_render_aiqua_social_sharing_tracking', 10, 1 );

function add_unixdev_css() {
	wp_enqueue_style( 'unixdev', get_theme_file_uri( 'unixdev.css' ), array(), filemtime( get_theme_file_path( 'unixdev.css' ) ), 'all' );
}
add_action( 'wp_enqueue_scripts', 'add_unixdev_css' );

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

function custom_search_query( $query ) {
	if ( $query->is_search ) {
		$search_terms = explode( ' ', $query->query_vars['s'] );
		if ( count( $search_terms ) > 3 ) {
			$search_terms = array_slice( $search_terms, 0, 3 );
			$query->set( 's', implode( ' ', $search_terms ) );
		}
		if ( strlen( $query->query_vars['s'] ) > 100 ) {
			$search_terms = substr( $query->query_vars['s'], 0, 100 );
			$query->set( 's', $search_terms );
		}
	}
}
add_action( 'pre_get_posts', 'custom_search_query' );

add_filter( 'gtm4wp_compile_datalayer', 'modified_datalayer' );
function modified_datalayer( $dataLayer ) {
	$_post_tags = get_the_tags();
	if ( $_post_tags ) {
		$dataLayer['pageAttributes'] = array();
		foreach ( $_post_tags as $_one_tag ) {
			$dataLayer['pageAttributes'][] = $_one_tag->name;
		}
	}
	return $dataLayer;
}

function add_google_search_settings_menu() {
	// Add a submenu under the "Settings" menu
	add_options_page(
		'Google Search Settings', // Page title
		'Google Search Settings', // Menu title
		'manage_options', // Capability required to access
		'google-search-settings', // Menu slug
		'google_search_settings_page' // Callback function to display the page
	);
}
add_action( 'admin_menu', 'add_google_search_settings_menu' );

function google_search_settings_page() {
	// Content of your settings page goes here
	echo '<div class="wrap">';
	echo '<h2>Google Search Settings</h2>';

	// Check if the form has been submitted
	if ( isset( $_POST['submit'] ) ) {
		// Verify the nonce
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'google_search_settings_nonce' ) ) {
			die( 'Security check failed.' );
		}

		// Save the 'cx' value in the database or perform any other actions
		$cx_value = sanitize_text_field( $_POST['cx'] );
		update_option( 'google_search_cx', $cx_value );
		echo '<div class="updated"><p>Settings saved.</p></div>';
	}

	// Retrieve the 'cx' value from the database
	$cx_value = get_option( 'google_search_cx' );

	// Display the settings form within a table
	echo '<form method="post" action="">';
	echo '<table class="form-table">';
	echo '<tr>';
	echo '<th scope="row"><label for="cx">CX:</label></th>';
	echo '<td><input type="text" id="cx" name="cx" value="' . esc_attr( $cx_value ) . '" /></td>';
	echo '</tr>';
	echo '</table>';

	// Add a nonce field for security
	wp_nonce_field( 'google_search_settings_nonce', '_wpnonce' );

	echo '<p class="submit"><input type="submit" name="submit" class="button button-primary" value="Save Settings" /></p>';
	echo '</form>';

	echo '</div>';
}

function check_page_query_var() {
	// Check if 'page' query var is set and equals 0
	if ( is_archive() ) {
		if ( get_query_var( 'paged' ) == '0' ) {
			global $wp_query;
			if ( null === get_queried_object() ) {
				$wp_query->set_404(); // Set the global query to 404 not found
				status_header( 404 );  // Send a 404 status header
				get_template_part( 404 ); // Show the 404 error page
				exit;
			}
		}
	}
}
add_action( 'template_redirect', 'check_page_query_var' );

require get_theme_file_path( 'includes/memories/shortcode.php' );
/* Load Weekly Memories Function */
require_once 'includes/memories/function.php';
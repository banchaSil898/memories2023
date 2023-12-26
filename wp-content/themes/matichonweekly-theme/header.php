<!doctype html >
<!--[if IE 8]>    <html class="ie8" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta charset="<?php bloginfo( 'charset' );?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php
    wp_head(); /** we hook up in wp_booster @see td_wp_booster_functions::hook_wp_head */
    ?>
    <?php
    if ( is_single() ) {
        $post_meta = td_util::get_post_meta_array( get_the_ID(), 'td_post_theme_settings' );
        if ( empty( $post_meta['ud_disabled_ads'] )
             || ! is_array( $post_meta['ud_disabled_ads'] )
             || ! in_array( 'ud_taboola_ad', $post_meta['ud_disabled_ads'] ) ) {

            $ud_ad_infos = td_util::get_td_ads( 'ud_taboola_ad' );
            if ( ! empty( $ud_ad_infos['ud_taboola_ad'] ) && ! empty( $ud_ad_infos['ud_taboola_ad']['init_script'] ) ) {
                echo stripslashes( $ud_ad_infos['ud_taboola_ad']['init_script'] );
            }
        }
    }
    ?>

	
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-5544713714875469",
          enable_page_level_ads: true
     });
</script>
	
</head>

<body <?php body_class() ?> itemscope="itemscope" itemtype="<?php echo td_global::$http_or_https?>://schema.org/WebPage">
    <?php wp_body_open(); ?>
    <?php do_action('ud_after_body_tag'); //Unixdev MOD ?>

    <?php /* scroll to top */?>
    <div class="td-scroll-up"><i class="td-icon-menu-up"></i></div>
    
    <?php locate_template('parts/menu-mobile.php', true);?>
    <?php locate_template('parts/search.php', true);?>
    
    
    <div id="td-outer-wrap" class="td-theme-wrap">
    <?php //this is closing in the footer.php file ?>

        <?php
        /*
         * loads the header template set in Theme Panel -> Header area
         * the template files are located in ../parts/header
         */
        td_api_header_style::_helper_show_header();

        do_action('td_wp_booster_after_header'); //used by unique articles


<!-- Instagram -->

<?php if (td_util::get_option('tds_footer_instagram') == 'show') { ?>

<div class="td-main-content-wrap td-footer-instagram-container td-container-wrap <?php echo td_util::get_option('td_full_footer_instagram'); ?>">
    <?php
    //get the instagram id from the panel
    $tds_footer_instagram_id = td_instagram::strip_instagram_user(td_util::get_option('tds_footer_instagram_id'));
    ?>

    <div class="td-instagram-user">
        <h4 class="td-footer-instagram-title">
            <?php echo  __td('Follow us on Instagram', TD_THEME_NAME); ?>
            <a class="td-footer-instagram-user-link" href="https://www.instagram.com/<?php echo $tds_footer_instagram_id ?>" target="_blank">@<?php echo $tds_footer_instagram_id ?></a>
        </h4>
    </div>

    <?php
    //get the other panel seetings
    $tds_footer_instagram_nr_of_row_images = intval(td_util::get_option('tds_footer_instagram_on_row_images_number'));
    $tds_footer_instagram_nr_of_rows = intval(td_util::get_option('tds_footer_instagram_rows_number'));
    $tds_footer_instagram_img_gap = td_util::get_option('tds_footer_instagram_image_gap');
    $tds_footer_instagram_header = td_util::get_option('tds_footer_instagram_header_section');

    //show the insta block
    echo td_global_blocks::get_instance('td_block_instagram')->render(
        array(
            'instagram_id' => $tds_footer_instagram_id,
            'instagram_header' => /*td_util::get_option('tds_footer_instagram_header_section')*/ 1,
            'instagram_images_per_row' => $tds_footer_instagram_nr_of_row_images,
            'instagram_number_of_rows' => $tds_footer_instagram_nr_of_rows,
            'instagram_margin' => $tds_footer_instagram_img_gap
        )
    );

    ?>
</div>

<?php } ?>


<!-- Footer -->
<?php
if (td_util::get_option('tds_footer') != 'no') {
    td_api_footer_template::_helper_show_footer();
}
?>

<?php
// Unixdev MOD: add sticky ads to footer
if ( td_util::is_ad_spot_enabled( td_global::$ud_footer_ad_spot_id ) ) {
    $ud_ads     = td_util::get_td_ads( td_global::$ud_footer_ad_spot_id );
    $ad_options = $ud_ads[ td_global::$ud_footer_ad_spot_id ];
    $ud_sticky_ad_info = array();
    array_push( $ud_sticky_ad_info, array(
        'minWidth' => 0,
        'maxWidth' => 768,
        'limitHeight' => ( ! empty( $ad_options['p_ud_limit_height'] ) ) ? esc_js( $ad_options['p_ud_limit_height'] ) : 0,
        'enable' => ( 'yes' === $ad_options['disable_p'] ) ? false : true
    ) );
    array_push( $ud_sticky_ad_info, array(
        'minWidth' => 768,
        'maxWidth' => 1019,
        'limitHeight' => ( ! empty( $ad_options['tp_ud_limit_height'] ) ) ? esc_js( $ad_options['tp_ud_limit_height'] ) : 0,
        'enable' => ( 'yes' === $ad_options['disable_tp'] ) ? false : true
    ) );
    array_push( $ud_sticky_ad_info, array(
        'minWidth' => 1019,
        'maxWidth' => 1140,
        'limitHeight' => ( ! empty( $ad_options['tl_ud_limit_height'] ) ) ? esc_js( $ad_options['tl_ud_limit_height'] ) : 0,
        'enable' => ( 'yes' === $ad_options['disable_tl'] ) ? false : true
    ) );
    array_push( $ud_sticky_ad_info, array(
        'minWidth' => 1140,
        'maxWidth' => null,
        'limitHeight' => ( ! empty( $ad_options['m_ud_limit_height'] ) ) ? esc_js( $ad_options['m_ud_limit_height'] ) : 0,
        'enable' => ( 'yes' === $ad_options['disable_m'] ) ? false : true
    ) );

    $ud_footer_classes = array();
    $ad_options_disable = array(
        'disable_m' => 'td-rec-hide-on-m',
        'disable_tl' => 'td-rec-hide-on-tl',
        'disable_tp' => 'td-rec-hide-on-tp',
        'disable_p' => 'td-rec-hide-on-p',
    );

    foreach ( $ad_options_disable as $ad_options_item => $ad_options_class ) {
        if ( 'yes' === $ad_options[$ad_options_item] ) {
            array_push( $ud_footer_classes, $ad_options_class );
        }
    }

    $ud_footer_classes = implode( ' ', $ud_footer_classes );

    $buffy = '<div class="ud-footer-wrapper ' . $ud_footer_classes . '">';
    $buffy .= '<div class="ud-sticky-bottom-ads-wrap-2">';
    $buffy .= '<button type="button" class="trigger-sticky-ads -active" onclick="triggerStickyAds(this)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"/></svg></button>';
    $buffy .= '<div class="ud-sticky-bottom-ads-2 -active">';
    $buffy .= '<script type="text/javascript">';
    $buffy .= 'function triggerStickyAds(element) {
        const udStickyBottomAds2 = document.querySelector(".ud-sticky-bottom-ads-2");
        udStickyBottomAds2.classList.toggle("-active");
        element.classList.toggle("-active");
    }';
    // have to specify default height otherwise not show
    $buffy .= 'var ud_sticky_ad_info = ' . json_encode( $ud_sticky_ad_info ) . ';';
    $buffy .= '</script>';
    $buffy .= td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => td_global::$ud_footer_ad_spot_id));
    $buffy .= '</div>';
    $buffy .= '</div>';
    $buffy .= '</div>';
    echo $buffy;
}
?>


<?php
// Unixdev MOD: takeover ads
if ( ! empty( td_global::$ud_takeover_ad_spot_id ) && td_util::is_ad_spot_enabled( td_global::$ud_takeover_ad_spot_id ) ) {
    $uds_takeover_ad_title = td_util::get_option( 'tds_' . td_global::$ud_takeover_ad_spot_id . '_title' );
    echo td_global_blocks::get_instance( 'td_block_ad_box' )->render( array( 'spot_id' => td_global::$ud_takeover_ad_spot_id, 'spot_title' => $uds_takeover_ad_title ) );
}
//-----------
?>

<!-- Sub Footer -->
<?php if (td_util::get_option('tds_sub_footer') != 'no') { ?>
    <div class="td-sub-footer-container td-container-wrap <?php echo td_util::get_option('td_full_footer'); ?>">
        <div class="td-container">
            <div class="td-pb-row">
                <div class="td-pb-span td-sub-footer-menu">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer-menu',
                            'menu_class'=> 'td-subfooter-menu',
                            'fallback_cb' => 'td_wp_footer_menu'
                        ));

                        //if no menu
                        function td_wp_footer_menu() {
                            //do nothing?
                        }
                        ?>
                </div>

                <div class="td-pb-span td-sub-footer-copy">
                    <?php
                    $tds_footer_copyright = stripslashes(td_util::get_option('tds_footer_copyright'));
                    $tds_footer_copy_symbol = td_util::get_option('tds_footer_copy_symbol');

                    //show copyright symbol
                    if ($tds_footer_copy_symbol == '') {
                        echo '&copy; ';
                    }

                    echo $tds_footer_copyright;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!--close td-outer-wrap-->

<?php wp_footer(); ?>
<?php
//Unixdev MOD: taboola ads footer
if ( is_single() ) {
    $post_meta = td_util::get_post_meta_array( get_the_ID(), 'td_post_theme_settings' );
    if ( empty( $post_meta['ud_disabled_ads'] ) || ! is_array( $post_meta['ud_disabled_ads'] ) || ! in_array( 'ud_taboola_ad', $post_meta['ud_disabled_ads'] ) ) {

        $ud_ad_infos = td_util::get_td_ads( 'ud_taboola_ad' );
        if ( ! empty( $ud_ad_infos['ud_taboola_ad'] ) && ! empty( $ud_ad_infos['ud_taboola_ad']['init_script'] )) {
            echo '<script type="text/javascript">';
            echo 'window._taboola = window._taboola || [];';
            echo '_taboola.push({flush: true});';
            echo '</script>';
        }
    }
}
//------------
?>

</body>
</html>

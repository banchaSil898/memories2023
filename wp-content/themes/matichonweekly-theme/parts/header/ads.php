<div class="td-header-rec-wrap">
    <?php
    $tds_header_ad_title = td_util::get_option('tds_header_title');

    //Unixdev MOD: ----------
    if ( td_util::is_ad_spot_enabled( td_global::$ud_header_ad_spot_id ) ) {
        $uds_header_ad_title = td_util::get_option( 'tds_' . td_global::$ud_header_ad_spot_id . '_title' );
        echo td_global_blocks::get_instance( 'td_block_ad_box' )->render( array('spot_id' => td_global::$ud_header_ad_spot_id, 'spot_title' => $tds_header_ad_title ) );
    }
    //-----------
    ?>

</div>
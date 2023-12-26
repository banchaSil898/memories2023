

<div class="ud-header-social-widget">
    <?php

    //get the socials that are set by user
    $td_get_social_network = td_util::get_option( 'td_social_networks' );

    if ( ! empty( $td_get_social_network ) ) {
        foreach ( $td_get_social_network as $social_id => $social_link ) {
            if ( ! empty( $social_link ) ) {
                echo td_social_icons::get_icon( $social_link, $social_id, true, false, true );
            }
        }
    }
    ?>
</div>
<?php
// show the date and time if needed
//if (td_util::get_option('tds_data_top_menu') == 'show') {
$tds_data_time = td_util::get_option( 'tds_data_time_format' );
if ( $tds_data_time == '' ) {
    $tds_data_time = get_option( 'date_format' ); //Unixdev MOD: use wordpress setting as a default
}
// if the js date is enabled hide the default one
$td_date_visibility = '';
if ( td_util::get_option( 'tds_data_js' ) == 'true' ) {
    $td_date_visibility = 'style="visibility:hidden;"';
}
?>
<div class="td_data_time">
    <div <?php echo $td_date_visibility ?>>

        <?php
        //Unixdev MOD
        if ( 'th' === get_locale() ) {
            $tds_data_time = td_util::insert_buddhist_year_to_date_format( $tds_data_time );
        }
        //-----
        ?>

        <?php echo date_i18n( stripslashes( $tds_data_time ) ); ?>

    </div>
</div>
<?php


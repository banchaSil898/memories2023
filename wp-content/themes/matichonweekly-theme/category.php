<?php
/*  ----------------------------------------------------------------------------
    the blog index template
 */

get_header();

global $loop_module_id, $loop_sidebar_position;

$current_category_id = get_query_var('cat');
$current_category_obj = get_category($current_category_id);


//read the per category setting
$tdc_layout = td_util::get_category_option($current_category_id, 'tdc_layout');//swich by RADU A, get_tax_meta($cur_cat_id, 'tdc_layout');
$tdc_sidebar_pos = td_util::get_category_option($current_category_id, 'tdc_sidebar_pos');////swich by RADU A,  get_tax_meta($cur_cat_id, 'tdc_sidebar_pos');

// sidebar position used to align the breadcrumb on sidebar left + sidebar first on mobile issue
$td_sidebar_position = '';
if($loop_sidebar_position == 'sidebar_left') {
	$td_sidebar_position = 'td-sidebar-left';
}

//set the template id, used to get the template specific settings
$template_id = 'category';

//prepare the loop variables

$loop_module_id = td_util::get_option('tds_' . $template_id . '_page_layout', 1); //module 1 is default
$loop_sidebar_position = td_util::get_option('tds_' . $template_id . '_sidebar_pos'); //sidebar right is default (empty)

//override the category global template with the category specific settings
if (!empty($tdc_layout)) {
    $loop_module_id = $tdc_layout;
}

if (!empty($tdc_sidebar_pos)) {
    $loop_sidebar_position = $tdc_sidebar_pos;
}

// sidebar position used to align the breadcrumb on sidebar left + sidebar first on mobile issue
$td_sidebar_position = '';
if($loop_sidebar_position == 'sidebar_left') {
	$td_sidebar_position = 'td-sidebar-left';
}

// make the category pulldown filter list to be equal
td_js_buffer::add_to_footer (
    'jQuery().ready(function() {' . "\r\n" .
    'var pulldown_size = jQuery(".td-category-pulldown-filter:first").width();' . "\r\n" .
    'if (pulldown_size > 113) { jQuery(".td-category-pulldown-filter .td-pulldown-filter-list").css({"min-width": pulldown_size, "border-top": "1px solid #444"}); }' . "\r\n" .
    '});'
);

//Unixdev MOD: force column count
global $ud_force_column_number;

//read the per category setting
$udc_force_column_number = intval(td_util::get_category_option($current_category_id, 'udc_force_column_number'));

$ud_force_column_number = intval(td_util::get_option('uds_' . $template_id . '_force_column_number'));
if ( ! empty( $udc_force_column_number ) ) {
    if ( -1 === $udc_force_column_number ) {
        // ignore global option
        $ud_force_column_number = '';
    } else {
        $ud_force_column_number = $udc_force_column_number;
    }
}

//----------------------------

//Unixdev MOD: Custom css

$udc_custom_css_mode = td_util::get_category_option($current_category_id, 'udc_custom_css_mode');

$ud_custom_css = td_util::get_option('uds_' . $template_id . '_custom_css');

if ( ! empty( $udc_custom_css_mode ) ) {
    if ( 'none' === $udc_custom_css_mode ) {
        $ud_custom_css = '';
    } elseif ( 'override' === $udc_custom_css_mode ) {
        $ud_custom_css = td_util::get_category_option($current_category_id, 'udc_custom_css');
    }
}

//----------------------------

//Unixdev MOD: enable/disable loop wrap
global $ud_loop_wrap_mode;

$udc_loop_wrap_mode = td_util::get_category_option($current_category_id, 'udc_loop_wrap_mode');

$ud_loop_wrap_mode = td_util::get_option('uds_' . $template_id . '_loop_wrap_mode');

if ( ! empty( $udc_loop_wrap_mode ) ) {
    if ( 'disable' === $udc_loop_wrap_mode ) {
        $ud_loop_wrap_mode = 'disable';
    } elseif ( 'enable' === $udc_loop_wrap_mode ) {
        $ud_loop_wrap_mode = '';
    }
}

//----------------------------

?>



<?php //td_api_category_template::_helper_show_category_template() ?>
<?php //td_api_category_top_posts_style::_helper_show_category_top_posts_style() //Unixdev MOD: move into row ?>

<div class="td-main-content-wrap td-container-wrap">
    <div class="td-container">
        <div class="td-crumb-container">
            <?php
            //Unixdev MOD
            echo td_page_generator::get_category_breadcrumbs($current_category_obj);
            ?>
        </div>
        <?php apply_filters( 'ud_header_ads', '' ) //Unixdev MOD ?>

        <!-- content -->
        <div class="td-pb-row">
            <?php
                switch ($loop_sidebar_position) {

                    default: //default: sidebar right
                        ?>
                            <div class="td-pb-span8 td-main-content">
                                <div class="td-ss-main-content <?php echo esc_attr($ud_custom_css)?>">
                                    <?php
                                    // Unixdev MOD
                                    if ( ( empty( $paged ) or $paged < 2 ) and 'td_category_top_posts_style_disable' !== td_api_category_top_posts_style::_helper_get_active_id()) {
                                        td_api_category_top_posts_style::_helper_show_category_top_posts_style();
                                        if ( td_util::is_ad_spot_enabled( 'ud_b4x_cat_ad' ) ) {
                                            echo td_global_blocks::get_instance( 'td_block_ad_box' )->render( array( 'spot_id' => 'ud_b4x_cat_ad' ) );
                                        }
                                    }
                                    //--------------
                                    ?>
                                    <?php
                                    //Unixdev MOD
                                    echo '<h1 class="entry-title td-page-title"><span>'.$current_category_obj->name.'</span></h1>';
                                    locate_template('loop.php', true);
                                    if (td_util::is_ad_spot_enabled('ud_b5x_cat_ad')) {
                                        echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b5x_cat_ad'));
                                    }
                                    //-----------
                                    ?>
                                    <?php td_page_generator::get_pagination(); ?>
                                </div>
                            </div>

                            <div class="td-pb-span4 td-main-sidebar">
                                <div class="td-ss-main-sidebar">
                                    <?php get_sidebar(); ?>
                                </div>
                            </div>
                        <?php
                        break;


                    case 'sidebar_left':
                        ?>
                        <div class="td-pb-span8 td-main-content <?php echo $td_sidebar_position; ?>-content">
                            <div class="td-ss-main-content <?php echo esc_attr($ud_custom_css)?>">
                                <?php
                                // Unixdev MOD
                                if ( ( empty( $paged ) or $paged < 2 ) and 'td_category_top_posts_style_disable' !== td_api_category_top_posts_style::_helper_get_active_id()) {
                                    td_api_category_top_posts_style::_helper_show_category_top_posts_style();
                                    if ( td_util::is_ad_spot_enabled( 'ud_b4x_cat_ad' ) ) {
                                        echo td_global_blocks::get_instance( 'td_block_ad_box' )->render( array( 'spot_id' => 'ud_b4x_cat_ad' ) );
                                    }
                                }
                                //--------------
                                ?>
                                <?php
                                //Unixdev MOD
                                echo '<h1 class="entry-title td-page-title"><span>'.$current_category_obj->name.'</span></h1>';
                                locate_template('loop.php', true);
                                if (td_util::is_ad_spot_enabled('ud_b5x_cat_ad')) {
                                    echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b5x_cat_ad'));
                                }
                                //-------------
                                ?>
                                <?php td_page_generator::get_pagination(); ?>
                            </div>
                        </div>
	                    <div class="td-pb-span4 td-main-sidebar">
		                    <div class="td-ss-main-sidebar">
			                    <?php get_sidebar(); ?>
		                    </div>
	                    </div>
                        <?php

                        break;


                    case 'no_sidebar':
                        ?>
                        <div class="td-pb-span12 td-main-content">
                            <div class="td-ss-main-content <?php echo esc_attr($ud_custom_css)?>">
                                <?php
                                // Unixdev MOD
                                if ( ( empty( $paged ) or $paged < 2 ) and 'td_category_top_posts_style_disable' !== td_api_category_top_posts_style::_helper_get_active_id()) {
                                    td_api_category_top_posts_style::_helper_show_category_top_posts_style();
                                    if ( td_util::is_ad_spot_enabled( 'ud_b4x_cat_ad' ) ) {
                                        echo td_global_blocks::get_instance( 'td_block_ad_box' )->render( array( 'spot_id' => 'ud_b4x_cat_ad' ) );
                                    }
                                }
                                //--------------
                                ?>
                                <?php
                                //Unixdev MOD
                                echo '<h1 class="entry-title td-page-title"><span>'.$current_category_obj->name.'</span></h1>';
                                locate_template('loop.php', true);
                                if (td_util::is_ad_spot_enabled('ud_b5x_cat_ad')) {
                                    echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b5x_cat_ad'));
                                }
                                //-------------
                                ?>
                                <?php td_page_generator::get_pagination(); ?>
                            </div>
                        </div>
                        <?php
                        break;
                }
            ?>
        </div> <!-- /.td-pb-row -->
    </div> <!-- /.td-container -->
</div> <!-- /.td-main-content-wrap -->

<?php

get_footer();
<?php
/*  ----------------------------------------------------------------------------
    the search template
 */


get_header();



//set the template id, used to get the template specific settings
$template_id = 'search';

//prepare the loop variables
global $loop_module_id, $loop_sidebar_position;

/* after */
$loop_module_id = td_util::get_option('tds_' . $template_id . '_page_layout', 16); //module 16 is default
$loop_sidebar_position = td_util::get_option('tds_' . $template_id . '_sidebar_pos'); //sidebar right is default (empty)

// sidebar position used to align the breadcrumb on sidebar left + sidebar first on mobile issue
$td_sidebar_position = '';
if($loop_sidebar_position == 'sidebar_left') {
	$td_sidebar_position = 'td-sidebar-left';
}

td_global::$custom_no_posts_message = __td('No results for your search', TD_THEME_NAME);


?>
<div class="td-main-content-wrap td-container-wrap">

<div class="td-container <?php echo $td_sidebar_position; ?>">
    <div class="td-crumb-container">
        <?php echo td_page_generator::get_search_breadcrumbs(); ?>
    </div>
    <?php apply_filters( 'ud_header_ads', '' ) //Unixdev MOD ?>
    <div class="td-pb-row">
        <?php

        switch ($loop_sidebar_position) {
            default:
                ?>
                    <div class="td-pb-span8 td-main-content">
                        <div class="td-ss-main-content">
                            <div class="td-page-header">
                                <?php locate_template('parts/page-search-box.php', true); ?>
                            </div>
                            <?php
                            //Unixdev MOD
                            if (td_util::is_ad_spot_enabled('ud_b6x_cat_ad')) {
                                echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b6x_cat_ad'));
                            }
                            locate_template('loop.php', true);
                            if (td_util::is_ad_spot_enabled('ud_b5x_cat_ad')) {
                                echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b5x_cat_ad'));
                            }
                            //-------------
                            ?>

                            <?php echo td_page_generator::get_pagination(); ?>
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
                    <div class="td-ss-main-content">
                        <div class="td-page-header">
                            <?php locate_template('parts/page-search-box.php', true); ?>
                        </div>
                        <?php   
                            //Unixdev MOD
                            if (td_util::is_ad_spot_enabled('ud_b6x_cat_ad')) {
                                echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b6x_cat_ad'));
                            }
                            locate_template('loop.php', true);
                            if (td_util::is_ad_spot_enabled('ud_b5x_cat_ad')) {
                                echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b5x_cat_ad'));
                            }
                            //-------------
                        ?>

                        <?php echo td_page_generator::get_pagination(); ?>
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
                    <div class="td-ss-main-content">
                        <div class="td-page-header">
                            <?php locate_template('parts/page-search-box.php', true); ?>
                        </div>
                        <?php locate_template('loop.php', true);?>

                        <?php echo td_page_generator::get_pagination(); ?>
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
?>
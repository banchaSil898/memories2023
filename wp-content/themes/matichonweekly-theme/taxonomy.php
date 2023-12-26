<?php
/**
 * the custom taxonomy template
 * This file is loaded by WordPress on custom taxonomies. You can further customize this template
 * for specific taxonomies by copying this file to taxonomy-yourTaxonomyName.php
 */

get_header();

global $loop_module_id, $loop_sidebar_position;

// get the current taxonomy object - note that it's note complete
$current_term_obj = get_queried_object();

//read the loop variables for this specific taxonomy
$loop_module_id = td_util::get_taxonomy_option($current_term_obj->taxonomy, 'tds_taxonomy_page_layout');
$loop_sidebar_position = td_util::get_taxonomy_option($current_term_obj->taxonomy, 'tds_taxonomy_sidebar_pos');

if (empty($loop_module_id)) {
    $loop_module_id = 1; // module_1 is the default
}

// sidebar position used to align the breadcrumb on sidebar left + sidebar first on mobile issue
$td_sidebar_position = '';
if($loop_sidebar_position == 'sidebar_left') {
    $td_sidebar_position = 'td-sidebar-left';
}

?>

    <div class="td-main-content-wrap td-container-wrap">
        <div class="td-container <?php echo $td_sidebar_position; ?>">
            <div class="td-crumb-container">
                <?php echo td_page_generator::get_taxonomy_breadcrumbs($current_term_obj); // get the breadcrumbs - /includes/wp_booster/td_page_generator.php ?>
            </div>
            <?php apply_filters( 'ud_header_ads', '' ) //Unixdev MOD ?>

            <!-- content -->
            <div class="td-pb-row">
                <?php
                switch ($loop_sidebar_position) {

                    default: //default: sidebar right
                        ?>
                        <div class="td-pb-span8 td-main-content">
                            <div class="td-ss-main-content">
                                <div class="td-page-header">
                                    <h1 class="entry-title td-page-title">
                                        <span><?php echo $current_term_obj->name ?></span>
                                    </h1>
                                </div>
                                <?php
                                //Unixdev MOD
                                locate_template('loop.php', true);
                                if (td_util::is_ad_spot_enabled('ud_b5x_cat_ad')) {
                                    echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b5x_cat_ad'));
                                }
                                //----------
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
                        <div class="td-pb-span8 td-main-content <?php echo $td_sidebar_position ?>-content">
                            <div class="td-ss-main-content">
                                <div class="td-page-header">
                                    <h1 class="entry-title td-page-title">
                                        <span><?php echo $current_term_obj->name ?></span>
                                    </h1>
                                </div>
                                <?php
                                //Unixdev MOD
                                echo '<div class="ud_loop_inner">';
                                locate_template('loop.php', true);
                                echo '</div>';
                                if (td_util::is_ad_spot_enabled('ud_b5x_cat_ad')) {
                                    echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b5x_cat_ad'));
                                }
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
                                    <h1 class="entry-title td-page-title">
                                        <span><?php echo $current_term_obj->name ?></span>
                                    </h1>
                                </div>
                                <?php
                                //Unixdev MOD
                                locate_template('loop.php', true);
                                if (td_util::is_ad_spot_enabled('ud_b5x_cat_ad')) {
                                    echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b5x_cat_ad'));
                                }
                                //----------------
                                ?>
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
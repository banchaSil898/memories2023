<?php
/*  ----------------------------------------------------------------------------
    the archive(s) template
 */

get_header();


//set the template id, used to get the template specific settings
$template_id = 'archive';

//prepare the loop variables
global $loop_module_id, $loop_sidebar_position, $part_cur_auth_obj;
$loop_module_id = 80;
$loop_sidebar_position = ''; //sidebar right is default (empty)

// sidebar position used to align the breadcrumb on sidebar left + sidebar first on mobile issue
$td_sidebar_position = '';
if($loop_sidebar_position == 'sidebar_left') {
	$td_sidebar_position = 'td-sidebar-left';
}

//read the current author object - used by here in title and by /parts/author-header.php
$part_cur_auth_obj = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

$td_archive_title = "นิตยสาร";

$breadcrumbs_array [] = array (
    'title_attribute' => '',
    'url' => get_post_type_archive_link(UDBook::POST_TYPE_NAME),
    'display_name' =>  "นิตยสาร"
);

global $ud_loop_wrap_mode;
$ud_loop_wrap_mode = 'disable';


?>
<div class="td-main-content-wrap">
    <div class="td-container <?php echo $td_sidebar_position; ?>">
        <div class="td-crumb-container">
            <?php echo td_page_generator::get_breadcrumbs($breadcrumbs_array); // get the breadcrumbs - /includes/wp_booster/td_page_generator.php ?>
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
                                    <h1 class="entry-title td-page-title">
                                        <span><?php echo $td_archive_title; ?></span>
                                    </h1>
                                </div>

                                <?php locate_template('loop.php', true);?>
                                <?php
                                //Unixdev MOD
                                if ( td_util::is_ad_spot_enabled( 'ud_b5x_cat_ad' ) ) {
                                    echo td_global_blocks::get_instance( 'td_block_ad_box' )->render( array( 'spot_id' => 'ud_b5x_cat_ad' ) );
                                }
                                //-----------
                                ?>

                                <?php td_page_generator::get_pagination(); // get the pagination - /includes/wp_booster/td_page_generator.php ?>
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
                                <h1 class="entry-title td-page-title">
                                    <span><?php echo $td_archive_title; ?></span>
                                </h1>
                            </div>

                            <?php locate_template('loop.php', true);?>
                            <?php
                            //Unixdev MOD
                            if ( td_util::is_ad_spot_enabled( 'ud_b5x_cat_ad' ) ) {
                                echo td_global_blocks::get_instance( 'td_block_ad_box' )->render( array( 'spot_id' => 'ud_b5x_cat_ad' ) );
                            }
                            //-----------
                            ?>

                            <?php td_page_generator::get_pagination(); // get the pagination - /includes/wp_booster/td_page_generator.php ?>
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
                                    <span><?php echo $td_archive_title; ?></span>
                                </h1>
                            </div>
                            <?php locate_template('loop.php', true);?>
                            <?php
                            //Unixdev MOD
                            if ( td_util::is_ad_spot_enabled( 'ud_b5x_cat_ad' ) ) {
                                echo td_global_blocks::get_instance( 'td_block_ad_box' )->render( array( 'spot_id' => 'ud_b5x_cat_ad' ) );
                            }
                            //-----------
                            ?>

                            <?php td_page_generator::get_pagination(); // get the pagination - /includes/wp_booster/td_page_generator.php ?>
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
<?php
/* Template Name: Pagebuilder + latest copy */

get_header();

td_global::$current_template = 'page-homepage-loop';

global $paged, $loop_module_id, $loop_sidebar_position, $post, $more; //$more is a hack to fix the read more loop
$td_page = (get_query_var('page')) ? get_query_var('page') : 1; //rewrite the global var
$td_paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //rewrite the global var


//paged works on single pages, page - works on homepage
if ($td_paged > $td_page) {
    $paged = $td_paged;
} else {
    $paged = $td_page;
}


$list_custom_title_show = true; //show the article list title by default




/*
    read the settings for the loop
---------------------------------------------------------------------------------------- */
if (!empty($post->ID)) {
    td_global::load_single_post($post);
}
/**
 * detect the page builder
 */
$td_use_page_builder = td_global::is_page_builder_content();
?>

<div class="td-main-content-wrap td-main-page-wrap td-container-wrap">
	<div class="tdc-content-wrap">
		<?php the_content(); ?>
	</div>
<div class="td-container td-pb-article-list">
    <div class="td-pb-row">
        <?php
        // set the $cur_single_template_sidebar_pos - for gallery and video playlist
        td_global::$cur_single_template_sidebar_pos = $loop_sidebar_position;

        // The main content header style is from 'tds_global_block_template'
        $global_block_template_id = td_options::get('tds_global_block_template', 'td_block_template_1');
        $global_block_template_instance = new $global_block_template_id(
            array(
                'atts' => array(
                    'custom_title' => $td_list_custom_title,
                ),
			)
        );

        $main_content_title = '<div class="td-block-title-wrap">' . $global_block_template_instance->get_block_title() . '</div>';

        //the default template
        switch ($loop_sidebar_position) {
            default: //sidebar right
                ?>
                    <div class="td-pb-span8 td-main-content" role="main">
                        <div class="td-ss-main-content <?php echo $global_block_template_id ?> <?php echo esc_attr($ud_custom_css)?>">
                            <?php
                                //Unixdev MOD
                                if ( !empty ($ud_top_loop_ad_spot_id) and td_util::is_ad_spot_enabled($ud_top_loop_ad_spot_id)) {
                                    echo '<div class="ud_block_inner">';
                                    echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => $ud_top_loop_ad_spot_id));
                                    echo '</div>';
                                }
                                //---------

                            //query_posts(td_data_source::metabox_to_args($td_homepage_loop_filter, $paged));
                            query_posts(td_data_source::metabox_to_args($td_homepage_loop, $paged));
                            if ( empty($ud_loop_block_mode)) {
                                if ((empty($paged) or $paged < 2) and $list_custom_title_show === true) {
                                    echo $main_content_title;
                                }
                                locate_template('loop.php', true);
                            }else {
                                locate_template('loop-td-block.php', true);
                            }
                            td_page_generator::get_pagination();
                            wp_reset_query();
                            ?>
                        </div>
                    </div>
                    <div class="td-pb-span4 td-main-sidebar" role="complementary">
                        <div class="td-ss-main-sidebar">
                            <?php get_sidebar(); ?>
                        </div>
                    </div>
                <?php
                break;

            case 'sidebar_left':
                ?>
                <div class="td-pb-span8 td-main-content <?php echo $td_sidebar_position; ?>-content" role="main">
                    <div class="td-ss-main-content <?php echo $global_block_template_id ?> <?php echo esc_attr($ud_custom_css)?>">
                        <?php if ((empty($paged) or $paged < 2) and $list_custom_title_show === true) {
                            echo $main_content_title;
                        }

                        //query_posts(td_data_source::metabox_to_args($td_homepage_loop_filter, $paged));
                        query_posts(td_data_source::metabox_to_args($td_homepage_loop, $paged));
                        locate_template('loop.php', true);
                        td_page_generator::get_pagination();
                        wp_reset_query();
                        ?>
                    </div>
                </div>
	            <div class="td-pb-span4 td-main-sidebar" role="complementary">
		            <div class="td-ss-main-sidebar">
			            <?php get_sidebar(); ?>
		            </div>
	            </div>
                <?php
                break;

            case 'no_sidebar':
                //td_global::$load_featured_img_from_template = 'art-slide-big';
                td_global::$load_featured_img_from_template = 'full';
                ?>
                <div class="td-pb-span12 td-main-content" role="main">
                    <div class="td-ss-main-content <?php echo $global_block_template_id ?> <?php echo esc_attr($ud_custom_css)?>">
                        <?php if ((empty($paged) or $paged < 2) and $list_custom_title_show === true) {
                            echo $main_content_title;
                        }

                        //query_posts(td_data_source::metabox_to_args($td_homepage_loop_filter, $paged));
                        query_posts(td_data_source::metabox_to_args($td_homepage_loop, $paged));
                        locate_template('loop.php', true);
                        td_page_generator::get_pagination();
                        wp_reset_query();
                        ?>
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
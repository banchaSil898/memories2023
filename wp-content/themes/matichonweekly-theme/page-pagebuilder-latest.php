<?php
/* Template Name: Pagebuilder + latest articles + pagination */

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

    //read the metadata for the post
	//
	// the $td_homepage_loop is used instead
    //$td_homepage_loop_filter = get_post_meta($post->ID, 'td_homepage_loop_filter', true); //it's send to td_data_source
    $td_homepage_loop = td_util::get_post_meta_array($post->ID, 'td_homepage_loop');


    if (!empty($td_homepage_loop['td_layout'])) {
        $loop_module_id = $td_homepage_loop['td_layout'];
    }

    if (!empty($td_homepage_loop['td_sidebar_position'])) {
        $loop_sidebar_position = $td_homepage_loop['td_sidebar_position'];
    }

	// sidebar position used to align the breadcrumb on sidebar left + sidebar first on mobile issue
	$td_sidebar_position = '';
	if($loop_sidebar_position == 'sidebar_left') {
		$td_sidebar_position = 'td-sidebar-left';
	}

    if (!empty($td_homepage_loop['td_sidebar'])) {
        td_global::$load_sidebar_from_template = $td_homepage_loop['td_sidebar'];
    }

    if (!empty($td_homepage_loop['list_custom_title'])) {
        $td_list_custom_title = $td_homepage_loop['list_custom_title'];
    } else {
        $td_list_custom_title =__td('LATEST ARTICLES', TD_THEME_NAME);
    }


    if (!empty($td_homepage_loop['list_custom_title_show'])) {
        $list_custom_title_show = false;
    }

    //Unixdev MOD: force column count
    global $ud_force_column_number;
    if ( ! empty($td_homepage_loop['ud_force_column_number']) && ! empty (intval($td_homepage_loop['ud_force_column_number'])) ) {
        $ud_force_column_number = intval($td_homepage_loop['ud_force_column_number']);
    }

    $ud_custom_css = '';
    if ( ! empty($td_homepage_loop['ud_custom_css']) ) {
        $ud_custom_css = $td_homepage_loop['ud_custom_css'];
    }

    //-------------------------------

    //Unixdev MOD: enable/disable ud_loop_inner div
    global $ud_loop_wrap_mode;
    if ( ! empty($td_homepage_loop['ud_loop_wrap_mode']) ) {
        $ud_loop_wrap_mode = $td_homepage_loop['ud_loop_wrap_mode'];
    }
    //-----------------------------


    //Unixdev MOD: loop block mode;
    global $ud_loop_block_mode;
    if ( ! empty($td_homepage_loop['ud_loop_block_mode']) ) {
        $ud_loop_block_mode = $td_homepage_loop['ud_loop_block_mode'];

        $ud_force_column_number = 1;
        // override post type setting from Post loop setting tab
        switch ($ud_loop_block_mode) {
            case 'columnist' :
                $td_homepage_loop['installed_post_types'] = UD_COLUMNIST_POST_TYPE_NAME;
                break;

            default :
                error_log('ud_loop_block_mode unknown option');
                break;
        }
    }
    //----------------------------------
}
/**
 * detect the page builder
 */
$td_use_page_builder = td_global::is_page_builder_content();
?>

<div class="td-main-content-wrap td-main-page-wrap td-container-wrap">
    <?php //Unixdev MOD ?>
    <div class="td-container">
        <div class="td-crumb-container">
            <?php echo td_page_generator::get_page_breadcrumbs(get_the_title()); ?>
        </div>
        <?php apply_filters( 'ud_header_ads', '' ); //Unixdev MOD ?>
    </div>
    <?php //-------------?>

<?php
/*
the first part of the page (built with the page builder)  - empty($paged) or $paged < 2 = first page
---------------------------------------------------------------------------------------- */
//td_global::$cur_single_template_sidebar_pos = 'no_sidebar';
if(!empty($post->post_content)) { //show this only when we have content
    if (empty($paged) or $paged < 2) { //show this only on the first page
        if (have_posts()) { ?>
            <?php while ( have_posts() ) : the_post(); ?>

                <div class="<?php if ((!td_util::tdc_is_installed()) or (!$td_use_page_builder)) { echo 'td-container '; } ?>tdc-content-wrap">
                    <?php the_content(); ?>
                </div>

            <?php endwhile; ?>
        <?php }
    }
} else if ( td_util::tdc_is_live_editor_iframe() ) {
	// The content needs to be shown (Maybe we have a previewed content, and we need the 'the_content' hook !)
	?>
	<div class="tdc-content-wrap">
		<?php the_content(); ?>
	</div>
	<?php
}
?>

<?php
    //Unixdev MOD
    $ud_top_loop_ad_spot_id = '';
    if (is_page()){
    $ud_td_page = get_post_meta($post->ID, 'td_homepage_loop', true);
        if ( ! empty ($ud_td_page['ud_top_loop_ad_spot_id'])) {
            $ud_top_loop_ad_spot_id = $ud_td_page['ud_top_loop_ad_spot_id'];
        }
    }

    //-----------
?>


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
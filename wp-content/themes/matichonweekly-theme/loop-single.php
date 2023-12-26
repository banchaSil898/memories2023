<?php
/**
 * The single post loop Default template
 **/

if (have_posts()) {
    the_post();

    $td_mod_single = new td_module_single($post);
    ?>

    <article id="post-<?php echo $td_mod_single->post->ID;?>" class="<?php echo join(' ', get_post_class());?>" <?php echo $td_mod_single->get_item_scope();?>>
        <div class="block-title">
            <?php
            //Unixdev MOD: add category as title
            $ud_primary_category_id = td_global::get_primary_category_id();
            $ud_primary_category_obj = get_category( $ud_primary_category_id );
            $ud_primary_category_link = get_category_link($ud_primary_category_id);
            echo '<a href="' . esc_url($ud_primary_category_link) . '">' . $ud_primary_category_obj->name . '</a>';
            //---------------
            ?>
        </div>
        <div class="ud_loop_inner">

            <div class="td-post-header">

                <?php echo $td_mod_single->get_category(); ?>

                <header class="td-post-title">
                    <?php echo $td_mod_single->get_title();?>


                    <?php if (!empty($td_mod_single->td_post_theme_settings['td_subtitle'])) { ?>
                        <p class="td-post-sub-title"><?php echo $td_mod_single->td_post_theme_settings['td_subtitle'];?></p>
                    <?php } ?>

                    <?php echo $td_mod_single->get_columnist(); //Unixdev MOD?>
                </header>

            </div>

            <?php echo $td_mod_single->get_social_sharing_top();?>

            <?php
            //Unixdev MOD
            if (td_util::is_ad_spot_enabled('ud_b1y_post_ad') and is_single()) {
                echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b1y_post_ad'));
            }
            //-----------
            ?>

            <?php //echo $td_mod_single->ud_get_article_reference(); ?>
            <?php //echo $td_mod_single->ud_get_magazine_reference(); ?>

            <div class="td-post-content">
                <?php
                // override the default featured image by the templates (single.php and home.php/index.php - blog loop)
                if (!empty(td_global::$load_featured_img_from_template)) {
                    echo $td_mod_single->get_image(td_global::$load_featured_img_from_template);
                } else {
                    echo $td_mod_single->get_image('td_696x0');
                }
                ?>
                <?php echo $td_mod_single->ud_get_article_info(); ?>
                <?php echo $td_mod_single->get_content();?>
            </div>

            <footer>
                <?php echo $td_mod_single->get_post_pagination();?>
                <?php echo $td_mod_single->get_review();?>

                <div class="td-post-source-tags">
                    <?php echo $td_mod_single->get_source_and_via();?>
                    <?php echo $td_mod_single->get_the_tags();?>
                </div>

                <?php echo $td_mod_single->get_social_sharing_bottom(); // Move ?>
                <?php //echo $td_mod_single->get_next_prev_posts(); //Unixdev MOD: move position ?>
                <?php echo $td_mod_single->get_author_box();?>
                <?php echo $td_mod_single->get_item_scope_meta();?>
            </footer>




            <?php
            //Unixdev mOD
            if (td_util::is_ad_spot_enabled('ud_b3x_post_ad') and is_single()) {
                echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b3x_post_ad'));
            }
            /* if (td_util::is_ad_spot_enabled('ud_taboola_ad') and is_single()) {
                echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_taboola_ad'));
            } */
            //-----------
            ?>

            <?php
    //Unixdev MOD
    if (td_util::is_ad_spot_enabled('ud_news_list_post_ad') and is_single()) {
        echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_news_list_post_ad'));
    }
    //-----------
    ?>
 <?php
    //Unixdev MOD
    if (td_util::is_ad_spot_enabled('ud_dable_post_ad') and is_single()) {
        echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_dable_post_ad'));
    }
    //-----------
    ?>
        </div>


    </article> <!-- /.post -->


    <?php echo $td_mod_single->get_next_prev_posts();?>
    <?php echo $td_mod_single->related_posts();?>
    <?php
    //Unixdev MOD
    if (td_util::is_ad_spot_enabled('ud_b4x_post_ad') and is_single()) {
        echo td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'ud_b4x_post_ad'));
    }
    //----------
    ?>

<?php
} else {
    //no posts
    echo td_page_generator::no_posts();
}

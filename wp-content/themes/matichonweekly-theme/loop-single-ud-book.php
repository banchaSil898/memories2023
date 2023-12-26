<?php
/**
 * The single post loop Default template  (Unixdev MOD)
 **/

if (have_posts()) {
    the_post();

    $td_mod_single = new td_module_single($post);
    ?>

    <article id="post-<?php echo $td_mod_single->post->ID;?>" class="<?php echo join(' ', get_post_class());?>" <?php echo $td_mod_single->get_item_scope();?>>
        <div class="block-title">
            <?php
            //Unixdev MOD: add category as title
            $ud_primary_category_link = get_post_type_archive_link(UDBook::POST_TYPE_NAME);
            echo '<a href="' . esc_url($ud_primary_category_link) . '">' . "นิตยสาร" . '</a>';
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

                    <?php //Unixdev MOD: delete td-module-meta-info because of having ud_get_article_info ?>

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

            <div class="td-post-content">
                <?php
                // override the default featured image by the templates (single.php and home.php/index.php - blog loop)
                if (!empty(td_global::$load_featured_img_from_template)) {
                    echo $td_mod_single->get_image(td_global::$load_featured_img_from_template);
                } else {
                    echo $td_mod_single->get_image('td_696x0');
                }
                ?>
                <?php echo $td_mod_single->ud_get_book_purchase_button();?>
                <?php echo $td_mod_single->get_content();?>
            </div>

            <footer>
                <?php echo $td_mod_single->get_post_pagination();?>
                <?php echo $td_mod_single->get_review();?>

                <div class="td-post-source-tags">
                    <?php echo $td_mod_single->get_source_and_via();?>
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

            <div id="_popIn_recommend"></div>

<script type="text/javascript">

    (function() {

        var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.charset = "utf-8"; pa.async = true;

        pa.src = window.location.protocol + "//api.popin.cc/searchbox/matichonweekly_th.js";

        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(pa, s);

    })();

</script>
        </div>


    </article> <!-- /.post -->


    <?php echo get_next_prev_posts();?>
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

function get_next_prev_posts() {
    if (! is_single()) {
        return '';
    }

    if (td_util::get_option('tds_show_next_prev') == 'hide') {
        return '';
    }

    $buffy = '';

    $next_post = get_next_post();
    $prev_post = get_previous_post();

    if (!empty($next_post) or !empty($prev_post)) {
        $buffy .= '<div class="td-block-row td-post-next-prev">';
        if (!empty($prev_post)) {
            $buffy .= '<div class="td-block-span6 td-post-prev-post">';
            $buffy .= '<div class="td-post-next-prev-content"><span>' . "นิตยสารฉบับก่อนหน้า" . '</span>';
            $buffy .= '<a href="' . esc_url(get_permalink($prev_post->ID)) . '">' . get_the_title( $prev_post->ID ) . '</a>';
            $buffy .= '</div>';
            $buffy .= '</div>';
        } else {
            $buffy .= '<div class="td-block-span6 td-post-prev-post">';
            $buffy .= '</div>';
        }
        $buffy .= '<div class="td-next-prev-separator"></div>';
        if (!empty($next_post)) {
            $buffy .= '<div class="td-block-span6 td-post-next-post">';
            $buffy .= '<div class="td-post-next-prev-content"><span>' . "นิตยสารฉบับถัดไป" . '</span>';
            $buffy .= '<a href="' . esc_url(get_permalink($next_post->ID)) . '">' . get_the_title( $next_post->ID ) . '</a>';
            $buffy .= '</div>';
            $buffy .= '</div>';
        }
        $buffy .= '</div>'; //end fluid row
    }

    return $buffy;
}

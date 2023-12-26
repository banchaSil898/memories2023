<?php
/**
 * The single post loop Default template
 **/

if (have_posts()) {
    the_post();

    $td_mod_single = new td_module_single($post);
    ?>

    <article id="post-<?php echo $td_mod_single->post->ID;?>" class="<?php echo join(' ', get_post_class());?>" <?php echo $td_mod_single->get_item_scope();?>>
        <div class="ud_loop_inner">

            <div class="td-post-header">

                <header class="td-post-title">
                    <?php echo $td_mod_single->get_title();?>
                </header>

            </div>

            <?php echo $td_mod_single->get_social_sharing_top();?>

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
                <?php // echo $td_mod_single->get_author_box();?>
                <?php // echo $td_mod_single->get_item_scope_meta();?>
            </footer>
        </div>
    </article> <!-- /.post -->
    
<?php
} else {
    //no posts
    echo td_page_generator::no_posts();
}

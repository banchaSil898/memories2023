<?php

locate_template('includes/wp_booster/td_single_template_vars.php', true);

get_header();

global $loop_module_id, $loop_sidebar_position, $post, $td_sidebar_position;

$td_mod_single = new td_module_single($post);




?>
<style>
    .td_block_template_1 {
        display: none !important;
    }
    .td-post-template-default .td-post-featured-image img {
        width: 100% !important;
    }
</style>
<div class="td-main-content-wrap td-container-wrap">

    <div class="td-container td-post-template-default <?php echo $td_sidebar_position; ?>">
        <div class="td-crumb-container"><?php echo td_page_generator::get_single_breadcrumbs($td_mod_single->title); ?></div>

        <div class="td-pb-row">
            <div class="td-pb-span12 td-main-content" role="main">
                <div class="td-ss-main-content">
                    <?php
                    locate_template('loop-memories.php', true);
                    ?>
                </div>
            </div>
        </div> <!-- /.td-pb-row -->
    </div> <!-- /.td-container -->
</div> <!-- /.td-main-content-wrap -->

<?php

get_footer();
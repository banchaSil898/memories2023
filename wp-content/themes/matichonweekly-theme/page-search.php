<?php
/**
 * Template Name: Search Page
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
                            ?>
                            <div class="gcse-searchresults-only"></div>
                            <?php
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
                            ?>
                            <div class="gcse-searchresults-only"></div>
                            <?php
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
                        <div class="gcse-searchresults-only"></div>

                        <?php echo td_page_generator::get_pagination(); ?>
                    </div>
                </div>
                <?php
                break;
        }
        ?>
    </div> <!-- /.td-pb-row -->
    <style>
	.td-main-content-wrap .gsc-search-box {
		margin-bottom: 21px;
		padding: 0;
	}

	.td-main-content-wrap .gsc-search-box > tbody > tr {
		display: grid;
		grid-template-columns: 1fr 55px;
	}

	.td-main-content-wrap .gsc-search-box > tbody > tr tr {
		display: grid;
		grid-template-columns: 1fr 45px;
		align-items: center;
	}

	.td-main-content-wrap .gsc-search-box .gsc-input,
	.td-main-content-wrap .gsc-search-box .gsc-search-button {
		border: 0 none;
		padding: 0;
		margin: 0;
	}

	.td-main-content-wrap .gsc-search-box .gsc-input .gsib_a {
		display: flex;
		align-items: center;
		border: 0 none;
		padding: 0 8px;
		height: 35px;
	}

	.td-main-content-wrap .gsc-search-box .gsc-input .gsib_b {
		border: 0 none;
	}

	.td-main-content-wrap .gsc-search-box .gsc-search-button .gsc-search-button {
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 4px 8px;
		width: 55px;
		height: 37px;
		background: #000;
		margin: 0;
	}

	.td-main-content-wrap .gsc-control-cse {
		padding: 0;
		border: 0 none;
	}

	.gs-webResult.gs-result a.gs-title:link, .gs-webResult.gs-result a.gs-title:link b, .gs-imageResult a.gs-title:link, .gs-imageResult a.gs-title:link b {
		font-family: 'Sarabun', sans-serif !important;
		font-size: 20px !important;
		letter-spacing: 0px !important;
	}

	.gs-webResult div.gs-visibleUrl-breadcrumb {
		font-family: 'Sarabun', sans-serif !important;
		font-size: 15px;
	}

	.gs-webResult:not(.gs-no-results-result):not(.gs-error-result) .gs-snippet, .gs-fileFormatType {
		font-family: 'Sarabun', sans-serif !important;
		font-size: 15px !important;
	}

	.gs-web-image-box, .gs-promotion-image-box {
		padding: 2px 2px 2px 0 !important;
		margin-right: 10px !important;
		width: 100px !important;
	}

	.gs-web-image-box .gs-image, .gs-promotion-image-box .gs-promotion-image {
		max-width: 100px !important;
		max-height: 120px !important;
	}

	.gsc-results .gsc-cursor-box .gsc-cursor-page {
		font-family: 'Sarabun', san-serif !important;
		font-size: 15px !important;
	}
	</style>
</div> <!-- /.td-container -->
</div> <!-- /.td-main-content-wrap -->


<?php
get_footer();
?>

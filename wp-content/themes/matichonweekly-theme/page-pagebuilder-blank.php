<?php
/* Template Name: Pagebuilder Blank Page */

// Load Memories 
// require_once 'includes/memories/functions.php';
require_once 'includes/memories/shortcode.php';

get_header();

td_global::$current_template = 'page-title';
//set the template id, used to get the template specific settings
$template_id = 'page';


/**
 * detect the page builder
 */
$td_use_page_builder = td_global::is_page_builder_content();


?>
	<div class="td-main-content-wrap td-container-wrap" id="memories" style="padding-top: 0px; padding-bottom: 0px;">
		<div class="td-pb-row">
			<div class="td-pb-span12 td-main-content" role="main">
				<?php
				if (have_posts()) {
				while ( have_posts() ) : the_post();
				?>
				<div class="td-page-content">
					<?php
					the_content();
					endwhile; //end loop
					}
					?>
				</div>
			</div>
		</div> <!-- /.td-pb-row -->
	</div> <!-- /.td-main-content-wrap -->
<?php






get_footer();
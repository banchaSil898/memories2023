<div id="td-header-menu" role="navigation">
    <div id="td-top-mobile-toggle"><button type="button"><i class="td-icon-font td-icon-mobile"></i></button></div>
    <div class="td-main-menu-logo td-logo-in-header">
        <?php
        if (td_util::get_option('tds_logo_menu_upload') == '') {
            locate_template('parts/header/logo.php', true, false);
        } else {
            locate_template('parts/header/logo-mobile.php', true, false);
        }?>
    </div>
    <?php
    wp_nav_menu(array(
        'theme_location' => 'header-menu',
        'menu_class'=> 'sf-menu',
        'fallback_cb' => 'td_wp_page_menu',
        'walker' => new td_tagdiv_walker_nav_menu()
    ));


    //if no menu
    function td_wp_page_menu() {
        //this is the default menu
        echo '<ul class="sf-menu">';
        echo '<li class="menu-item-first"><a href="' . esc_url(home_url( '/' )) . 'wp-admin/nav-menus.php?action=locations">Click here - to select or create a menu</a></li>';
        echo '</ul>';
    }
    ?>
</div>


<?php
if ( ! is_page_template( 'page-search.php' ) ) :
	$cx_value = get_option( 'google_search_cx' );
	?>
	<div class="td-search-wrapper">
		<div id="td-top-search">
			<!-- Search -->
			<div class="header-search-wrap">
				<div class="dropdown header-search">
					<a id="ud-td-header-search-button" href="#" role="button" class="dropdown-toggle " data-toggle="dropdown"><i class="td-icon-search"></i></a>
				</div>
			</div>
		</div>
	</div>

	<div class="header-search-wrap">
		<div class="dropdown header-search">
			<div class="td-drop-down-search" aria-labelledby="td-header-search-button">
				<script async src="https://cse.google.com/cse.js?cx=<?php echo esc_attr( $cx_value ); ?>"></script>
				<div class="gcse-searchbox-only"></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	const searchButton = document.getElementById('ud-td-header-search-button');
	const searchWrapper = document.querySelector('.header-search-wrap .td-drop-down-search');

	searchButton.addEventListener('click', function() {
		searchWrapper.classList.toggle('td-drop-down-search-open');
	});

	document.addEventListener('click', function(event) {
		console.log(event.target);
		if (!searchWrapper.contains(event.target) && !searchButton.contains(event.target) ) {
			searchWrapper.classList.remove('td-drop-down-search-open');
		}
	});
	</script>
	<style>
	.header-search-wrap .gsc-search-box {
		margin-bottom: 0;
		padding: 8px;
	}

	.header-search-wrap .gsc-search-box > tbody > tr {
		display: grid;
		grid-template-columns: 1fr 55px;
	}

	.header-search-wrap .gsc-search-box > tbody > tr tr {
		display: grid;
		grid-template-columns: 1fr 45px;
		align-items: center;
	}

	.header-search-wrap .gsc-search-box .gsc-input,
	.header-search-wrap .gsc-search-box .gsc-search-button {
		border: 0 none;
		padding: 0;
		margin: 0;
	}

	.header-search-wrap .gsc-search-box .gsc-input .gsib_a {
		display: flex;
		align-items: center;
		border: 0 none;
		padding: 0 8px;
		height: 35px;
	}

	.header-search-wrap .gsc-search-box .gsc-input .gsib_b {
		border: 0 none;
	}

	.header-search-wrap .gsc-search-box .gsc-search-button .gsc-search-button {
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 4px 8px;
		width: 55px;
		height: 37px;
		background: #000;
		margin: 0;
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
	<?php
endif;

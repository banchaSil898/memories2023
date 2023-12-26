<!--
Header style 1 (Unixdev MOD)
-->
<div class="td-header-wrap td-header-style-1">

    <div class="td-header-top-menu-full td-container-wrap <?php echo td_util::get_option('td_full_top_bar'); ?>">
        <div class="td-container td-header-row td-header-top-menu">
            <?php td_api_top_bar_template::_helper_show_top_bar() ?>
        </div>
    </div>

    <div class="td-banner-wrap-full td-logo-wrap-full td-container-wrap <?php echo td_util::get_option('td_full_header'); ?>">
        <div class="td-container td-header-row td-header-header">
            <div class="td-header-sp-logo">
                <?php locate_template('parts/header/logo-h1.php', true);?>
            </div>
        </div>
    </div>

    <div class="ud-header-social-full">
        <div class="td-container td-header-row">
            <div class="ud-header-widgets">
                <?php locate_template('parts/header/ud-social-widget.php', true); ?>
            </div>
        </div>
    </div>

    <div class="td-header-menu-wrap-full td-container-wrap <?php echo td_util::get_option('td_full_menu'); ?>">
        <div class="td-header-menu-wrap td-header-gradient">
            <div class="td-container td-header-row td-header-main-menu">
                <?php locate_template('parts/header/header-menu.php', true);?>
            </div>
        </div>
    </div>

    <div class="ud-gray-wrap"></div>

</div>
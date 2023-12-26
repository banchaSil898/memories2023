<?php

td_api_ad::helper_display_ads( array(
    'header',
    'ud_b1y_post_ad',
    'ud_b2x_post_ad',
    'ud_b3x_post_ad',
    'ud_b4x_post_ad',
    'content_inline',
    'content_inline_2',
    'content_inline_3',
    'ud_b6x_post_ad',
    'ud_footer_sticky_post_ad',
    'ud_takeover_post_ad',
    'ud_under_menu_post_ad',
    'ud_dable_post_ad',
    'ud_news_list_post_ad',
) );

?>

<?php
echo td_panel_generator::box_start( 'Taboola ad', false ); ?>

<div class="td-box-row">
    <div class="td-box-description td-box-full">
        <span class="td-box-title">More information:</span>
        <p>You can put your Taboola's initial code and Taboola's ad code here.</p>
        <p><strong>Note: Taboola's end-of-body-tag code will be automatically generated!, Don't need to be
                worry.</strong></p>
    </div>
    <div class="td-box-row-margin-bottom"></div>
</div>

<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">YOUR TABOOLA'S INITIAL CODE</span>
        <p>
            this code will be in the &lt;head&gt; tag
        </p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::textarea( array(
            'ds' => 'td_ads',
            'item_id' => 'ud_taboola_ad',
            'option_id' => 'init_script',
        ) );
        ?>
    </div>
</div>

<!-- ad box code -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">YOUR TABOOLA AD CODE</span>
        <p>Paste your ad code here.</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::textarea( array(
            'ds' => 'td_ads',
            'item_id' => 'ud_taboola_ad',
            'option_id' => 'ad_code',
        ) );
        ?>
    </div>
</div>

<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">AD title:</span>
        <p>A title for the Ad, like - <strong>Advertisement</strong> - if you leave it blank the ad spot will not have a
            title</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::input( array(
            'ds' => 'td_option',
            'option_id' => 'tds_ud_taboola_ad_title'
        ) );
        ?>
    </div>
</div>

<!-- disable ad on monitor -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title td-title-on-row">DISABLE ON DESKTOP</span>
        <p></p>
    </div>
    <div class="td-box-control-full">
                <span>
                <?php
                echo td_panel_generator::checkbox( array(
                    'ds' => 'td_ads',
                    'item_id' => 'ud_taboola_ad',
                    'option_id' => 'disable_m',
                    'true_value' => 'yes',
                    'false_value' => ''
                ) );
                ?>
                </span>
    </div>
</div>


<!-- disable ad on table landscape -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title td-title-on-row">DISABLE ON TABLET LANDSCAPE</span>
        <p></p>
    </div>
    <div class="td-box-control-full">
                <span>
                <?php
                echo td_panel_generator::checkbox( array(
                    'ds' => 'td_ads',
                    'item_id' => 'ud_taboola_ad',
                    'option_id' => 'disable_tl',
                    'true_value' => 'yes',
                    'false_value' => ''
                ) );
                ?>
                </span>
    </div>
</div>


<!-- disable ad on tablet portrait -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title td-title-on-row">DISABLE ON TABLET PORTRAIT</span>
        <p></p>
    </div>
    <div class="td-box-control-full">
                <span>
                <?php
                echo td_panel_generator::checkbox( array(
                    'ds' => 'td_ads',
                    'item_id' => 'ud_taboola_ad',
                    'option_id' => 'disable_tp',
                    'true_value' => 'yes',
                    'false_value' => ''
                ) );
                ?>
                </span>
    </div>
</div>


<!-- disable ad on phones -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">DISABLE ON PHONE</span>
        <p>Google adsense requiers that you do not use big header ads on mobiles!</p>
    </div>
    <div class="td-box-control-full">
                <span>
                <?php
                echo td_panel_generator::checkbox( array(
                    'ds' => 'td_ads',
                    'item_id' => 'ud_taboola_ad',
                    'option_id' => 'disable_p',
                    'true_value' => 'yes',
                    'false_value' => ''
                ) );
                ?>
                </span>
    </div>
</div>
<?php echo td_panel_generator::box_end(); ?>


<?php
//backround add
echo td_panel_generator::box_start('Background click Ad', false);?>

<div class="td-box-row">
	<div class="td-box-description td-box-full">
		<span class="td-box-title">Notice:</span>
		<p>Please go to <strong>BACKGROUND</strong> tab if you also need a background image</p>
	</div>
	<div class="td-box-row-margin-bottom"></div>
</div>

<!-- ad box code -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">URL</span>
		<p>Paste your link here like : http://www.domain.com</p>
	</div>
	<div class="td-box-control-full td-panel-input-wide">
		<?php
		echo td_panel_generator::input(array(
			'ds' => 'td_option',
			'option_id' => 'tds_background_click_url',
		));
		?>
	</div>
</div>


<!-- ad taget -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Open in new window</span>
		<p>If enabled, this option will open the URL in a new window. Leave disabled for the URL to be loaded in current page</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::checkbox(array(
			'ds' => 'td_option',
			'option_id' => 'tds_background_click_target',
			'true_value' => '_blank',
			'false_value' => ''
		));
		?>
	</div>
</div>

<?php  echo td_panel_generator::box_end();?>

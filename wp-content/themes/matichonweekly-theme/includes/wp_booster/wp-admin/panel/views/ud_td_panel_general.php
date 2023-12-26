<?php
/**
 * Created by PhpStorm.
 * User: Pai
 * Date: 9/7/2015
 * Time: 3:14 PM
 */
?>

<?php echo td_panel_generator::box_start('Author Settings', true); ?>
    <div class="td-box-row">
        <div class="td-box-description td-box-full">
            <p>force author name on article. this will ignore author from Wordpress system</p>
        </div>
        <div class="td-box-row-margin-bottom"></div>
    </div>
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">Author Username</SPAN>
            <p>specify author by username (login name)</p>
        </div>
        <div class="td-box-control-full td-panel-input">
            <?php
            echo td_panel_generator::input(array(
                'ds' => 'td_option',
                'option_id' => 'ud_force_author_username',
                'placeholder' => '',
            ));
            ?>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>

<?php echo td_panel_generator::box_start('Search Settings', true); ?>
<!-- Enable footer -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">Disable Frontend Ajax Search</span>
        <p>Disable Frontend Ajax Search</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::checkbox(array(
            'ds' => 'td_option',
            'option_id' => 'ud_disable_ajax_search',
            'true_value' => 'yes',
            'false_value' => ''
        ));
        ?>
    </div>
</div>
<?php echo td_panel_generator::box_end();?>

<?php echo td_panel_generator::box_start('Ajax View Count Settings', true); ?>
<div class="td-box-row">
    <div class="td-box-description td-box-full">
        <p>Random sampling for performance issue</p>
    </div>
    <div class="td-box-row-margin-bottom"></div>
</div>
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">Max Value of Random Number</SPAN>
        <p>Max Value of Random Number</p>
    </div>
    <div class="td-box-control-full td-panel-input">
        1 / <?php
        echo td_panel_generator::input(array(
            'ds' => 'td_option',
            'option_id' => 'ud_sampling_max_value',
            'placeholder' => '',
        ));
        ?>
    </div>
</div>


<?php echo td_panel_generator::box_end();?>

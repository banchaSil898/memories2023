<?php
/**
 * Created by PhpStorm.
 * User: Pai
 * Date: 9/7/2015
 * Time: 3:14 PM
 */
?>

<?php echo td_panel_generator::box_start('Truehits Main Settings', true); ?>
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">ENABLE TRUEHITS</SPAN>
            <p>enable Truehits</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::checkbox(array(
                'ds' => 'td_option',
                'option_id' => 'ud_truehits_enable',
                'title' => 'enable',
                'true_value' => 'yes',
                'false_value' => '',
            ));
            ?>
        </div>
    </div>
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">TRUEHITS CODE</span>
            <p>Paste your Truehits code here</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::textarea(array(
                'ds' => 'td_option',
                'option_id' => 'ud_truehits_code',
            ));
            ?>
        </div>
    </div>

<?php echo td_panel_generator::box_end();?>
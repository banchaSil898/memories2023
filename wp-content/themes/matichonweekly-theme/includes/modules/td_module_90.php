<?php

//td_module_90 by Unixdev for columnist

class td_module_90 extends td_module {

    function __construct($post) {
        //run the parrent constructor
        parent::__construct($post);
    }

    function render() {
        ob_start();
        ?>

        <div class="<?php echo $this->get_module_classes();?>">
        <div class="meta-info-container">
                <?php echo $this->get_image('td_356x475');?>
                <?php if (td_util::get_option('tds_category_module_3') == 'yes') { echo $this->get_category(); }?>
        <div class="td-item-details">
            <?php echo $this->get_title();?>
            </div>


<!--            <div class="td-module-meta-info">-->
                <?php //echo $this->get_author();?>
                <?php //echo $this->get_date();?>
                <?php //echo $this->get_comments();?>
<!--            </div>-->

            <?php //echo $this->get_quotes_on_blocks();?>
        </div>

        </div>

        <?php return ob_get_clean();
    }
}
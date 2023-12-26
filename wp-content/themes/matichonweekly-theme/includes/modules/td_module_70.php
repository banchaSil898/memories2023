<?php

// Unixdev MOD: based on td_module_mx4
class td_module_70 extends td_module {
    function __construct($post) {
        //run the parrent constructor
        parent::__construct($post);
    }

    function render() {
        ob_start();
        ?>

        <div class="<?php echo $this->get_module_classes();?>">
            <div class="td-module-image">
                <?php echo $this->get_image('td_356x258');?>
                <?php if (td_util::get_option('tds_category_module_70') == 'yes') { echo $this->get_category(); }?>
            </div>

            <?php echo $this->get_title(); ?>
            <div class="td-module-meta-info">
                <?php echo $this->get_author();?>
                <?php echo $this->get_date();?>
                <?php echo $this->get_comments();?>
            </div>

            <div class="td-excerpt">
                <?php echo $this->get_excerpt();?>
            </div>

            <div class="td-read-more">
                <a href="<?php echo $this->href;?>"><?php echo __td('Read more', TD_THEME_NAME);?></a>
            </div>

        </div>

        <?php
        return ob_get_clean();
    }
}

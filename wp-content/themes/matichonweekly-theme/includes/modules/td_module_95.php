<?php

//td_module_95 by Unixdev for Quote category

class td_module_95 extends td_module {

    function __construct($post) {
        //run the parrent constructor
        parent::__construct($post);
    }

    function render() {
        ob_start();
        ?>

        <div class="<?php echo $this->get_module_classes();?> ud_quote">
            <div class="meta-info-container">
                <?php echo $this->get_image('td_696x0');?>
                <?php echo $this->ud_get_social_button();?>
            </div>
        </div>

        <?php return ob_get_clean();
    }
}
<?php

// td_module_80: for book

class td_module_80 extends td_module {

    function __construct($post) {
        //run the parrent constructor
        parent::__construct($post);
    }

    function render() {
        ob_start();
        ?>

        <div class="<?php echo $this->get_module_classes();?>">
            <div class="ud-white-box-wrap ud-book-details">
                <?php echo $this->get_image('td_696x0');?>

                <div class="item-details">
                    <?php //echo $this->get_title();?>
                </div>
            </div>
            <?php echo $this->ud_get_book_external()?>

        </div>

        <?php return ob_get_clean();
    }

    function ud_get_book_external(){
        $td_post_theme_settings = td_util::get_post_meta_array($this->post->ID, 'td_post_theme_settings');

        $ud_book_urls = array();

        if ( ! empty($td_post_theme_settings)) {
            foreach($td_post_theme_settings as $key => $value) {
                if (preg_match_all('/ud_purchase_book_(.*)_url/',$key, $out)){
                    $ud_book_urls[$out[1][0]] = $value;
                }
            }
        }

        $buffy = '';
        if ( ! empty($ud_book_urls)) {
            $buffy .= '<div class="ud-white-box-wrap ud-book-ext-wrap">';


            switch (count($ud_book_urls)) {
                case 1:
                    $buttons_class = 'ud-general-span12';
                    break;
                case 2:
                    $buttons_class = 'ud-general-span6';
                    break;
                case 3:
                    $buttons_class = 'ud-general-span4';
                    break;
                default:
                    $buttons_class = 'ud-general-span4';
            }

            foreach ( $ud_book_urls as $key => $url ){
                $buffy .= '<div class="ud-book-ext-button '. esc_attr($buttons_class).'">';
                $buffy .= '<i class="ud-sprite ud-icon-sprite-'.esc_attr($key).'"></i>';
                $buffy .= '<a href="'.esc_url($url).'" target="_blank">';
                if ( 'ngandee' === $key ) {
                    $buffy .= '<span>สมัครสมาชิก</span>';
                } else {
                    $buffy .= '<span>สั่งซื้อหนังสือ</span>';
                }
                $buffy .= '</a>';
                $buffy .= '</div>';
            }

            $buffy .= '</div>';

        }


        return $buffy;
    }
}

<?php

class td_module_unixdev_slide_1 extends td_module {


    function __construct($post) {
        //run the parrent constructor
        parent::__construct($post);
    }

    function get_title_main() {
        $buffy = '';

        $buffy .= '<div class="td-sbig-title-wrap">';
        $buffy .='<a class="noSwipe" href="' . $this->href . '" rel="bookmark" title="' . $this->title_attribute . '">';
        $buffy .= $this->get_title();
        $buffy .='</a>';
        $buffy .= '</div>';

        return $buffy;
    }

    function render($td_column_number, $td_post_count, $td_unique_id_slide, $ads_spot_id = '') {
        $buffy = '';

        $buffy .= '<div id="' . $td_unique_id_slide . '_item_' . $td_post_count . '" class = "' . $this->get_module_classes() . '">';
        if( empty($ads_spot_id) ) {
            switch ($td_column_number) {
                case '1': //one column layout
                    $buffy .= $this->get_image( 'td_696x0' );
                    break;
                case '2': //two column layout
                    $buffy .= $this->get_image( 'td_696x0' ); //Unixdev MOD
                    break;
                case '3': //three column layout
                    $buffy .= $this->get_image( 'td_696x0' );
                    break;
            }

            $buffy .= $this->ud_get_social_button();

        }else{
            if (td_util::is_ad_spot_enabled($ads_spot_id)) {
                $buffy .= '<div class="td-module-thumb ud-slide-ads-wrap">';
                $buffy .= td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => $ads_spot_id));
                $buffy .= '</div>';
            }
        }


            $buffy .= '</div>';

        return $buffy;
    }

    function get_category() {
        $buffy = '';

        //read the post meta to get the custom primary category
        $td_post_theme_settings = td_util::get_post_meta_array($this->post->ID, 'td_post_theme_settings');
        if (!empty($td_post_theme_settings['td_primary_cat'])) {
            //we have a custom category selected
            $selected_category_obj = get_category($td_post_theme_settings['td_primary_cat']);
        } else {
            //get one auto
            $categories = get_the_category($this->post->ID);
            if (!empty($categories[0])) {
                if ($categories[0]->name === TD_FEATURED_CAT and !empty($categories[1])) {
                    $selected_category_obj = $categories[1];
                } else {
                    $selected_category_obj = $categories[0];
                }
            }
        }


        if (!empty($selected_category_obj)) { //@todo catch error here
            $buffy .= '<a href="' . get_category_link($selected_category_obj->cat_ID) . '">'  . $selected_category_obj->name . '</a>' ;
        }

        //return print_r($post, true);
        return $buffy;
    }


    //overwrite the default function from td_module.php
    function get_comments() {
        $buffy = '';
        if (td_util::get_option('tds_p_show_comments') != 'hide') {
            $buffy .= '<div class="td-post-comments"><i class="td-icon-comments"></i>';
            $buffy .= '<a href="' . get_comments_link($this->post->ID) . '">';
            $buffy .= get_comments_number($this->post->ID);
            $buffy .= '</a>';
            $buffy .= '</div>';
        }

        return $buffy;
    }
}
//td-icon-views
<?php

// Support only 1-column

class td_block_unixdev_slide_1 extends td_block {


    function render($atts, $content = null){
        parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        // Unixdev MOD
        if ( empty( $atts['td_column_number'] ) ) {
            $td_column_number = td_global::vc_get_column_number();
        }else {
            $td_column_number = $atts['td_column_number'];
        }
        //-----------

        extract(shortcode_atts(
            array(
                'ud_block_slide_adspot_id_1' => '', //Unixdev MOD
                'ud_block_slide_ad_offset_pos_1' => -1, //Unixdev MOD
                'ud_block_slide_adspot_id_2' => '', //Unixdev MOD
                'ud_block_slide_ad_offset_pos_2' => -1, //Unixdev MOD
                'autoplay' => ''
            ),$atts));

        $buffy = ''; //output buffer

        //Unixdev MOD: collect ads
        $ud_ad_params = array();
        if ( td_util::is_ad_spot_enabled( $ud_block_slide_adspot_id_1 ) && is_numeric( $ud_block_slide_ad_offset_pos_1 ) && intval( $ud_block_slide_ad_offset_pos_1 ) >= 0 ) {
            $ud_ad_params[intval( $ud_block_slide_ad_offset_pos_1 ) + sizeof( $ud_ad_params )] = $ud_block_slide_adspot_id_1;
        }

        if ( td_util::is_ad_spot_enabled( $ud_block_slide_adspot_id_2 ) && is_numeric( $ud_block_slide_ad_offset_pos_2 ) && intval( $ud_block_slide_ad_offset_pos_2 ) >= 0 ) {
            $ud_ad_params[intval( $ud_block_slide_ad_offset_pos_2 ) + sizeof( $ud_ad_params )] = $ud_block_slide_adspot_id_2;
        }
        //----------------


        //Unixdev MOD: change from $this->td_query->found_posts to $this->td_query->post_count
        if ($this->td_query->have_posts() and $this->td_query->post_count > 1 ) {

            $buffy .= '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

		        //get the block js
		        $buffy .= $this->get_block_css();

		        //get the js for this block
		        $buffy .= $this->get_block_js();

                // block title wrap
                $buffy .= '<div class="td-block-title-wrap">';
                    $buffy .= $this->get_block_title(); //get the block title
                    $buffy .= $this->get_pull_down_filter(); //get the sub category filter for this block
                $buffy .= '</div>';

                $buffy .= '<div id=' . $this->block_uid . ' class="td_block_inner">';
                    $buffy .= $this->inner($this->td_query->posts, $td_column_number , $autoplay, false, $ud_ad_params); //Unixdev MOD
                $buffy .= '</div>';
            $buffy .= '</div> <!-- ./block1 -->';
        }
        return $buffy;
    }


    /**
     * @param $posts
     * @param string $td_column_number - get the column number
     * @param string $autoplay - not use via ajax
     * @param bool $is_ajax - if true the script will return the js inline, if not, it will use the td_js_buffer class
     * @return string
     */
    //Unixdev MOD : add $ad_params
    function inner($posts, $td_column_number = '', $autoplay = '', $is_ajax = false, $ad_params = array()) {
        $buffy = '';

        if (empty($td_column_number)) {
            $td_column_number = td_global::vc_get_column_number(); // get the column width of the block from the page builder API
        }

        $td_post_count = 0; // the number of posts rendered

        $td_unique_id_slide = td_global::td_generate_unique_id();
        $ud_rendered_ad_count = 0;

        //@generic class for sliders : td-theme-slider
        $buffy .= '<div id="' . $td_unique_id_slide . '" class="td-theme-slider iosSlider-col-' . $td_column_number . ' td_mod_wrap">';
            $buffy .= '<div class="td-slider ">';
                if (!empty($posts)) {
                    $post = null; //Unixdev MOD
                    //Unixdev MOD ---------
                    while ($td_post_count - $ud_rendered_ad_count < sizeof( $posts )) {
                        //$buffy .= td_modules::mod_slide_render($post, $td_column_number, $td_post_count);

                        $post = $posts[$td_post_count - $ud_rendered_ad_count];
                        if ( in_array( $td_post_count, array_keys( $ad_params ) ) ) {
                            if ( td_util::tdc_is_live_editor_iframe() or td_util::tdc_is_live_editor_ajax() ) {
                                continue;
                            }
                            $ad_spot_id = $ad_params[$td_post_count];
                            $td_module_unixdev_slide_1 = new td_module_unixdev_slide_1( $post ); //Unixdev MOD: $post fake parameter not use if render ads
                            $buffy .= $td_module_unixdev_slide_1->render( $td_column_number, $td_post_count, $td_unique_id_slide, $ad_spot_id );
                            $ud_rendered_ad_count++;
                            unset( $ad_params[$td_post_count] );
                        } else {
                            $td_module_unixdev_slide_1 = new td_module_unixdev_slide_1( $post );
                            $buffy .= $td_module_unixdev_slide_1->render( $td_column_number, $td_post_count, $td_unique_id_slide );
                        }

                        $td_post_count++;
                        // Show only the first frame in tagDiv composer
                        if ( td_util::tdc_is_live_editor_iframe() or td_util::tdc_is_live_editor_ajax() ) {
                            break;
                        }
                    }
                    //---------------------

                    //Unixdev MOD: if position offset is exceed posts count, put it to the end of slide
                    if ( ! td_util::tdc_is_live_editor_iframe() and ! td_util::tdc_is_live_editor_ajax() ) {
                        foreach ( $ad_params as $spot_id ) {
                            $td_module_unixdev_slide_1 = new td_module_unixdev_slide_1( $post ); //Unixdev MOD: $post fake parameter not use if render ads
                            $buffy .= $td_module_unixdev_slide_1->render( $td_column_number, $td_post_count, $td_unique_id_slide, $spot_id );
                            $td_post_count ++;
                        }
                    }
                    //----------------
                }
            $buffy .= '</div>'; //close slider

            $buffy .= '<i class = "td-icon-left prevButton"></i>';
            $buffy .= '<i class = "td-icon-right nextButton"></i>';

        $buffy .= '</div>'; //close ios

	    // Suppress any iosSlider in tagDiv composer
	    if (td_util::tdc_is_live_editor_iframe() or td_util::tdc_is_live_editor_ajax()) {
		    return $buffy;
	    }

	    //Unixdev MOD: set image ratio ----
        $ud_image_width = 0;
        $ud_image_height = 0;
        switch ($td_column_number) {
            case '1': //one column layout
                // td_356x356
                $ud_image_width = 356;
                $ud_image_height = 356;
                break;
            case '2': //two column layout
                // td_728x728
                $ud_image_width = 728;
                $ud_image_height = 728;
                break;
            case '3': //three column layout
                // td_1100x1100
                $ud_image_width = 1100;
                $ud_image_height = 1100;
                break;
        }
        //--------------------------------

        if (!empty($autoplay)) {
            $autoplay_string =  '
            autoSlide: true,
            autoSlideTimer: ' . $autoplay * 1000 . ',
            ';
        } else {
            $autoplay_string = '';
        }

        //add resize events
        //$add_js_resize = '';
        //if($td_column_number > 1) {
        // Unixdev MOD
        $add_js_resize = ',
                onSliderLoaded : ud_on_slider_loaded,
                onSliderResize : (tdDetect.isAndroid)?ud_update_slider:ud_resize_normal_slide';
        //}


        $slide_js = '
jQuery(document).ready(function() {
    
    jQuery("#' . $td_unique_id_slide . '").data("ud_image_size", {width: ' . $ud_image_width . ', height: ' . $ud_image_height . '} );
    
    jQuery("#' . $td_unique_id_slide . '").iosSlider({
        snapToChildren: true,
        desktopClickDrag: true,
        keyboardControls: false,
        responsiveSlideContainer: true,
        responsiveSlides: true,
        ' . $autoplay_string. '

        infiniteSlider: true,
        navPrevSelector: jQuery("#' . $td_unique_id_slide . ' .prevButton"),
        navNextSelector: jQuery("#' . $td_unique_id_slide . ' .nextButton")
        ' . $add_js_resize . '
    });
});
    ';

        if ($is_ajax) {
            $buffy .= '<script>' . $slide_js . '</script>';
        } else {
            td_js_buffer::add_to_footer($slide_js);
        }

        return $buffy;
    }
}

<?php

/**
 * Class td_block_unixdev_letter_1
 */
class td_block_unixdev_letter_1 extends td_block {

    function render($atts, $content = null){
        parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        // Unixdev MOD
        if ( empty( $atts['td_column_number'] ) ) {
            $td_column_number = td_global::vc_get_column_number();
        }else {
            $td_column_number = $atts['td_column_number'];
        }
        //-----------

        $buffy = ''; //output buffer

        $buffy .= '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

	        //get the block js
		    $buffy .= $this->get_block_css();

		    //get the js for this block
		    $buffy .= $this->get_block_js();


            //get the block title
            $buffy .= $this->get_block_title();

            //get the sub category filter for this block
            $buffy .= $this->get_pull_down_filter();

            $buffy .= '<div id=' . $this->block_uid . ' class="td_block_inner">';
                //Unixdev MOD: explicit specify $td_column_number for forcing number of column
                $buffy .= $this->inner( $this->td_query->posts, $td_column_number ); //inner content of the block
                // Unixdev MOD
                if (!empty($atts['ud_block_adspot_bottom_id'])&& td_util::is_ad_spot_enabled($atts['ud_block_adspot_bottom_id']) ) {
                    $ud_adspot = td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => $atts['ud_block_adspot_bottom_id']));
                    $buffy .= $ud_adspot;
                }
                //--------------------


            $buffy .= '</div>';

            $ud_letter_mailto = '';
            if ( ! empty ($atts['ud_letter_mailto'])) {
                $ud_letter_mailto = $atts['ud_letter_mailto'];
            }

            //get the ajax pagination for this block
            $buffy .= $this->get_block_pagination();
            // Unixdev MOD
            $buffy .= '<div class="ud-button ud-button-sent-letter">';
                $buffy .= '<i class="ud-icon ud-icon-sent-letter"></i>';
                $buffy .= '<a href="mailto:'.esc_attr($ud_letter_mailto).'"><span>ส่งจดหมาย</span></a>';
            $buffy .= '</div>';
            //------------
        $buffy .= '</div> <!-- ./block -->';
        return $buffy;
    }

    function inner($posts, $td_column_number = '') {

        $buffy = '';

        $td_block_layout = new td_block_layout();
        if (empty($td_column_number)) {
            $td_column_number = td_global::vc_get_column_number(); // get the column width of the block from the page builder API
        }
        $td_post_count = 0; // the number of posts rendered
        $td_current_column = 1; //the current column
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $td_module_71 = new td_module_71($post);

                switch ($td_column_number) {

                    case '1': //one column layout
                        $buffy .= $td_block_layout->open12(); //added in 010 theme - span 12 doesn't use rows
                        $buffy .= $td_module_71->render($post);
                        $buffy .= $td_block_layout->close12();
                        break;

                    case '2': //two column layout
                        $buffy .= $td_block_layout->open_row();

                        $buffy .= $td_block_layout->open6();
                        $buffy .= $td_module_71->render($post);
                        $buffy .= $td_block_layout->close6();

                        if ($td_current_column == 2) {
                            $buffy .= $td_block_layout->close_row();
                        }
                        break;

                    case '3': //three column layout
                        $buffy .= $td_block_layout->open_row();

                        $buffy .= $td_block_layout->open4();
                        $buffy .= $td_module_71->render($post);
                        $buffy .= $td_block_layout->close4();

                        if ($td_current_column == 3) {
                            $buffy .= $td_block_layout->close_row();
                        }
                        break;
                }

                //current column
                if ($td_current_column == $td_column_number) {
                    $td_current_column = 1;
                } else {
                    $td_current_column++;
                }


                $td_post_count++;
            }
        }
        $buffy .= $td_block_layout->close_all_tags();
        return $buffy;
    }
}

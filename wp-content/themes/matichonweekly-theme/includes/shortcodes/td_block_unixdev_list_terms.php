<?php

class td_block_unixdev_list_terms extends td_block {


	/**
	 * Disable loop block features. This block does not use a loop and it dosn't need to run a query.
	 */
	function __construct() {
		parent::disable_loop_block_features();
	}



    function render($atts, $content = null){
        parent::render($atts);

        $buffy = '';

        extract(shortcode_atts(
            array(
                'limit' => '-1', // show only 6 categories by default
                'custom_title' => '',
                'custom_url' => '',
                'hide_title' => '',
                'header_color' => '',
                'ud_taxonomy_name' => 'post_tag',
            ), $atts));

        $term_args = array(
            'taxonomy' => $ud_taxonomy_name,
            'show_count' => true,
            'orderby' => 'name',
            'hide_empty' => false,
            'order' => 'ASC',
            'number' => $limit,
        );



        $terms = get_terms($term_args); // has a limit of 6 by default

        $td_block_layout = new td_block_layout();
        if (empty($td_column_number)) {
            $td_column_number = td_global::vc_get_column_number(); // get the column width of the block from the page builder API
        }

        $td_post_count = 0; // the number of posts rendered
        $td_current_column = 1; //the current column

        $count_per_column = ceil(count($terms)/3);


        $buffy .= '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';
		    //get the block js
		    $buffy .= $this->get_block_css();

            $buffy .= $this->get_block_title();

            $buffy .= '<div id=' . $this->block_uid . ' class="td_block_inner">';

            if (!empty($terms)) {
                $buffy .= $td_block_layout->open_row();
                switch ($td_column_number) {
                    case '1': //one column layout
                        $buffy .= $td_block_layout->open12(); //added in 010 theme - span 12 doesn't use rows
                        break;

                    case '2': //two column layout
                        $buffy .= $td_block_layout->open6(); //added in 010 theme - span 12 doesn't use rows
                        break;

                    case '3': //three column layout
                        $buffy .= $td_block_layout->open4();
                        break;
                }
                foreach ($terms as $term) {

//                    switch ($td_column_number) {

                        $buffy .= $this->ud_get_term_item( $term );

//                        case '1': //one column layout
//                            $buffy .= $td_block_layout->open12(); //added in 010 theme - span 12 doesn't use rows
//                            $buffy .= $this->ud_get_term_item( $term );
//                            $buffy .= $td_block_layout->close12();
//                            break;
//
//                        case '2': //two column layout
//                            $buffy .= $td_block_layout->open_row();
//
//                            $buffy .= $td_block_layout->open6(); //added in 010 theme - span 12 doesn't use rows
//                            $buffy .= $this->ud_get_term_item( $term );
//                            $buffy .= $td_block_layout->close6();
//
//                            if ( $td_current_column == 2 ) {
//                                $buffy .= $td_block_layout->close_row();
//                            }
//                            break;
//
//
//                        case '3': //three column layout
//                            $buffy .= $td_block_layout->open_row();
//
//                            $buffy .= $td_block_layout->open4();
//                            $buffy .= $this->ud_get_term_item( $term );
//                            $buffy .= $td_block_layout->close4();
//
//                            if ( $td_current_column == 3 ) {
//                                $buffy .= $td_block_layout->close_row();
//                            }
//                            break;
//                    }

                    //current column
                    $td_post_count++;

                    if ( $td_post_count == $count_per_column ) {
                        $td_post_count = 0;
                        switch ($td_column_number) {
                            case '1': //one column layout
                                $buffy .= $td_block_layout->close12();
                                $buffy .= $td_block_layout->open12(); //added in 010 theme - span 12 doesn't use rows
                                break;

                            case '2': //two column layout
                                $buffy .= $td_block_layout->close6();
                                $buffy .= $td_block_layout->open6(); //added in 010 theme - span 12 doesn't use rows
                                break;


                            case '3': //three column layout
                                $buffy .= $td_block_layout->close4();
                                $buffy .= $td_block_layout->open4(); //added in 010 theme - span 12 doesn't use rows
                                break;
                        }
                    }

                }
            }
            $buffy .= $td_block_layout->close_all_tags();

            $buffy .= '</div>';

        $buffy .= '</div> <!-- ./block -->';
        return $buffy;
    }

    private function ud_get_term_item($term){
        $buffy = '';
        $buffy .= '<div class="item-details">';
        $buffy .= '<a href="' . get_term_link($term) . '">' . $term->name .'</a>';
        $buffy .= '</div>';
        return $buffy;
    }

    function inner($posts, $td_column_number = '') {

    }
}
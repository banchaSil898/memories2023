<?php

class td_block_15 extends td_block {


    function render($atts, $content = null){
        parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        $td_column_number = $this->get_att('td_column_number'); //Unixdev MOD
        if (empty($td_column_number)) {
            $td_column_number = td_global::vc_get_column_number(); // get the column width of the block from the page builder API
        }

        $buffy = ''; //output buffer

        $buffy .= '<div class="' . $this->get_block_classes() . ' td-column-' . $td_column_number . ' td_block_padding" ' . $this->get_block_html_atts() . '>';

		    //get the block js
		    $buffy .= $this->get_block_css();

		    //get the js for this block
		    $buffy .= $this->get_block_js();

            // block title wrap
            $buffy .= '<div class="td-block-title-wrap">';
                $buffy .= $this->get_block_title(); //get the block title
                $buffy .= $this->get_pull_down_filter(); //get the sub category filter for this block
            $buffy .= '</div>';

	        $buffy .= '<div id=' . $this->block_uid . ' class="td_block_inner td-column-' . $td_column_number . '">';
	            $buffy .= $this->inner($this->td_query->posts, $td_column_number); //inner content of the block
	        $buffy .= '</div>';

	        //get the ajax pagination for this block
	        $buffy .= $this->get_block_pagination();

	        //Unixdev MOD: readmore wrap
            $buffy .= '<div class="ud_readmore_wrap">';
                $buffy .= $this->ud_get_readmore();
            $buffy .= '</div>';
            //--------------------------

        $buffy .= '</div> <!-- ./block -->';

        //fix issue - widget disappearing after the block
        $buffy .= '<div class="clearfix"></div>';

        return $buffy;
    }

    function inner($posts, $td_column_number = '') {

        $buffy = '';

        $td_block_layout = new td_block_layout();
        $td_post_count = 0; // the number of posts rendered


        if (!empty($posts)) {
            foreach ($posts as $post) {

                $td_module_mx4 = new td_module_mx4($post);

                switch ($td_column_number) {

                    case '1': //one column layout
                        if ($td_post_count == 0) {
                            $buffy .= '<div class="td-cust-row">';
                        }

                        $buffy .= $td_block_layout->open12(); //added in 010 theme - span 12 doesn't use rows
                        $buffy .= $td_module_mx4->render($post);
                        $buffy .= $td_block_layout->close12();

                        if ($td_post_count == 1) {
                            $buffy .= '</div>';
                            $td_post_count = -1;
                        }
                        break;

                    case '2': //two column layout
                        $buffy .= $td_block_layout->open_row();

                        $buffy .= $td_block_layout->open4();
                        $buffy .= $td_module_mx4->render($post);
                        $buffy .= $td_block_layout->close4();

                        if ($td_post_count == 2) {
                            $buffy .= $td_block_layout->close_row();
                        }
                        break;

                    case '3': //three column layout
                        $buffy .= $td_block_layout->open_row();

                        $buffy .= $td_block_layout->open4();
                        $buffy .= $td_module_mx4->render($post);
                        $buffy .= $td_block_layout->close4();

                        if ($td_post_count == 4) {
                            $buffy .= $td_block_layout->close_row();
                        }
                        break;
                }
                $td_post_count++;

                // close the row after 3(for 2 columns) or 5 posts(for 3 columns)
                if (($td_column_number == 2 and $td_post_count == 3) or ($td_column_number == 3 and $td_post_count == 5)) {
                    $td_post_count = 0;
                }
            }

            if (($td_column_number == 1 and $td_post_count == 1)) {
                $buffy .= '</div>';
            }
        }

        $buffy .= $td_block_layout->close_all_tags();
        return $buffy;
    }
}
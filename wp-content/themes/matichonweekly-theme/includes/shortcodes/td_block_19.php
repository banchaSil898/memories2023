<?php

class td_block_19 extends td_block {



    function render($atts, $content = null) {
        parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        $td_column_number = $this->get_att('td_column_number'); //Unixdev MOD
        if (empty($td_column_number)) {
            $td_column_number = td_global::vc_get_column_number(); // get the column width of the block from the page builder API
        }

        $buffy = ''; //output buffer

        $buffy .= '<div class="' . $this->get_block_classes() . ' td-column-' . $td_column_number . '" ' . $this->get_block_html_atts() . '>';

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
	            $buffy .= $this->inner($this->td_query->posts, $td_column_number);//inner content of the block
	        $buffy .= '</div>';

	        //get the ajax pagination for this block
	        $buffy .= $this->get_block_pagination();

	        //Unixdev MOD: readmore wrap
            $buffy .= '<div class="ud_readmore_wrap">';
                $buffy .= $this->ud_get_readmore();
            $buffy .= '</div>';
            //--------------------------

        $buffy .= '</div> <!-- ./block -->';
        return $buffy;
    }

    function inner($posts, $td_column_number = '') {

        $buffy = '';
        $td_block_layout = new td_block_layout();
        $td_post_count = 0; // the number of posts rendered
        $td_current_column = 1; //the current column


        if (!empty($posts)) {
            foreach ($posts as $post) {
                $td_module_mx1 = new td_module_mx1($post);
                $td_module_mx2 = new td_module_mx2($post);

                switch ($td_column_number) {

                    case '1': //one column layout
                        if ($td_post_count == 0) { //first post
                            $buffy .= $td_module_mx1->render();
                        } else {
                            $buffy .= $td_module_mx2->render();
                        }
                        break;

                    case '2': //two column layout
                        $buffy .= $td_block_layout->open_row();

                        if ($td_post_count <= 1) { // big posts
                            $buffy .= $td_block_layout->open6();
                            $buffy .= $td_module_mx1->render();
                            $buffy .= $td_block_layout->close6();
                        }

                        if ($td_post_count == 1) { //close big posts
                            $buffy .= $td_block_layout->close_row();
                        }

                        if ($td_post_count > 1) { //4th post (big posts are rendered)
                            $buffy .= $td_block_layout->open_row();

                            $buffy .= $td_block_layout->open6();
                            $buffy .= $td_module_mx2->render();
                            $buffy .= $td_block_layout->close6();

                            if ($td_current_column == 2) { // column 2
                                $buffy .= $td_block_layout->close_row();
                            }
                        }
                        break;

                    case '3': //three column layout
                        $buffy .= $td_block_layout->open_row();

                        if ($td_post_count <= 2) { // big posts
                            $buffy .= $td_block_layout->open4();
                            $buffy .= $td_module_mx1->render();
                            $buffy .= $td_block_layout->close4();
                        }

                        if ($td_post_count == 2) { //close big posts
                            $buffy .= $td_block_layout->close_row();
                        }

                        if ($td_post_count > 2) { //4th post (big posts are rendered)
                            $buffy .= $td_block_layout->open_row();

                            $buffy .= $td_block_layout->open4();
                            $buffy .= $td_module_mx2->render();
                            $buffy .= $td_block_layout->close4();

                            if ($td_current_column == 3) { // column 3
                                $buffy .= $td_block_layout->close_row();
                            }
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
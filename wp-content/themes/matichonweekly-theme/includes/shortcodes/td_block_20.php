<?php

/**
 * uses module mx3
 * Class td_block_20
 */
class td_block_20 extends td_block {


    function render($atts, $content = null){
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
        return $buffy;
    }

    function inner($posts, $td_column_number = '') {

        $buffy = '';

        if (!empty($posts)) {
            foreach ($posts as $post) {

                switch ($td_column_number) {
                    case '1':
                        $td_module_mx3 = new td_module_mx3($post);
                        $buffy .= $td_module_mx3->render($post);
                        break;

                    case '2':
                        $td_module_14 = new td_module_14($post);
                        $buffy .= $td_module_14->render($post);
                        break;

                    case '3':
                        //we use module 14 - maybe use a full screen one?
                        $td_module_14 = new td_module_14($post);
                        $buffy .= $td_module_14->render($post);
                        break;
                }
            }
        }
        return $buffy;
    }
}

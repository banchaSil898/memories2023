<?php

class td_nav_menu_edit_walker extends Walker_Nav_Menu_Edit {
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {


        $control_buffy = '';

        //read the menu setting from post meta (menu id, key, single)
        $td_mega_menu_cat = get_post_meta($item->ID, 'td_mega_menu_cat', true);
        $td_mega_menu_page_id = get_post_meta($item->ID, 'td_mega_menu_page_id', true);

        //Unixdev MOD: uncollapse mobile menu by default --------------------
        $ud_mobile_menu_default_uncollapse = get_post_meta($item->ID, 'ud_mobile_menu_default_uncollapse', true);
        $ud_mobile_menu_default_uncollapse_options = array(' - Disable - ' => '', 'Enable' => 'enable');
        $control_buffy .= '<p class="description description-wide"><br><br>';
            $control_buffy .= '<label>';
                $control_buffy .= 'Make this item uncollapse by default on mobile view';
            $control_buffy .= '</label>';
            $control_buffy .= '<select name="ud_mobile_menu_default_uncollapse[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
                foreach ($ud_mobile_menu_default_uncollapse_options as $label => $option_value) {
                    $control_buffy .= '<option value="' . $option_value . '"' . selected($ud_mobile_menu_default_uncollapse, $option_value, false) . '>' . $label . '</option>';
                }
            $control_buffy .= ' </select>';
        $control_buffy .= '</p>';
        //-------------------------------------------------------------------

        //Unixdev MOD: custom onclick handler -------------------------------
        $ud_custom_onclick_handler = get_post_meta($item->ID, 'ud_custom_onclick_handler', true);
        $control_buffy .= '<p class="description description-wide"><br><br>';
            $control_buffy .= '<label>';
                $control_buffy .= 'Custom onclick event handler';
            $control_buffy .= '</label>';
            $control_buffy .= '<textarea name="ud_custom_onclick_handler[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
                $control_buffy .= $ud_custom_onclick_handler;
            $control_buffy .= '</textarea>';
        $control_buffy .= '</p>';
        //-------------------------------------------------------------------

        //make the tree
        $td_category_tree = array_merge (array(' - Not mega menu - ' => ''), td_util::get_category2id_array(false));

        //make a new ui control ( dropdown )
        $control_buffy .= '<p class="description description-wide"><br><br>';
            $control_buffy .= '<label>';
                $control_buffy .= 'Make this a category mega menu';
            $control_buffy .= '</label>';
            $control_buffy .= '<select name="td_mega_menu_cat[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
                foreach ($td_category_tree as $category => $category_id) {
                    $control_buffy .= '<option value="' . $category_id . '"' . selected($td_mega_menu_cat, $category_id, false) . '>' . $category . '</option>';
                }
            $control_buffy .= ' </select>';
        $control_buffy .= '</p>';


        if (td_api_features::is_enabled('page_mega_menu') === true){
            $control_buffy .= '<br>OR<br>';

            //make a new ui control ( dropdown )
            $control_buffy .= '<p class="description description-wide">';

                $control_buffy .= '<label>';
                    $control_buffy .= 'Load a page in the menu (enter the page ID)';
                $control_buffy .= '</label><br>';
                $control_buffy .= '<input name="td_mega_menu_page_id[' . $item->ID . ']" type="text" value="' . $td_mega_menu_page_id . '" />';
                $control_buffy .= '<span class="td-wpa-info"><strong>Just a tip:</strong> If you choose to load a mega menu or a page, please do not add submenus to this item. The mega menu and mega page menu have to be the top most menu item. <a href="http://forum.tagdiv.com/menus-newsmag/" target="_blank">Read more</a></span>';


            $control_buffy .= '</p>';
        }

        //run the parent and add in $buffy (byref) our code via regex
        $buffy = '';
        parent::start_el($buffy, $item, $depth, $args, $id);
        $buffy = preg_replace('/(?=<div.*submitbox)/', $control_buffy, $buffy);



        $output .= $buffy;
    }
}

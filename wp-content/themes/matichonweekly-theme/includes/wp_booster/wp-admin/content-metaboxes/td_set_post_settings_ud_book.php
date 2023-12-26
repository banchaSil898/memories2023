<div class="td-page-options-tab-wrap">
    <div class="td-page-options-tab td-page-options-tab-active" data-panel-class="td-post-option-general"><a href="#">General</a></div>
    <?php//Unixdev MOD ?><div class="td-page-options-tab" data-panel-class="td-page-option-ads"><a href="#">Ads</a></div>
<!--    <div class="td-page-options-tab" data-panel-class="td-page-option-post-smart-list"><a href="#">Smart list</a></div>-->
<!--    <div class="td-page-options-tab" data-panel-class="td-page-option-post-review"><a href="#">Reviews</a></div>-->
    <div class="td-page-options-tab" data-panel-class="td-page-option-post-book-purchase"><a href="#">Book Purchase Info</a></div>
</div>


<div class="td-meta-box-inside">


    <!-- post option general -->
    <div class="td-page-option-panel td-post-option-general td-page-option-panel-active">

        <!-- sidebar position -->
        <div class="td-meta-box-row">
            <span class="td-page-o-custom-label">
                Sidebar position:
                <?php
                td_util::tooltip_html('
                        <h3>Sidebar position:</h3>
                        <p>For best results and easy to maintain websites we recommend that you set the sidebar from the <i>Primary category</i> of this post. That way if you have
                        multiple posts, when you change the category settings all the posts will match the category</p>
                        <ul>
                            <li><strong>This setting overrides</strong> the Theme panel setting from <i>Post settings > Default post template</i> and the <i>Category settings</i></li>
                            <li><strong>On default</strong> - the post will look at the primary category settings and it will try to get the position form there. If the primary category
                            does not have a custom sidebar position, the post will load the setting from <i>Template settings > Blog and posts template</i></li>

                        </ul>
                    ', 'right')
                ?>
            </span>
                <?php
                echo td_panel_generator::visual_select_o(array(
                    'ds' => 'td_post_theme_settings',
                    'item_id' => '',
                    'option_id' => 'td_sidebar_position',
                    'values' => array(
                        array('text' => '', 'title' => '', 'val' => '', 'class' => 'td-sidebar-position-default', 'img' => get_template_directory_uri() . '/images/panel/sidebar/sidebar-default.png'),
                        array('text' => '', 'title' => '', 'val' => 'sidebar_left', 'class' => 'td-sidebar-position-left', 'img' => get_template_directory_uri() . '/images/panel/sidebar/sidebar-left.png'),
                        array('text' => '', 'title' => '', 'val' => 'no_sidebar', 'class' => 'td-no-sidebar', 'img' => get_template_directory_uri() . '/images/panel/sidebar/sidebar-full.png'),
                        array('text' => '', 'title' => '', 'val' => 'sidebar_right', 'class' => 'td-sidebar-position-right','img' => get_template_directory_uri() . '/images/panel/sidebar/sidebar-right.png')
                    ),
                    'selected_value' => $mb->get_the_value('td_sidebar_position')
                ));
                ?>
        </div>


        <!-- custom sidebar -->
        <div class="td-meta-box-row">
            <span class="td-page-o-custom-label">
                Custom sidebar:
                <?php
                td_util::tooltip_html('
                        <h3>Custom sidebar:</h3>
                        <p>For best results and easy to maintain websites we recommend that you set the sidebar from the <i>Primary category</i> of this post. That way if you have
                        multiple posts, when you change the category settings all the posts will match the category</p>
                        <ul>
                            <li><strong>This setting overrides</strong> the Theme panel setting from <i>Post settings > Default post template</i> and the <i>Category settings</i></li>
                            <li><strong>On default</strong> - the post will look at the primary category settings and it will try to get the sidebar form there. If the primary category
                            does not have a custom sidebar, the post will load the setting from <i>Template settings > Blog and posts template</i></li>
                        </ul>
                    ', 'right')
                ?>
            </span>
            <?php
            echo td_panel_generator::sidebar_pulldown(array(
                'ds' => 'td_post_theme_settings',
                'item_id' => '',
                'option_id' => 'td_sidebar',
                'selected_value' => $mb->get_the_value('td_sidebar')
            ));
            ?>
        </div>





        <div class="td-meta-box-row">
            <?php $mb->the_field('td_subtitle'); ?>
            <span class="td-page-o-custom-label td_text_area_label">Subtitle:</span>
            <textarea name="<?php $mb->the_name(); ?>" class="td-textarea-subtitle"><?php $mb->the_value(); ?></textarea>
            <span class="td-page-o-info">This text will appear under the title</span>
        </div>

        <div class="td-meta-box-row">
            <?php $mb->the_field('td_quote_on_blocks'); ?>
            <span class="td-page-o-custom-label">Quote on blocks:</span>
            <input class="td-input-text-post-settings" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
            <span class="td-page-o-info">Show a quote (only when this article shows up in blocks that support quote and only on blocks that are on one column)</span>
        </div>

    </div> <!-- /post option general -->






    <!-- post option smart list -->
    <div class="td-page-option-panel td-page-option-post-smart-list">
            <!-- smart list -->
            <div class="td-meta-box-row">
                <span class="td-page-o-custom-label">
                    Use a smart list? :
                    <?php
                    td_util::tooltip_html('
                        <h3>Smart Lists:</h3>
                        <p>Using <i>Smart lists</i> you can transform your article in a list of items. Each item must have a title, an image and a description</p>
                        <p>How to make an item:</p>
                        <ul>
                            <li><strong>add a text wrapped in H3</strong> - this will be the title of the item</li>
                            <li><strong>add any picture</strong> from the media library</li>
                            <li>in a new paragraph below the picture, <strong>add some text</strong></li>
                            <li><i>repeat the last 3 steps for each item that you want to add</i></li>
                        </ul>

                        <p>The system will use the H3 from the tiles to split your article and make each individual slide or numbered item</p>
                    ', 'right')
                    ?>
                </span>

                <div class="td-inline-block-wrap">
                    <?php
                    echo td_panel_generator::visual_select_o(array(
                        'ds' => 'td_post_theme_settings',
                        'item_id' => '',
                        'option_id' => 'smart_list_template',
                        'values' => td_api_smart_list::_helper_td_smart_list_api_to_panel_values(),
                        'selected_value' => $mb->get_the_value('smart_list_template')
                    ));
                    ?>
                </div>
            </div>


            <!-- title tag -->
            <div class="td-meta-box-row">
                <span class="td-page-o-custom-label">
                    Title tags:
                    <?php
                    td_util::tooltip_html('
                        <h3>Smart lists title tags:</h3>
                        <p>Customize what tag is used for <i>Title</i> lookup. This setting is useful if for example, you already have articles that use H2 for items</p>
                    ', 'right')
                    ?>
                </span>
                <?php $mb->the_field('td_smart_list_h'); ?>
                <div class="td-select-style-overwrite td-inline-block-wrap">
                    <select name="<?php $mb->the_name(); ?>" class="td-panel-dropdown">
                        <option value="h1"<?php $mb->the_select_state('h1'); ?>>Heading 1 ( H1 tag )</option>
                        <option value="h2"<?php $mb->the_select_state('h2'); ?>>Heading 2 ( H2 tag )</option>
                        <option value="" <?php $mb->the_select_state(''); ?>>Heading 3 ( H3 tag )</option>
                        <option value="h4"<?php $mb->the_select_state('h4'); ?>>Heading 4 ( H4 tag )</option>
                        <option value="h5"<?php $mb->the_select_state('h5'); ?>>Heading 5 ( H5 tag )</option>
                        <option value="h6"<?php $mb->the_select_state('h6'); ?>>Heading 6 ( H6 tag )</option>
                    </select>
                </div>
                <span class="td-page-o-info">The tags that wrap the title of each Smart List item.</span>
            </div>


            <!-- smart list numbering -->
            <div class="td-meta-box-row">
                <span class="td-page-o-custom-label">
                    Smart list numbering:
                    <?php
                    td_util::tooltip('Change the sort order of the items', 'right')
                    ?>
                </span>
                <?php $mb->the_field('td_smart_list_order'); ?>
                <div class="td-select-style-overwrite td-inline-block-wrap">
                    <select name="<?php $mb->the_name(); ?>" class="td-panel-dropdown">
                        <option value=""<?php $mb->the_select_state(''); ?>>Descending (ex: 3, 2, 1)</option>
                        <option value="asc_1" <?php $mb->the_select_state('asc_1'); ?>>Ascending (ex: 1, 2, 3)</option>
                    </select>
                </div>
                <span class="td-page-o-info">The smart lists put a number on each item, select the counting method.</span>
            </div>
    </div> <!-- /post option smart list -->






    <!-- post option review -->
    <div class="td-page-option-panel td-page-option-post-review">

        <div class="td-meta-box-row">
            <span class="td-page-o-custom-label">Is product review?:</span>
            <?php $mb->the_field('has_review'); ?>
            <div class="td-select-style-overwrite td-inline-block-wrap">
                <select id="reviewSelector" name="<?php $mb->the_name(); ?>" class="td-panel-dropdown">
                    <option value="">No</option>
                    <option value="rate_stars"<?php $mb->the_select_state('rate_stars'); ?>>Stars</option>
                    <option value="rate_percent"<?php $mb->the_select_state('rate_percent'); ?>>Percentages</option>
                    <option value="rate_point"<?php $mb->the_select_state('rate_point'); ?>>Points</option>
                </select>
            </div>
        </div>


        <div class="rating_type rate_Stars">
            <p>
                <strong>Add star ratings for this product:</strong><br>
            </p>

            <?php while($mb->have_fields_and_multi('p_review_stars')): ?>
                <div class="td-meta-box-row">
                    <?php $mb->the_group_open(); ?>

                    <?php $mb->the_field('desc'); ?>
                    <span class="td-page-o-custom-label">Feature name:</span>
                    <input style="width: 200px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

                    <?php $mb->the_field('rate'); ?>

                    <select name="<?php $mb->the_name(); ?>">
                        <option value="">Select rating</option>
                        <option value="5"<?php $mb->the_select_state('5'); ?>>5 stars</option>
                        <option value="4.5"<?php $mb->the_select_state('4.5'); ?>>4.5 stars</option>
                        <option value="4"<?php $mb->the_select_state('4'); ?>>4 stars</option>
                        <option value="3.5"<?php $mb->the_select_state('3.5'); ?>>3.5 stars</option>
                        <option value="3"<?php $mb->the_select_state('3'); ?>>3 stars</option>
                        <option value="2.5"<?php $mb->the_select_state('2.5'); ?>>2.5 stars</option>
                        <option value="2"<?php $mb->the_select_state('2'); ?>>2 stars</option>
                        <option value="1.5"<?php $mb->the_select_state('1.5'); ?>>1.5 stars</option>
                        <option value="1"<?php $mb->the_select_state('1'); ?>>1 stars</option>
                        <option value="0.5"<?php $mb->the_select_state('0.5'); ?>>0.5 stars</option>
                    </select>
                    <a href="#" class="dodelete button">Delete</a>

                    <?php $mb->the_group_close(); ?>
                </div>
            <?php endwhile; ?>

            <p><a href="#" class="docopy-p_review_stars button">Add rating category</a></p>
        </div>



        <div class="rating_type rate_Percentages">
            <p>
                <strong>Add percent ratings for this product:</strong><br>
                <strong>Note:</strong> The percent range is between 0 and 100 (do not add the %)
            </p>
            <?php while($mb->have_fields_and_multi('p_review_percents')): ?>
                <div class="td-meta-box-row">
                    <?php $mb->the_group_open(); ?>

                    <?php $mb->the_field('desc'); ?>
                    <span class="td-page-o-custom-label">Feature name: </span><input style="width: 200px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

                    <?php $mb->the_field('rate'); ?>
                    - Percent:
                    <input style="width: 100px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>


                    <a href="#" class="dodelete button">Delete</a>

                    <?php $mb->the_group_close(); ?>
                </div>
            <?php endwhile; ?>

            <p><a href="#" class="docopy-p_review_percents button">Add rating category</a></p>
        </div>


        <div class="rating_type rate_Points">
            <p>
                <strong>Add points ratings for this product:</strong><br>
                <strong>Note:</strong> The points range is between 1 and 10
            </p>
            <?php while($mb->have_fields_and_multi('p_review_points')): ?>
                <div class="td-meta-box-row">
                    <?php $mb->the_group_open(); ?>

                    <?php $mb->the_field('desc'); ?>
                    <span class="td-page-o-custom-label">Feature name: </span><input style="width: 200px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

                    <?php $mb->the_field('rate'); ?>
                    - Points:
                    <input style="width: 100px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>


                    <a href="#" class="dodelete button">Delete</a>

                    <?php $mb->the_group_close(); ?>
                </div>
            <?php endwhile; ?>

            <p><a href="#" class="docopy-p_review_points button">Add rating category</a></p>
        </div>

        <div class="review_desc">
            <div><strong>Review description:</strong></div>
            <p class="td_help_section">
                <?php $mb->the_field('review'); ?>

                <textarea class="td-textarea-subtitle" type="text" name="<?php $mb->the_name(); ?>"><?php $mb->the_value(); ?></textarea>
            </p>
        </div>



        <script>
            jQuery().ready(function() {
                td_updateMetaboxes();

                jQuery('#reviewSelector').change(function() {
                    td_updateMetaboxes();
                });

                function td_updateMetaboxes() {
                    var cur_selection = jQuery('#reviewSelector option:selected').text();

                    if(cur_selection.indexOf("No") !== -1) {
                        //alert('ra');
                        jQuery('.rating_type').hide();
                        jQuery('.review_desc').hide();

                    } else {
                        jQuery('.rating_type').hide();
                        jQuery('.rate_' + cur_selection).show();
                        jQuery('.review_desc').show();
                        //alert(cur_selection);
                    }



                }
            }); //end on load
        </script>
    </div> <!-- /post option review -->

    <div class="td-page-option-panel td-page-option-ads">
        <p>
            <strong>Note:</strong> Disable/enable ads per post
        </p>

        <?php
        $ud_post_page_ad_spot_ids = array(
            'b1x_post'           => 'header',
            'b1y_post'           => 'ud_b1y_post_ad',
            'b2_post'            => 'ud_b2x_post_ad',
            'b3_post'            => 'ud_b3x_post_ad',
            'b4_post'            => 'ud_b4x_post_ad',
            // 'b5_post' => 'content_inline', // already disabled by logic
            'b5_2_post'          => 'content_inline_2',
            'b6_post'            => 'ud_b6x_post_ad',
            'footer_sticky_post' => 'ud_footer_sticky_post_ad',
            'takeover_post'      => 'ud_takeover_post_ad',
            'custom_ad_1'        => 'custom_ad_1',
            'custom_ad_2'        => 'custom_ad_2',
            'custom_ad_3'        => 'custom_ad_3',
            'custom_ad_4'        => 'custom_ad_4',
            'custom_ad_5'        => 'custom_ad_5',
            'taboola_ad'         => 'ud_taboola_ad',
        );

        $ud_labels = array_keys($ud_post_page_ad_spot_ids);

        ?>

        <?php while($mb->have_fields('ud_disabled_ads',sizeof($ud_post_page_ad_spot_ids))): ?>
            <?php
            $label = $ud_labels[ $mb->get_the_index() ];
            $spot_id = $ud_post_page_ad_spot_ids[ $ud_labels[ $mb->get_the_index() ] ];
            $ud_disabled_ads = $mb->get_the_value( null, true );
            if ( empty( $ud_disabled_ads ) ) {
                $ud_disabled_ads = array();
            }
            ?>
            <div class="td-meta-box-row">
                <span class="td-page-o-custom-label"><?php echo $label ?></span>
                <div class="td-select-style-overwrite td-inline-block-wrap">
                    <select name="<?php $mb->the_name(); ?>" class="td-panel-dropdown">
                        <option value="">Inherit from Global</option>
                        <option value="<?php echo $spot_id?>"<?php echo (in_array($spot_id, $ud_disabled_ads))?' selected="selected"': ''; ?>>- Disabled -</option>
                    </select>
                </div>
            </div>
        <?php endwhile; ?>
    </div>


    <!-- post option book purchase -->
    <div class="td-page-option-panel td-page-option-post-book-purchase">

        <div class="td-meta-box-row">
            <?php $mb->the_field( 'ud_purchase_book_ookbee_url' ); ?>
            <span class="td-page-o-custom-label">
                Ookbee url:
                <?php
                td_util::tooltip_html( '
                        <h3>Ookbee url:</h3>
                        <p>ookee url</p>
                    ', 'right' )
                ?>
            </span>
            <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
            <span class="td-page-o-info">ookee url</span>
        </div>

        <div class="td-meta-box-row">
            <?php $mb->the_field( 'ud_purchase_book_ngandee_url' ); ?>
            <span class="td-page-o-custom-label">
                Ngandee url:
                <?php
                td_util::tooltip_html( '
                        <h3>Ngandee url:</h3>
                        <p>ngandee url</p>
                    ', 'right' )
                ?>
            </span>
            <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
            <span class="td-page-o-info">ngandee url</span>
        </div>


    </div> <!-- /post option book purchase -->

</div>

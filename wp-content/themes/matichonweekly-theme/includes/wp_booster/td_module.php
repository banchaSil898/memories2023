<?php
abstract class td_module {
    var $post;

    var $title_attribute;
    var $title;             // by default the WordPress title is not escaped on twenty fifteen
    var $href;


    /**
     * @var mixed the review metadata - we get it for each $post
     */
    protected $td_review;

	/**
	 * @var bool is true if we have a review for this $post
	 */
	protected $is_review = false;

    /**
     * @var int|null Contains the id of the current $post thumbnail. If no thumbnail is found, the value is NULL
     */
    protected $post_thumb_id = NULL;


    /**
     * @param $post WP_Post
     * @throws ErrorException
     */
    function __construct($post) {
        if (gettype($post) != 'object' or get_class($post) != 'WP_Post') {
            td_util::error(__FILE__, 'td_module: ' . get_Class($this) . '($post): $post is not WP_Post');
        }


        //this filter is used by td_unique_posts.php - to add unique posts to the array for the datasource
        apply_filters("td_wp_booster_module_constructor", $this, $post);

        $this->post = $post;

        // by default the WordPress title is not escaped on twenty fifteen
        $this->title = get_the_title($post->ID);
        $this->title_attribute = esc_attr(strip_tags($this->title));
        $this->href = esc_url(get_permalink($post->ID));

        if (has_post_thumbnail($this->post->ID)) {
            $tmp_get_post_thumbnail_id = get_post_thumbnail_id($this->post->ID);
            if (!empty($tmp_get_post_thumbnail_id)) {
                // if we have a wrong id, leave the post_thumb_id NULL
                $this->post_thumb_id = $tmp_get_post_thumbnail_id;
            }
        }

        //Unixdev: if post is attachment then thumb is ID itself
        if ( 'attachment' === $this->post->post_type ) {
            $this->post_thumb_id = $this->post->ID;
        }

        //get the review metadata
        //$this->td_review = get_post_meta($this->post->ID, 'td_review', true); @todo $this->td_review variable name must be replaced and the 'get_quotes_on_blocks', 'get_category' methods also
	    $this->td_review = td_util::get_post_meta_array($this->post->ID, 'td_post_theme_settings');

	    if (!empty($this->td_review['has_review']) and (
			    !empty($this->td_review['p_review_stars']) or
			    !empty($this->td_review['p_review_percents']) or
			    !empty($this->td_review['p_review_points'])
		    )
	    ) {
		    $this->is_review = true;
	    }
    }


    /**
     * @deprecated - google changed the structured data requirements and we no longer use them on modules
     */
    function get_item_scope() {
        return '';
    }


    /**
     * @deprecated - google changed the structured data requirements and we no longer use them on modules
     */
    function get_item_scope_meta() {
        return '';
    }


    function get_module_classes($additional_classes_array = '') {
        //add the wrap and module id class
        $buffy = get_class($this);


	    // each module setting has a 'class' key to customize css
	    $module_class = td_api_module::get_key(get_class($this), 'class');

	    if ($module_class != '') {
		    $buffy .= ' ' . $module_class;
	    }


        //show no thumb only if no thumb is detected and image placeholders are disabled
        if (is_null($this->post_thumb_id) and td_util::get_option('tds_hide_featured_image_placeholder') == 'hide_placeholder') {
            $buffy .= ' td_module_no_thumb';
        }

        // fix the meta info space when all options are off
        if (td_util::get_option('tds_m_show_author_name') == 'hide' and td_util::get_option('tds_m_show_date') == 'hide' and td_util::get_option('tds_m_show_comments') == 'hide') {
            $buffy .= ' td-meta-info-hide';
        }

	    if ($additional_classes_array != '' && is_array($additional_classes_array)) {
		    $buffy .= ' ' . implode(' ', $additional_classes_array);
	    }

	    // the following case could not be checked
	    // $buffy = implode(' ', array_unique(explode(' ', $buffy)));

        return $buffy;
    }


    function get_author() {
        $buffy = '';

        if ($this->is_review === false) {
            if (td_util::get_option('tds_m_show_author_name') != 'hide') {
                $buffy .= '<span class="td-post-author-name">';
                //Unixdev MOD: force author display name
                $terms = array();
                if ( td_global::$is_ud_columnist_manager_activated ) {
                    $terms = get_the_terms( $this->post->ID, UDColumnistManager\ColumnistManager::TAXONOMY_NAME );
                }

                if ( ! empty( $terms[0] ) ) {
                    $author_name = $terms[0]->name;
                    $author_link = get_term_link( $terms[0], UDColumnistManager\ColumnistManager::TAXONOMY_NAME );
                } else {
                    $ud_article_references = get_post_meta( $this->post->ID, 'ud_article_references', true );
                    if ( ! empty( $ud_article_references['author_name'] ) ) {
                        $author_name = $ud_article_references['author_name'];
                        $author_link = isset( $ud_article_references['author_url'] ) ? $ud_article_references['author_url'] : '';
                    } else if ( ! empty( td_global::$ud_force_author ) and is_a( td_global::$ud_force_author, 'WP_User' ) ) {
                        $author_name = td_global::$ud_force_author->get( 'display_name' );
                    } else {
                        $author_name = get_the_author_meta( 'display_name', $this->post->post_author );
                        $author_link = get_author_posts_url( $this->post->post_author );
                    }
                }

                if ( ! empty( $author_link ) ) {
                    $buffy .= '<a href="' . esc_url( $author_link ) . '">' . esc_html( $author_name ) . '</a>';
                } else {
                    $buffy .= esc_html( $author_name );
                }
                //--------------------------------
                if (td_util::get_option('tds_m_show_author_name') != 'hide' and td_util::get_option('tds_m_show_date') != 'hide') {
                    $buffy .= ' <span>-</span> ';
                }
                $buffy .= '</span>';
            }

        }
        return $buffy;

    }


    function get_date($show_stars_on_review = true) {
        $visibility_class = '';
        if (td_util::get_option('tds_m_show_date') == 'hide') {
            $visibility_class = ' td-visibility-hidden';
        }

        $buffy = '';
        if ($this->is_review and $show_stars_on_review === true) {
            //if review show stars
            $buffy .= '<div class="entry-review-stars">';
            $buffy .=  td_review::render_stars($this->td_review);
            $buffy .= '</div>';

        } else {
            if (td_util::get_option('tds_m_show_date') != 'hide') {
                $td_article_date_unix = get_the_time('U', $this->post->ID);
                $buffy .= '<span class="td-post-date">';
                    $buffy .= '<time class="entry-date updated td-module-date' . $visibility_class . '" datetime="' . date(DATE_W3C, $td_article_date_unix) . '" >' . get_the_time(get_option('date_format'), $this->post->ID) . '</time>';
                $buffy .= '</span>';
            }
        }

        return $buffy;
    }

    function get_comments() {
        $buffy = '';
        if (td_util::get_option('tds_m_show_comments') != 'hide') {
            $buffy .= '<div class="td-module-comments">';
                $buffy .= '<a href="' . get_comments_link($this->post->ID) . '">';
                    $buffy .= get_comments_number($this->post->ID);
                $buffy .= '</a>';
            $buffy .= '</div>';
        }

        return $buffy;
    }



    /**
     * get image - v 3.0  23 ian 2015
     * @param $thumbType
     * @return string
     */
    function get_image($thumbType, $css_image = false) {
        $buffy = ''; //the output buffer
        $tds_hide_featured_image_placeholder = td_util::get_option('tds_hide_featured_image_placeholder');
        //retina image
        $srcset_sizes = '';

        // do we have a post thumb or a placeholder?
        if (!is_null($this->post_thumb_id) or ($tds_hide_featured_image_placeholder != 'hide_placeholder')) {

            if (!is_null($this->post_thumb_id)) {
                //if we have a thumb
                // check to see if the thumb size is enabled in the panel, we don't have to check for the default wordpress
                // thumbs (the default ones are already cut and we don't have  a panel setting for them)
                if (td_util::get_option('tds_thumb_' . $thumbType) != 'yes' and $thumbType != 'thumbnail') {
                    //the thumb is disabled, show a placeholder thumb from the theme with the "thumb disabled" message
                    global $_wp_additional_image_sizes;

                    if (empty($_wp_additional_image_sizes[$thumbType]['width'])) {
                        $td_temp_image_url[1] = '';
                    } else {
                        $td_temp_image_url[1] = $_wp_additional_image_sizes[$thumbType]['width'];
                    }

                    if (empty($_wp_additional_image_sizes[$thumbType]['height'])) {
                        $td_temp_image_url[2] = '';
                    } else {
                        $td_temp_image_url[2] = $_wp_additional_image_sizes[$thumbType]['height'];
                    }

					// For custom wordpress sizes (not 'thumbnail', 'medium', 'medium_large' or 'large'), get the image path using the api (no_image_path)
	                $thumb_disabled_path = td_global::$get_template_directory_uri;
	                if (strpos($thumbType, 'td_') === 0) {
			            $thumb_disabled_path = td_api_thumb::get_key($thumbType, 'no_image_path');
		            }
			        $td_temp_image_url[0] = $thumb_disabled_path . '/images/thumb-disabled/' . $thumbType . '.png';

                    $attachment_alt = 'alt=""';
                    $attachment_title = '';

                } else {
                    // the thumb is enabled from the panel, it's time to show the real thumb
                    $td_temp_image_url = wp_get_attachment_image_src($this->post_thumb_id, $thumbType);
                    $attachment_alt = get_post_meta($this->post_thumb_id, '_wp_attachment_image_alt', true );
                    $attachment_alt = 'alt="' . esc_attr(strip_tags($attachment_alt)) . '"';
                    $attachment_title = ' title="' . esc_attr(strip_tags($this->title)) . '"';

                    if (empty($td_temp_image_url[0])) {
                        $td_temp_image_url[0] = '';
                    }

                    if (empty($td_temp_image_url[1])) {
                        $td_temp_image_url[1] = '';
                    }

                    if (empty($td_temp_image_url[2])) {
                        $td_temp_image_url[2] = '';
                    }

                    //retina image
                    //don't display srcset_sizes on DEMO - it messes up Pagespeed score (8 March 2017)
                    if (TD_DEPLOY_MODE != 'demo') {
                        $srcset_sizes = td_util::get_srcset_sizes($this->post_thumb_id, $thumbType, $td_temp_image_url[1], $td_temp_image_url[0]);
                    }

                } // end panel thumb enabled check



            } else {
                //we have no thumb but the placeholder one is activated
                global $_wp_additional_image_sizes;

                if (empty($_wp_additional_image_sizes[$thumbType]['width'])) {
                    $td_temp_image_url[1] = '';
                } else {
                    $td_temp_image_url[1] = $_wp_additional_image_sizes[$thumbType]['width'];
                }

                if (empty($_wp_additional_image_sizes[$thumbType]['height'])) {
                    $td_temp_image_url[2] = '';
                } else {
                    $td_temp_image_url[2] = $_wp_additional_image_sizes[$thumbType]['height'];
                }

                /**
                 * get thumb height and width via api
                 * first we check the global in case a custom thumb is used
                 *
                 * The api thumb is checked only for additional sizes registered and if at least one of the settings (width or height) is empty.
                 * This should be enough to avoid getting a non existing id using api thumb.
                 */
	            if (!empty($_wp_additional_image_sizes) && array_key_exists($thumbType, $_wp_additional_image_sizes) && ($td_temp_image_url[1] == '' || $td_temp_image_url[2] == '')) {
                    $td_thumb_parameters = td_api_thumb::get_by_id($thumbType);
	                $td_temp_image_url[1] = $td_thumb_parameters['width'];
                    $td_temp_image_url[2] = $td_thumb_parameters['height'];
                }

	            // For custom wordpress sizes (not 'thumbnail', 'medium', 'medium_large' or 'large'), get the image path using the api (no_image_path)
	            $no_thumb_path = td_global::$get_template_directory_uri;
	            if (strpos($thumbType, 'td_') === 0) {
		            $no_thumb_path = rtrim(td_api_thumb::get_key($thumbType, 'no_image_path'), '/');
	            }
		        $td_temp_image_url[0] = $no_thumb_path . '/images/no-thumb/' . $thumbType . '.png';

                $attachment_alt = 'alt=""';
                $attachment_title = '';
            } //end    if ($this->post_has_thumb) {



            $buffy .= '<div class="td-module-thumb">';
                if (current_user_can('edit_posts')) {
                    $buffy .= '<a class="td-admin-edit" href="' . get_edit_post_link($this->post->ID) . '">edit</a>';
                }


                $buffy .= '<a href="' . $this->href . '" rel="bookmark" title="' . $this->title_attribute . '">';

                    // css image
                    if ($css_image === true) {
                        // retina image
                        if (td_util::get_option('tds_thumb_' . $thumbType . '_retina') == 'yes' && !empty($td_temp_image_url[1])) {
                            $retina_url = wp_get_attachment_image_src($this->post_thumb_id, $thumbType . '_retina');
                            if (!empty($retina_url[0])) {
                                $td_temp_image_url[0] = $retina_url[0];
                            }
                        }
                        $buffy .= '<span class="entry-thumb td-thumb-css" style="background-image: url(' . $td_temp_image_url[0] . ')"></span>';

                    // normal image
                    } else {
                        $buffy .= '<img width="' . $td_temp_image_url[1] . '" height="' . $td_temp_image_url[2] . '" class="entry-thumb" src="' . $td_temp_image_url[0] . '"' . $srcset_sizes . ' ' . $attachment_alt . $attachment_title . '/>';
                    }

                    // on videos add the play icon
                    if (get_post_format($this->post->ID) == 'video') {

                        $use_small_post_format_icon_size = false;
                        // search in all the thumbs for the one that we are currently using here and see if it has post_format_icon_size = small
                        foreach (td_api_thumb::get_all() as $thumb_from_thumb_list) {
                            if ($thumb_from_thumb_list['name'] == $thumbType and $thumb_from_thumb_list['post_format_icon_size'] == 'small') {
                                $use_small_post_format_icon_size = true;
                                break;
                            }
                        }

                        // load the small or medium play icon
                        if ($use_small_post_format_icon_size === true) {
                            $buffy .= '<span class="td-video-play-ico td-video-small"><img width="20" height="20" class="td-retina" src="' . td_global::$get_template_directory_uri . '/images/icons/video-small.png' . '" alt="video"/></span>';
                        } else {
                            $buffy .= '<span class="td-video-play-ico"><img width="40" height="40" class="td-retina" src="' . td_global::$get_template_directory_uri . '/images/icons/ico-video-large.png' . '" alt="video"/></span>';
                        }
                    } // end on video if

                $buffy .= '</a>';
            $buffy .= '</div>'; //end wrapper

            return $buffy;
        }
    }



    /**
     * This function returns the title with the appropriate markup.
     * @param string $cut_at - if provided, the method will just cut at that point
     * and it will cut after that. If not setting is in the database the function will cut at the default value
     * @return string
     */

    function get_title($cut_at = '') {
        $buffy = '';
        $buffy .= '<h3 class="entry-title td-module-title">';
        $buffy .='<a href="' . $this->href . '" rel="bookmark" title="' . $this->title_attribute . '">';

        //see if we have to cut the title and if we have the title lenght in the panel for ex: td_module_6__title_excerpt
        if ($cut_at != '') {
            //cut at the hard coded size
            $buffy .= td_util::excerpt($this->title, $cut_at, 'show_shortcodes');

        } else {
            $current_module_class = get_class($this);

            //see if we have a default setting for this module, and if so only apply it if we don't get other things form theme panel.
            if (td_api_module::_helper_check_excerpt_title($current_module_class)) {
                $db_title_excerpt = td_util::get_option($current_module_class . '_title_excerpt');
                if ($db_title_excerpt != '') {
                    //cut from the database settings
                    $buffy .= td_util::excerpt($this->title, $db_title_excerpt, 'show_shortcodes');
                } else {
                    //cut at the default size
                    $module_api = td_api_module::get_by_id($current_module_class);
                    $buffy .= td_util::excerpt($this->title, $module_api['excerpt_title'], 'show_shortcodes');
                }
            } else {
                /**
                 * no $cut_at provided and no setting in td_config -> return the full title
                 * @see td_global::$modules_list
                 */
                $buffy .= $this->title;
            }

        }
        $buffy .='</a>';
        $buffy .= '</h3>';
        return $buffy;
    }


    /**
     * This method is used by modules to get content that has to be excerpted (cut)
     * IT RETURNS THE EXCERPT FROM THE POST IF IT'S ENTERED IN THE EXCERPT CUSTOM POST FIELD BY THE USER
     * @param string $cut_at - if provided the method will just cat at that point
     * @return string
     */
    function get_excerpt($cut_at = '') {

        //If the user supplied the excerpt in the post excerpt custom field, we just return that
        if ($this->post->post_excerpt != '') {
            return $this->post->post_excerpt;
        }

        $buffy = '';
        if ($cut_at != '') {
            // simple, $cut_at and return
            $buffy .= td_util::excerpt($this->post->post_content, $cut_at);
        } else {
            $current_module_class = get_class($this);

            //see if we have a default setting for this module, and if so only apply it if we don't get other things form theme panel.
            if (td_api_module::_helper_check_excerpt_content($current_module_class)) {
                $db_content_excerpt = td_util::get_option($current_module_class . '_content_excerpt');
                if ($db_content_excerpt != '') {
                    //cut from the database settings
                    $buffy .= td_util::excerpt($this->post->post_content, $db_content_excerpt);
                } else {
                    //cut at the default size
                    $module_api = td_api_module::get_by_id($current_module_class);
                    $buffy .= td_util::excerpt($this->post->post_content, $module_api['excerpt_content']);
                }
            } else {
                /**
                 * no $cut_at provided and no setting in td_config -> return the full $this->post->post_content
                 * @see td_global::$modules_list
                 */
                $buffy .= $this->post->post_content;
            }
        }
        return $buffy;
    }



    function get_category() {

        $buffy = '';
	    $selected_category_obj = '';
	    $selected_category_obj_id = '';
	    $selected_category_obj_name = '';

	    $current_post_type = get_post_type($this->post->ID);
	    $builtin_post_types = get_post_types(array('_builtin' => true));

	    if (array_key_exists($current_post_type, $builtin_post_types)) {

		    // default post type

		    //read the post meta to get the custom primary category
		    $td_post_theme_settings = td_util::get_post_meta_array($this->post->ID, 'td_post_theme_settings');
		    if (!empty($td_post_theme_settings['td_primary_cat'])) {
			    //we have a custom category selected
			    $selected_category_obj = get_category($td_post_theme_settings['td_primary_cat']);
		    } else {

			    //get one auto
			    $categories = get_the_category($this->post->ID);

			    if (is_category()) {
				    foreach ($categories as $category) {
					    if ($category->term_id == get_query_var('cat')) {
						    $selected_category_obj = $category;
						    break;
					    }
				    }
			    }

			    if (empty($selected_category_obj) and !empty($categories[0])) {
				    if ($categories[0]->name === TD_FEATURED_CAT and !empty($categories[1])) {
					    $selected_category_obj = $categories[1];
				    } else {
					    $selected_category_obj = $categories[0];
				    }
			    }
		    }

		    if (!empty($selected_category_obj)) {
			    $selected_category_obj_id = $selected_category_obj->cat_ID;
			    $selected_category_obj_name = $selected_category_obj->name;
		    }

	    } else {

		    // custom post type

		    // Validate that the current queried term is a term
		    global $wp_query;
		    $current_queried_term = $wp_query->get_queried_object();

		    if ( $current_queried_term instanceof WP_Term ) {
			    $current_term = term_exists( $current_queried_term->name, $current_queried_term->taxonomy );

			    if ($current_term !== 0 && $current_term !== null) {
				    $selected_category_obj = $current_queried_term;
			    }
		    }


		    // Get and validate the custom taxonomy according to the validated queried term
		    if (!empty($selected_category_obj)) {

			    $taxonomy_objects = get_object_taxonomies($this->post, 'objects');
			    $custom_taxonomy_object = '';

			    foreach ($taxonomy_objects as $taxonomy_object) {

				    if ($taxonomy_object->_builtin !== 1 && $taxonomy_object->name === $selected_category_obj->taxonomy) {
					    $custom_taxonomy_object = $taxonomy_object;
					    break;
				    }
			    }

			    // Invalid taxonomy
			    if (empty($custom_taxonomy_object)) {
				    return $buffy;
			    }

			    $selected_category_obj_id = $selected_category_obj->term_id;
			    $selected_category_obj_name = $selected_category_obj->name;
		    }
	    }

	    if (!empty($selected_category_obj_id) && !empty($selected_category_obj_name)) { //@todo catch error here
		    $buffy .= '<a href="' . get_category_link($selected_category_obj_id) . '" class="td-post-category">'  . $selected_category_obj_name . '</a>' ;
	    }

        //return print_r($post, true);
        return $buffy;
    }


    //get quotes on blocks
    function get_quotes_on_blocks() {

        // do not show the quote on WordPress loops
        if (td_global::$is_wordpress_loop === true or td_global::vc_get_column_number() != 1) {
            return '';
        }


        //get quotes data from database
        $post_data_from_db = td_util::get_post_meta_array($this->post->ID, 'td_post_theme_settings');

        if(!empty($post_data_from_db['td_quote_on_blocks'])) {
            return '<div class="td_quote_on_blocks">' . $post_data_from_db['td_quote_on_blocks'] . '</div>';
        }
    }

    //Unixdev MOD
    function ud_get_social_button(){

        $twitter_user = td_util::get_option('tds_tweeter_username');

        $buffy = '';
        $buffy .= '<div class="ud-module-social-sharing">';
        $buffy .= '<ul>';
        $buffy .= '<li>';
        $buffy .= '<a class="td-social-sharing-buttons td-social-facebook" href="http://www.facebook.com/sharer.php?u=' . urlencode( esc_url( get_permalink($this->post) ) ) . '" onclick="window.open(this.href, \'mywin\',\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="td-icon-facebook"></i><div class="td-social-but-text">Facebook</div></a>';
        $buffy .= '</li>';
        $buffy .= '<li>';
        $buffy .= '<a class="td-social-sharing-buttons td-social-twitter" href="https://twitter.com/intent/tweet?text=' . htmlspecialchars(urlencode(html_entity_decode($this->post->title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') . '&url=' . urlencode( esc_url( get_permalink($this->post)) ) . '&via=' . urlencode( $twitter_user ? $twitter_user : get_bloginfo( 'name' ) ) . '"  ><i class="td-icon-twitter"></i><div class="td-social-but-text">Twitter</div></a>';
        $buffy .= '</li>';
        $buffy .= '</ul>';
        $buffy .= '</div>';

        return $buffy;
    }
}
<?php

namespace UDColumnistManager\Admin;


use UDColumnistManager\ColumnistManager;

if (! defined('ABSPATH')) {
    exit;
}


class ColumnistMetaBox
{
    public function __construct()
    {
        if (is_admin()) {
            add_action('add_meta_boxes', array($this, 'addMetaboxes'));
            add_action('wp_ajax_ud_cm_search_columnist', array($this, 'searchColumnist'));
            add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        }

        add_action('save_post_post', array($this, 'savePost'), 10, 3);
        add_action('save_post_' . ColumnistManager::POST_TYPE_NAME, array($this, 'syncColumnistTermData'), 10, 3);
        add_action('save_post_' . ColumnistManager::POST_TYPE_NAME, array($this, 'saveColumnistProfileInfoMeta'), 10, 3);
        add_action('save_post_' . ColumnistManager::POST_TYPE_NAME, array($this, 'setDefaultColumnistCategory'), 10, 3);
    }

    public function enqueueScripts($hook)
    {
        if ('post.php' == $hook || 'post-new.php' == $hook) {
            wp_enqueue_script('ud-columnist', UD_COLUMNIST_MANAGER_URL . 'assets/js/admin.js', array('jquery'), UD_COLUMNIST_MANAGER_VERSION);
        }
    }

    public function addMetaboxes()
    {

        // edit post
        add_meta_box(
            'ud-columnist-metabox',
            __('Columnist', 'ud_columnist'),
            array($this, 'renderColumnistMetaboxes'),
            'post',
            'side',
            'default'
        );

        // edit columnist profile
        add_meta_box(
            'ud-columnist-profile-metabox',
            __('Columnist Info', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            array($this, 'renderColumnistProfileMetaboxes'),
            ColumnistManager::POST_TYPE_NAME,
            'normal',
            'high'
        );

    }

    public function renderColumnistMetaboxes($post)
    {

        // Add nonce for security and authentication.
        wp_nonce_field('ud_columnist_nonce_action', 'ud_columnist_nonce');

        // Retrieve an existing value from the database.
        $terms = wp_get_post_terms($post->ID, ColumnistManager::TAXONOMY_NAME);

        // Set default values.
        $ud_columnist_name = '';
        $ud_columnist_id = '';
        if (! empty($terms) && ! empty ($terms[0]->name)) {
            $ud_columnist_name = $terms[0]->name;
            $ud_columnist_id = $terms[0]->term_id;
        }

        ?>

        <div class="ud_cm_div">
            <div class="ud-cm-select-columnist">
                <p class="hide-if-js">
                    <input id="ud-columnist-id" name="ud_columnist[id]" type="text" class="real-input" value="<?php echo esc_attr($ud_columnist_id) ?>">
                </p>
                <p class="hide-if-no-js">
                    <input class="hide-if-no-js fake_input" placeholder="Search Columnist Here!!" type="text">
                </p>
                <div class="chosen_item"><span><?php echo esc_html($ud_columnist_name) ?></span></div>
                <p class="howto" id="ud-columnist-desc">Choose a columnist</p>
                <p class="hide-if-no-js">
                    <a class="ud_columnist_del" href="#" tabindex="0">Delete columnist</a>
                </p>
            </div>
        </div>
        <?php

    }

    public function renderColumnistProfileMetaboxes($post)
    {

        // Add nonce for security and authentication.
        wp_nonce_field('ud_columnist_nonce_action', 'ud_columnist_nonce');

        $ud_columnist_profile_meta = get_post_meta($post->ID, ColumnistManager::PROFILE_INFO_META_KEY, true);

        $info_url = '';
        if (! empty($ud_columnist_profile_meta['info_url'])) {
            $info_url = $ud_columnist_profile_meta['info_url'];
        }


        ?>
        <div class="ud-columnist-profile-div">
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label for="info_url">URL of Columnist:</label></th>
                    <td>
                        <input class="regular-text" id="info_url"
                               name="<?php echo ColumnistManager::PROFILE_INFO_META_KEY ?>[info_url]"
                               type="text" value="<?php echo esc_attr($info_url) ?>">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>


        <?php

    }

    // After post save, making relationship with columnist tag
    public function savePost($post_id, $post, $update)
    {
        if ('post' !== get_post_type($post_id)) {
            return $post_id;
        }

        // Add nonce for security and authentication.
        $nonce_name = isset($_POST['ud_columnist_nonce']) ? $_POST['ud_columnist_nonce'] : '';
        $nonce_action = 'ud_columnist_nonce_action';

        // Check if a nonce is set.
        if (! isset($nonce_name)) {
            return $post_id;
        }

        // Check if a nonce is valid.
        if (! wp_verify_nonce($nonce_name, $nonce_action)) {
            return $post_id;
        }

        // Check if the user has permissions to save data.
        if (! current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // Check if it's not an autosave.
        if (wp_is_post_autosave($post_id)) {
            return $post_id;
        }


        // Check if it's not a revision.
        if (wp_is_post_revision($post_id)) {
            return $post_id;
        }


        $ud_columnist_id = isset($_POST['ud_columnist']['id']) ? sanitize_text_field($_POST['ud_columnist']['id']) : '';

        $existing_terms = wp_get_object_terms($post_id, ColumnistManager::TAXONOMY_NAME);

        if (! empty($ud_columnist_id)) {
            $term = get_term($ud_columnist_id, ColumnistManager::TAXONOMY_NAME);
            // check if term already exist?
            if (empty ($term)) {
                return $post_id;
            }

            // bind term
            $result = wp_set_object_terms($post_id, $term->term_id, ColumnistManager::TAXONOMY_NAME);
            if (is_wp_error($result)) {
                error_log($result->get_error_message());

                return $post_id;
            }


            $this->updateColumnistProfileMeta($post_id, $term);

        } elseif (! empty($existing_terms[0])) {
            wp_remove_object_terms($post_id, $existing_terms[0]->term_id, ColumnistManager::TAXONOMY_NAME);
            $this->updateColumnistProfileMeta($post_id, $existing_terms[0]);
        }

        return $post_id;
    }

    public function saveColumnistProfileInfoMeta($post_id, $post, $update)
    {
        if (ColumnistManager::POST_TYPE_NAME !== get_post_type($post_id)) {
            return $post_id;
        }

        // Add nonce for security and authentication.
        $nonce_name = isset($_POST['ud_columnist_nonce']) ? $_POST['ud_columnist_nonce'] : '';
        $nonce_action = 'ud_columnist_nonce_action';

        // Check if a nonce is set.
        if (! isset($nonce_name)) {
            return $post_id;
        }

        // Check if a nonce is valid.
        if (! wp_verify_nonce($nonce_name, $nonce_action)) {
            return $post_id;
        }

        // Check if the user has permissions to save data.
        if (! current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // Check if it's not an autosave.
        if (wp_is_post_autosave($post_id)) {
            return $post_id;
        }


        // Check if it's not a revision.
        if (wp_is_post_revision($post_id)) {
            return $post_id;
        }

        $option_id = ColumnistManager::PROFILE_INFO_META_KEY;

        $_POST[$option_id]['info_url'] = isset($_POST[$option_id]['info_url']) ? sanitize_text_field($_POST[$option_id]['info_url']) : '';

        update_post_meta($post_id, $option_id, $_POST[$option_id]);

    }


    //    do auto generate term when create columnist profile
    public function syncColumnistTermData($post_id, $post, $update)
    {

        if (ColumnistManager::POST_TYPE_NAME !== get_post_type($post_id)) {
            return $post_id;
        }

        // Check if it's not an autosave.
        if (wp_is_post_autosave($post_id)) {
            return $post_id;
        }

        // Check if it's not a revision.
        if (wp_is_post_revision($post_id)) {
            return $post_id;
        }


        // get existing binding term of this columnist profile
        $existing_terms = wp_get_object_terms($post_id, ColumnistManager::TAXONOMY_NAME);
        if (! empty($existing_terms[0])) {
            $existing_term = $existing_terms[0];
        }

        // delete columnist
        if ('trash' === $post->post_status) {
            if (! empty($existing_terms)) {
                foreach ($existing_terms as $term) {
                    wp_delete_term($term->term_id, ColumnistManager::TAXONOMY_NAME);
                }
            }

            return $post_id;
        }

        // check if "add new" post
        if (! empty($post->post_name)) {
            if (empty ($existing_term)) {
                $result = wp_set_object_terms($post_id, urldecode($post->post_name), ColumnistManager::TAXONOMY_NAME);
                if (is_wp_error($result) || empty($result[0])) {
                    error_log("Can't not set term " . $post->post_name . " ID: " . $post_id);

                    return $post_id;
                }


                $existing_term = get_term($result[0], ColumnistManager::TAXONOMY_NAME);
            }

            wp_update_term($existing_term->term_id, ColumnistManager::TAXONOMY_NAME, array(
                'name'        => $post->post_title,
                'slug'        => urldecode($post->post_name),
                'description' => $post->post_excerpt,
            ));

            update_term_meta($existing_term->term_id, "ud_columnist_profile_id", $post_id);
        }


        return $post_id;

    }

    public function setDefaultColumnistCategory($post_id, $post, $update)
    {
        if (ColumnistManager::POST_TYPE_NAME !== get_post_type($post_id)) {
            return $post_id;
        }

        $terms = get_the_terms($post_id, ColumnistManager::COLUMNIST_CAT_TAXONOMY_NAME);
        if (! empty($terms)) {
            return $post_id;
        }

        wp_set_object_terms($post_id, 'uncategorized', ColumnistManager::COLUMNIST_CAT_TAXONOMY_NAME);

        return $post_id;
    }


    private function updateColumnistProfileMeta($post_id, $term)
    {
        // get columnist_profiles to update some meta
        $columnist_profiles = get_posts(array(
            'name'           => $term->slug,
            'post_type'      => 'ud_columnist_profile',
            'post_status'    => 'publish',
            'posts_per_page' => 1
        ));

        if (empty($columnist_profiles[0])) {
            error_log("missing columnist_profile " . $term->name);

            return $post_id;
        }

        // get modified latest_post of this columnist
        $lastest_post = get_posts(array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'orderby'        => 'date',
            'tax_query'      => array(
                array(
                    'taxonomy' => ColumnistManager::TAXONOMY_NAME,
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
        ));

        //collect post time of this columnist's latest post for future sorting
        if (! empty($lastest_post[0])) {
            $post_timestamp = get_post_time('U', true, $lastest_post[0]->ID);
            if (! add_post_meta($columnist_profiles[0]->ID, ColumnistManager::LATEST_POST_PUBLISH_TIME_META_KEY, $post_timestamp, true)) {
                update_post_meta($columnist_profiles[0]->ID, ColumnistManager::LATEST_POST_PUBLISH_TIME_META_KEY, $post_timestamp);
            }
        } else {
            //no post associate with this columnnist, unset metadata
            delete_post_meta($columnist_profiles[0]->ID, ColumnistManager::LATEST_POST_PUBLISH_TIME_META_KEY);
        }

        //collect post count for future sorting
        $columnist_post_count = $term->count;
        if (! add_post_meta($columnist_profiles[0]->ID, ColumnistManager::POST_COUNT_META_KEY, $columnist_post_count, true)) {
            update_post_meta($columnist_profiles[0]->ID, ColumnistManager::POST_COUNT_META_KEY, $columnist_post_count);
        }

        return $post_id;
    }

    public function searchColumnist()
    {

        //        if ( ! isset( $_GET['tax'] ) ) {
        //            wp_die( 0 );
        //        }
        //
        if (! current_user_can('edit_posts')) {
            wp_die();
        }

        $taxonomy = ColumnistManager::TAXONOMY_NAME;


        $s = wp_unslash($_GET['search_string']);
        $s = trim($s);
        //
        //        /**
        //         * Filter the minimum number of characters required to fire a tag search via AJAX.
        //         *
        //         * @since 4.0.0
        //         *
        //         * @param int    $characters The minimum number of characters required. Default 2.
        //         * @param object $tax        The taxonomy object.
        //         * @param string $s          The search term.
        //         */
        $term_search_min_chars = (int)apply_filters('term_search_min_chars', 2, $taxonomy, $s);
        //
        //        /*
        //         * Require $term_search_min_chars chars for matching (default: 2)
        //         * ensure it's a non-negative, non-zero integer.
        //         */
        if (($term_search_min_chars == 0) || (strlen($s) < $term_search_min_chars)) {
            wp_die();
        }

        $results = get_terms($taxonomy, array('name__like' => $s, 'fields' => 'id=>name', 'hide_empty' => false));

        echo json_encode($results);
        wp_die();
    }


}
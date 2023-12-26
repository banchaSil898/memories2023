<?php

namespace UDFixAndOptimize\Core;

use UDFixAndOptimize\UDOptionFramework\OptionFramework;
use UDFixAndOptimize\UDFixAndOptimize;

if (!defined('ABSPATH')) {
    exit;
}

class WPCore
{
    public function __construct()
    {
        if (true === OptionFramework::getOptionValue('wpcore_optimize_postmeta_form_keys_enable', UDFixAndOptimize::OPTION_KEY)) {
            add_filter('postmeta_form_keys', array($this, 'optimizePostmetaFormKeys'), 10, 2);
        }

        if (true === OptionFramework::getOptionValue('wpcore_optimize_search_title_only_enable', UDFixAndOptimize::OPTION_KEY)) {
            add_filter('posts_search', array($this, 'searchTitleOnly'), 10, 2);
        }

        if ('none' !== OptionFramework::getOptionValue('wpcore_optimize_wp_enqueue_media_audio_mode', UDFixAndOptimize::OPTION_KEY)) {
            add_filter('media_library_show_audio_playlist', array($this, 'mediaLibraryShowAudioPlaylist'));
        }

        if ('none' !== OptionFramework::getOptionValue('wpcore_optimize_wp_enqueue_media_video_mode', UDFixAndOptimize::OPTION_KEY)) {
            add_filter('media_library_show_video_playlist', array($this, 'mediaLibraryShowVideoPlaylist'));
        }

        add_filter('post_date_column_time', array($this, 'renderColumnTime'), 10, 4);

        if (true === OptionFramework::getOptionValue('wpcore_enable_thumbnail_in_feed', UDFixAndOptimize::OPTION_KEY)) {
            add_action('rss2_item', [$this, 'renderPostThumbnailTagInRSS2'], 10, 1);
            add_action('rss2_ns', [$this, 'insertMRSSNamespace'], 10, 1);
        }

        if (is_admin()) {
            add_action('admin_head', array($this, 'renderSettings'));

            if (true === OptionFramework::getOptionValue('wpcore_maintain_hierachical_term', UDFixAndOptimize::OPTION_KEY)) {
                add_filter('wp_terms_checklist_args', [$this, 'changeTaxonomyCheckboxlistOrder'], 10, 2);
            }
        }
    }


    public function optimizePostmetaFormKeys($keys, $post)
    {
        global $wpdb;

        $limit = apply_filters('postmeta_form_limit', 30);
        $sql = "SELECT DISTINCT meta_key
			FROM $wpdb->postmeta
			WHERE post_id = %d
			AND meta_key NOT BETWEEN '_' AND '_z'
			HAVING meta_key NOT LIKE %s
			ORDER BY meta_key
			LIMIT %d";
        $tmp_keys = $wpdb->get_col($wpdb->prepare($sql, $post->ID, $wpdb->esc_like('_') . '%', $limit));

        if (null === $keys) {
            $keys = array();
        }

        if (!empty($tmp_keys)) {
            $keys = array_merge($keys, $tmp_keys);
            $keys = array_unique($keys);
        }

        return $keys;
    }

    //Unixdev MOD: force to search only title
    public function searchTitleOnly($search, $wp_query)
    {
        global $wpdb;
        if (empty($search)) {
            return $search;
        } // skip processing - no search term in query
        $q = $wp_query->query_vars;
        $n = !empty($q['exact']) ? '' : '%';
        $search =
            $searchand = '';
        foreach ((array) $q['search_terms'] as $term) {
            $term = esc_sql($wpdb->esc_like($term));
            $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $searchand = ' AND ';
        }
        if (!empty($search)) {
            $search = " AND ({$search}) ";
            if (!is_user_logged_in()) {
                $search .= " AND ($wpdb->posts.post_password = '') ";
            }
        }

        return $search;
    }

    public function mediaLibraryShowAudioPlaylist()
    {
        $mode = OptionFramework::getOptionValue('wpcore_optimize_wp_enqueue_media_audio_mode', UDFixAndOptimize::OPTION_KEY);

        if ('force_true' === $mode) {
            return true;
        }

        return false;
    }

    public function mediaLibraryShowVideoPlaylist()
    {
        $mode = OptionFramework::getOptionValue('wpcore_optimize_wp_enqueue_media_video_mode', UDFixAndOptimize::OPTION_KEY);
        if ('force_true' === $mode) {
            return true;
        }

        return false;
    }

    public function renderColumnTime($t_h_time, $post, $column_name, $mode)
    {
        if ('date' !== $column_name) {
            return $t_h_time;
        }

        if ('0000-00-00 00:00:00' === $post->post_date) {
            return $t_h_time;
        }

        $time_ago_format_limit = OptionFramework::getOptionValue('wpcore_time_ago_after_publish_period', UDFixAndOptimize::OPTION_KEY);

        $enable_time_on_date_column = OptionFramework::getOptionValue('wpcore_enable_time_on_date_column', UDFixAndOptimize::OPTION_KEY);

        $m_time = $post->post_date;
        $time = get_post_time('G', true, $post, false);
        $time_diff = time() - $time;
        if ($time_diff > 0 && $time_diff < $time_ago_format_limit) {
            $t_h_time = sprintf(__('%s ago'), human_time_diff($time));
        } else {
            if (true === $enable_time_on_date_column) {
                $t_h_time = mysql2date(__('Y/m/d G:i:s'), $m_time);
            } else {
                $t_h_time = mysql2date(__('Y/m/d'), $m_time);
            }
        }

        return $t_h_time;
    }

    public function renderPostThumbnailTagInRSS2()
    {
        global $post;

        $images = [];
        if (has_post_thumbnail($post->ID)) {
            $images[] = get_post(get_post_thumbnail_id($post->ID));
        }

        if (empty($images)) {
            return;
        }

        foreach ($images as $image) {
            if (!$image instanceof \WP_Post) {
                continue;
            }

            $img_attr = wp_get_attachment_image_src($image->ID, 'full');

            $temp = explode('/', $img_attr[0]);
            $temp[count($temp) - 1] = rawurlencode($temp[count($temp) - 1]);

            $image_url = implode('/', $temp);
            $image_type = $image->post_mime_type;
            $image_width = $img_attr[1];
            $image_height = $img_attr[2];
            $image_title = $image->title;
            $image_description = $image->post_content

?>
            <media:content url="<?php echo esc_url($image_url); ?>" type="<?php echo esc_attr($image_type); ?>" medium="image" width="<?php echo absint($image_width); ?>" height="<?php echo absint($image_height); ?>">
                <media:title type="plain">
                    <![CDATA[<?php echo sanitize_text_field($image_title); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                ?>]]>
                </media:title>
                <media:thumbnail url="<?php echo esc_url($image_url); ?>" width="<?php echo absint($image_width); ?>" height="<?php echo absint($image_height); ?>" />
                <media:description type="plain">
                    <![CDATA[<?php echo wp_kses_post($image_description); ?>]]>
                </media:description>
            </media:content>
<?php
        }
    }

    function insertMRSSNamespace()
    {
        echo 'xmlns:media="http://search.yahoo.com/mrss/"' . "\n";
    }

    public function renderSettings()
    {
        $settings = array(
            'wpcore_maintain_hierachical_term' => OptionFramework::getOptionValue('wpcore_maintain_hierachical_term', UDFixAndOptimize::OPTION_KEY)
        );

        echo '<script>var UDFAOSettings = ' . wp_json_encode($settings) . '</script>';
    }


    public function changeTaxonomyCheckboxlistOrder($args, $post_id)
    {
        if (isset($args['taxonomy'])) {
            $args['checked_ontop'] = false;
        }

        return $args;
    }
}

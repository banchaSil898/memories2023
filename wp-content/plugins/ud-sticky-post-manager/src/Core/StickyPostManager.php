<?php

namespace UDStickyPostManager\Core;

if (! defined('ABSPATH')) {
    exit;
}


/**
 * Class SticyPostManager
 */
class StickyPostManager
{
    private static $data_loaded = false;
    private static $sticky_post_data = array();


    public function __construct()
    {
        add_action('pre_get_posts', array($this, 'preGetPostsHook'), 9999);
        add_action('posts_results', array($this, 'postsResultHook'), 9999, 2);
    }

    /**
     * @param \WP_Query $wp_query
     */
    public function preGetPostsHook($wp_query)
    {
        if ($wp_query->get('suppress_filters', false)) {
            return;
        }

        $udspm = $wp_query->get('udspm');
        if (! is_array($udspm) || empty($udspm['loop_ids'])) {
            return;
        }

        $loop_ids = $udspm['loop_ids'];
        if (empty($loop_ids) || ! is_array($loop_ids)) {
            return;
        }

        $sticky_post_infos = $this->getAllStickyPostInfos($loop_ids);
        if (empty($sticky_post_infos)) {
            return;
        }

        $post__not_in = $wp_query->get('post__not_in');
        if (empty($post__not_in)) {
            $post__not_in = array();
        }

        $posts_per_page = intval($wp_query->get('posts_per_page'));

        $paged = absint($wp_query->get('paged'));
        if (! $paged) {
            $paged = 1;
        }

        $offset = $wp_query->get('offset');
        if (! is_numeric($offset)) {
            $offset = ($paged - 1) * $posts_per_page;
        } else {
            $offset = absint($offset);
        }

        $lowerbound = $offset;
        $prev_paged_post_count = 0;
        $exclude_post_ids = array();

        foreach ($sticky_post_infos as $position => $post) {
            if ($position <= $lowerbound) {
                $prev_paged_post_count++;
            }

            if (! in_array($post->ID, $exclude_post_ids)) {
                array_push($exclude_post_ids, $post->ID);
            }
        }

        $post__not_in = array_merge($post__not_in, $exclude_post_ids);
        $offset -= $prev_paged_post_count;
        $udspm['prev_paged_post_count'] = $prev_paged_post_count;

        // use offset instead using paged. Setting the offset parameter overrides/ignores the paged parameter
        $wp_query->set('offset', $offset);
        $wp_query->set('post__not_in', $post__not_in);
        $wp_query->set('udspm', $udspm);
    }

    /**
     * @param \WP_Post[] $posts
     * @param \WP_Query  $wp_query
     * @return \WP_Post[]
     */
    public function postsResultHook($posts, $wp_query)
    {
        if ($wp_query->get('suppress_filters', false)) {
            return $posts;
        }

        $udspm = $wp_query->get('udspm');
        if (! is_array($udspm) || empty($udspm['loop_ids']) || ! is_array($udspm['loop_ids'])) {
            return $posts;
        }

        $loop_ids = $udspm['loop_ids'];

        $sticky_post_infos = $this->getAllStickyPostInfos($loop_ids);
        if (empty($sticky_post_infos)) {
            return $posts;
        }

        $offset = $wp_query->get('offset') + $udspm['prev_paged_post_count']; //original offset
        $posts_per_page = $wp_query->get('posts_per_page');
        $lowerbound = $offset;
        $current_sticky_posts = array();
        $next_page_sticky_posts = array();
        $prev_paged_post_count = 0;

        foreach ($sticky_post_infos as $position => $sticky_post) {
            if ($position <= $lowerbound) {
                $prev_paged_post_count++;
            } elseif ($posts_per_page <= 0 || $position <= $lowerbound + $posts_per_page) {
                $current_sticky_posts[$position - $offset] = $sticky_post;
            } else {
                $next_page_sticky_posts[$position - $offset] = $sticky_post;
            }
        }

        if ($posts_per_page > 0) {
            $splice_offset = $posts_per_page - count($current_sticky_posts);
            array_splice($posts, ($splice_offset < 0) ? 0 : $splice_offset);
        }

        foreach ($current_sticky_posts as $position => $sticky_post) {
            $inserting_index = $position - 1;
            array_splice($posts, $inserting_index, 0, array($sticky_post));
        }

        if ($posts_per_page > 0 && count($posts) < $posts_per_page) {
            foreach ($next_page_sticky_posts as $position => $sticky_post) {
                array_push($posts, $sticky_post);
            }
        }


        $no_found_rows = $wp_query->get('no_found_rows', false);
        $wp_query->post_count += count($current_sticky_posts);

        if ($no_found_rows) {
            return $posts;
        }

        $wp_query->found_posts += count($sticky_post_infos);
        $wp_query->max_num_pages = ceil($wp_query->found_posts / $posts_per_page);

        return $posts;
    }

    private function getAllStickyPostInfos($loop_ids)
    {
        if (false === self::$data_loaded) {
            $args = array(
                'post_type'      => PostType::POST_TYPE_NAME,
                'post_status'    => 'publish',
                'posts_per_page' => -1,
            );

            $sticky_post_schedules = get_posts($args);

            foreach ($sticky_post_schedules as $stick_post_schedule) {
                $meta = get_post_meta($stick_post_schedule->ID, PostType::META_KEY, true);
                if (empty($meta) or empty($meta['position_number']) or empty($meta['loop_ids']) or ! is_array($meta['loop_ids']) or empty($meta['post_id'])) {
                    continue;
                }

                $sticky_post = get_post($meta['post_id']);
                if (! $sticky_post) {
                    continue;
                }

                $schedule_loop_ids = $meta['loop_ids'];
                foreach ($schedule_loop_ids as $schedule_loop_id) {
                    self::$sticky_post_data[$schedule_loop_id][strval($meta['position_number'])] = $sticky_post;
                }
            }

            foreach (self::$sticky_post_data as $key => $category_sticky_posts) {
                ksort(self::$sticky_post_data[$key], SORT_NUMERIC);
            }

            self::$data_loaded = true;
        }

        $result = array();
        foreach ($loop_ids as $loop_id) {
            if (isset(self::$sticky_post_data[$loop_id])) {
                $result = self::$sticky_post_data[$loop_id] + $result;
            }
        }

        return $result;
    }
}

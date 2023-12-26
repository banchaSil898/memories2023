<?php
/**
 * Created by PhpStorm.
 * User: Pai
 * Date: 6/28/2018
 * Time: 5:22 PM
 */

namespace UDFixAndOptimize\CLI;

use UDFixAndOptimize\Core\PageSpeed;
use UDFixAndOptimize\Util\ImageEditor;

if (! class_exists('\WP_CLI')) {
    return;
}

/**
 * Revision Cleanup Command
 *
 * @package wp-cli
 */
class RevisionCommand extends \WP_CLI_Command
{

    const POST__IN_LENGTH = 100;

    /**
     * Clean revision
     *
     *
     * ## OPTIONS
     *
     * [--post_type=<post-type>]
     * : Clean revisions for given post type(s). Default: any*
     *
     * [--after_date=<strtotime-compatible-string>]
     * : Clean revisions published on or after this date. Accepts strtotime()-compatible string. Default: none.
     *
     * [--before_date=<strtotime-compatible-string>]
     * : Clean revisions published on or before this date. Accepts strtotime()-compatible string. Default: none.
     *
     * [--post_ids=<post-ids>]
     * : Clean revisions for given post.
     *
     * [--dry_run]
     * : Dry run, just a test, no actual cleaning done.
     *
     * ## EXAMPLES
     *
     *     wp udfao revision clean
     *     wp udfao revision clean --post_ids=2
     *     wp udfao revision clean --post_type=post,page
     *     wp udfao revision clean --after_date=2015-11-01 --before_date=2015-12-30
     *     wp udfao revision clean --after_date=2015-11-01 --before_date=2015-12-30 --dry_run
     *
     * @subcommand clean
     */
    public function clean($args, $assoc_args = array())
    {
        $revision_query_args = array(
            'post_status'            => 'any',
            'post_type'              => 'revision',
            'posts_per_page'         => -1,
            'fields'                 => 'id=>parent',
            'date_query'             => array(),
            'cache_results'          => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );

        // get all revisions that match parameter
        if (empty($assoc_args['post_ids'])) {
            if (! empty($assoc_args['after_date'])) {
                $after_date = $assoc_args['after_date'];
                $revision_query_args['date_query']['after'] = $after_date;
            }

            if (! empty($assoc_args['before_date'])) {
                $before_date = $assoc_args['before_date'];
                $revision_query_args['date_query']['before'] = $before_date;
            }
        } else {
            $post_parent__in = array_map('absint', explode(',', $assoc_args['post_ids']));
            $post_parent_ids = implode(',', $post_parent__in);
            if ($post_parent_ids !== $assoc_args['post_ids']) {
                \WP_CLI::error('Wrong format in --post_ids');
            }

            $revision_query_args['post_parent__in'] = $post_parent__in;
        }

        $wp_query = new \WP_Query($revision_query_args);

        $count = $wp_query->post_count;
        if (! $count) {
            \WP_CLI::warning('No revisions to cleanup.');

            return;
        }

        $post_revisions_array = array();
        foreach ($wp_query->posts as $obj) {
            if (! isset($post_revisions_array[$obj->post_parent])) {
                $post_revisions_array[$obj->post_parent] = array();
            }
            array_push($post_revisions_array[$obj->post_parent], $obj->ID);
        }

        $post_query_args = array(
            'post_status'            => 'any',
            'posts_per_page'         => -1,
            'fields'                 => 'ids',
            'cache_results'          => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );

        $wp_post_ids = array_keys($post_revisions_array);
        if (empty($assoc_args['post_ids'])) {
            //filter post type
            $post_types = $this->getPostTypeSupportedRevisions();

            if (! empty($assoc_args['post_type'])) {
                $post_types = array_intersect($post_types, explode(',', $assoc_args['post_type']));
            }

            if (! empty($post_types)) {
                $result_post_ids = array();
                $post_query_args['post_type'] = $post_types;

                for ($i = 0; $i < count($wp_post_ids); $i += self::POST__IN_LENGTH) {
                    $post_query_args['post__in'] = array_slice($wp_post_ids, $i, self::POST__IN_LENGTH);

                    $wp_query = new \WP_Query($post_query_args);

                    $result_post_ids = array_merge($result_post_ids, $wp_query->posts);
                }

                $result_post_revisions_array = array();
                foreach ($result_post_ids as $id) {
                    $result_post_revisions_array[$id] = $post_revisions_array[$id];
                }

                $post_revisions_array = &$result_post_revisions_array;
            }
        }

        //count post and revision to delete
        $post_count = 0;
        $revision_count = 0;

        if (! empty($post_revisions_array)) {
            $post_count = count($post_revisions_array);

            foreach ($post_revisions_array as $id => $revision_ids) {
                $revision_count += count($revision_ids);
            }
        }

        if (! $post_count) {
            \WP_CLI::warning('No revision to delete.');

            return;
        }

        \WP_CLI::log(sprintf('Found %1$d %2$s of %3$d %4$s to cleanup.', $revision_count, _n('revision', 'revisions', $revision_count), $post_count, _n('post object', 'post objects', $post_count)));

        // process cleanup revision
        $successes = $errors = $skips = 0;
        $number = 0;
        krsort($post_revisions_array);

        foreach ($post_revisions_array as $wp_post_id => $revision_ids) {
            $wp_post_title = get_the_title($wp_post_id);

            rsort($revision_ids);

            foreach ($revision_ids as $id) {
                $progress = ($number + 1) . '/' . $revision_count;
                if ('' === $wp_post_title) {
                    $att_desc = sprintf('"(no title)" (ID %1$d) (Revision ID %2$d)', $wp_post_id, $id);
                } else {
                    $att_desc = sprintf('"%1$s" (ID %2$d) (Revision ID %3$d)', $wp_post_title, $wp_post_id, $id);
                }

                if (\WP_CLI\Utils\get_flag_value($assoc_args, 'dry_run', false)) {
                    $result = true;
                    $att_desc .= ' (dry_run)';
                } else {
                    $result = wp_delete_post_revision($id);
                }

                if (empty($result) || is_wp_error($result)) {
                    \WP_CLI::log("$progress Couldn't delete revision of $att_desc.");
                    $errors++;
                } else {
                    \WP_CLI::log("$progress Delete revision of $att_desc.");
                    $successes++;
                }

                $number++;
            }
        }

        \WP_CLI\Utils\report_batch_operation_results('Revision', 'delete', $revision_count, $successes, $errors, $skips);
    }

    private function getPostTypeSupportedRevisions()
    {
        $supports_revisions = array();
        foreach (get_post_types() as $post_type) {
            if (post_type_supports($post_type, 'revisions')) {
                $supports_revisions[] = $post_type;
            }
        }

        return $supports_revisions;
    }

}

<?php

namespace UDFixAndOptimize\CLI;

use UDFixAndOptimize\Core\PageSpeed;
use UDFixAndOptimize\Util\ImageEditor;

if (! class_exists('\WP_CLI')) {
    return;
}

/**
 * PageSpeed Optimization
 *
 * @package wp-cli
 */
class PageSpeedCommand extends \WP_CLI_Command
{

    /**
     * @var PageSpeed $pagespeed
     */
    private $pagespeed;

    public function __construct($pagespeed)
    {
        $this->pagespeed = $pagespeed;
    }

    /**
     * optimize one or more original image.
     *
     *
     * ## OPTIONS
     *
     * [<attachment_id>...]
     * : One or more IDs of the attachments to optimize
     *
     * ## EXAMPLES
     *
     *     # optimize original image
     *     $ wp udfao pagespeed optimize-original-image
     *     Found 3 images to optimize.
     *     1/3 Optimized "Vertical Image" (ID 123).
     *     2/3 Optimized "Horizontal Image" (ID 124).
     *     3/3 Optimized "Beautiful Picture" (ID 125).
     *     Success: Optimized stat 3 of 3 images.
     *
     *     $ wp udfao pagespeed optimize-original-image 123 124 125
     *     Found 3 images to optimize.
     *     1/3 Optimized "Vertical Image" (ID 123).
     *     2/3 Optimized "Horizontal Image" (ID 124).
     *     3/3 Optimized "Beautiful Picture" (ID 125).
     *     Success: Optimized stat 3 of 3 images.
     *
     *
     * @subcommand optimize-original-image
     */

    public function optimizeOriginalImage($args, $assoc_args = array())
    {
        //force using our image editor
        add_filter('wp_image_editors', array($this->pagespeed, 'addImageEditorHook'), 9999, 1);

        if (empty($args)) {
            \WP_CLI::confirm('Do you really want to regenerate all images?');
        }


        $mime_types = array('image');

        $query_args = array(
            'post_type'              => 'attachment',
            'post__in'               => $args,
            'post_mime_type'         => $mime_types,
            'post_status'            => 'any',
            'posts_per_page'         => -1,
            'fields'                 => 'ids',
            "cache_results"          => false,
            "update_post_meta_cache" => false,
            "update_post_term_cache" => false,
        );

        $wp_query = new \WP_Query($query_args);

        $count = $wp_query->post_count;
        if (! $count) {
            \WP_CLI::warning('No images found.');

            return;
        }

        \WP_CLI::log(sprintf('Found %1$d %2$s to optimize.', $count, _n('image', 'images', $count)));

        $number = $successes = $errors = $skips = 0;
        foreach ($wp_query->posts as $id) {
            $number++;
            //            if (0 === $number % self::CLEAR_OBJECT_CACHE_OPTIMIZE_IMAGE_INTERVAL) {
            //                \WP_CLI\Utils\wp_clear_object_cache();
            //            }
            $this->processImageOptimization($id, $number . '/' . $count, $successes, $errors, $skips);
        }

        \WP_CLI\Utils\report_batch_operation_results('Image', 'optimize', $count, $successes, $errors, $skips);
    }

    private function processImageOptimization($id, $progress, &$successes, &$errors, &$skips)
    {
        $title = get_the_title($id);
        if ('' === $title) {
            // If audio or video cover art then the id is the sub attachment id, which has no title.
            if (metadata_exists('post', $id, '_cover_hash')) {
                // Unfortunately the only way to get the attachment title would be to do a non-indexed query against the meta value of `_thumbnail_id`. So don't.
                $att_desc = sprintf('cover attachment (ID %d)', $id);
            } else {
                $att_desc = sprintf('"(no title)" (ID %d)', $id);
            }
        } else {
            $att_desc = sprintf('"%1$s" (ID %2$d)', $title, $id);
        }

        $needs_optimization = 'image/jpeg' === get_post_mime_type($id) || 'image/png' === get_post_mime_type($id);
        if (! $needs_optimization) {
            \WP_CLI::log("$progress Skipped optimization for $att_desc.");
            $skips++;

            return;
        }

        $result = $this->pagespeed->optimizeOriginalImage($id);
        if (is_wp_error($result)) {
            \WP_CLI::log("$progress Couldn't optimized $att_desc. - " . $result->get_error_message());
            $errors++;
        } else {
            \WP_CLI::log("$progress Optimized $att_desc.");
            $successes++;
        }
    }
}

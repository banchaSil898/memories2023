<?php

namespace UDFixAndOptimize\Core;

use UDFixAndOptimize\UDOptionFramework\OptionFramework;
use UDFixAndOptimize\UDFixAndOptimize;

if (! defined('ABSPATH')) {
    exit;
}

class WPRSSAggregator
{
    public function __construct()
    {
        if (true === OptionFramework::getOptionValue('wprss_aggregator_utf8_encode_fix_enable', UDFixAndOptimize::OPTION_KEY)) {
            add_action('wprss_ftp_converter_post_title', array($this, 'decodeTitle'), 10, 2);
            add_action('wprss_ftp_converter_post_content', array($this, 'decodeContent'), 10, 2);
            add_action('wprss_ftp_converter_post_date_gmt', array($this, 'fixPostDateGMT'), 10, 2);
        }

        if (true === OptionFramework::getOptionValue('wprss_aggregator_show_source_url_enable', UDFixAndOptimize::OPTION_KEY)) {
            add_action('edit_form_after_title', array($this, 'renderSourceLinkBox'));
        }

        if (! empty(OptionFramework::getOptionValue('wprss_aggregator_cats_enable_ext_source_post_link', UDFixAndOptimize::OPTION_KEY))) {
            add_filter('post_link', array($this, 'linkPostToExternal'), 11, 3);
        }
    }

    public function decodeTitle($title, $source)
    {
        $converted_title = html_entity_decode(str_replace("&amp;", "&", $title), 0, 'UTF-8');

        return $converted_title;
    }

    public function decodeContent($content, $source)
    {
        $converted_content = html_entity_decode(str_replace("&amp;", "&", $content), 0, 'UTF-8');

        return $converted_content;
    }

    public function fixPostDateGMT($post_date_gmt, $source)
    {
        // now feed to draft can publish "immediately"
        return "0000-00-00 00:00:00";
    }

    public function renderSourceLinkBox($post)
    {
        $original_link = get_post_meta($post->ID, "wprss_item_permalink", true);
        if (! empty($original_link)) {
            ?>
            <div id="original_linkdiv" class="postbox">
                <h3 class="hndle">
                    <span>Original Link</span>
                </h3>
                <div class="inside">
                    <p><?php echo "<b>Source Link: </b>" . $original_link ?></p>
                    <a id=orig-preview target="wprss-preview-<?php echo $post->ID ?>" class="button"
                       href="<?php echo $original_link ?>">
                        Go to Source link
                    </a>
                    <a id=orig-preview target="wprss-download-<?php echo $post->ID ?>"
                       href="http://js.up2uhost.com/sc/index.php?url=<?php echo urlencode($original_link) ?>"
                       class="button">
                        ถ้ารูปมาไม่ครบ คลิ๊กที่นี่!
                    </a>
                </div>

            </div>
            <?php
        }
    }

    //Unixdev MOD -------------
    public function linkPostToExternal($url, $post, $leavename)
    {
        // If the id parameter was not passed, do nothing and return the title.
        if ($url === null || get_post() === null || ! class_exists('\WPRSS_FTP')) {
            return $url;
        }

        $cat_ids = OptionFramework::getOptionValue('wprss_aggregator_cats_enable_ext_source_post_link', UDFixAndOptimize::OPTION_KEY);

        if (! in_category($cat_ids, $post->ID)) {
            return $url;
        }

        // Get the feed source for the post
        $source = \WPRSS_FTP_Meta::get_instance()->get_meta($post->ID, 'feed_source');
        // IF AN IMPORTED POST
        if ($source !== '') {
            $permalink = get_post_meta($post->ID, 'wprss_item_permalink', true);

            // If the permalink is empty, return the regular WordPress post url
            if ($permalink === '') {
                return $url;
            }

            $link_external = true;

            // If link_external is TRUE, return the permalink of the original article.
            // Otherwise, return the regular WordPress post url
            return (($link_external === true) ? $permalink : $url);
        } else {
            return $url;
        }
    }
}

<?php

/*
Plugin Name: UD Generate Feed
Plugin URI: https://www.unixdev.co.th/
Description: Generate feed for MSN and Line
Author: Unixdev
Author URI: https://www.unixdev.co.th/
Version: 1.23.2
 */

/*
    2017-08-03 1.8  add config first
    2017-08-05 1.9  edit tag p for msn and add youtube video node
    2017-08-05 1.10 add feature image for msn
    2017-08-05 1.11 add gallery
    2017-09-24 1.12 xml namespace
    2017-09-27 1.13 edit date format MSN
    2018-01-03 1.14 add updateTimeUnix for Line Feed

    2018-02-10 1.15 add feed for sentangsedtee
    2018-03-25 1.16 change max line 20 to 100 and select with current date
    2018-04-21 1.17 change date column for line to post_modified
    2018-04-21 1.18 add excerpt to line feed
    2018-04-21 1.19 new format for MSN Feed (image , content, content:encode)
    2019-05-29 1.20 remove author for MSN
    2019-08-26 1.21 Change format for fixed error LINE
    2019-08-28 1.22 Replace none ascii e.g. 0x11 0x80
 */

define('UDGENFEED_VERSION', "1.23.2");

require_once('udgenfeedconfig.php');

function get_main_category($post_id)
{
    if (class_exists('WPSEO_Primary_Term')) {
        $primary_term_object = new WPSEO_Primary_Term('category', $post_id);
        $cat_id = $primary_term_object->get_primary_term();
        $cat = get_category($cat_id);

        if (! empty($cat) and ! is_wp_error($cat)) {
            return $cat;
        }
    }

    $categories = get_the_category($post_id);
    if (! empty($categories)) {
        return $categories[0];
    }

    return null;
}

function ud_feed_msn_init()
{
    register_taxonomy(
        'msn',
        'post',
        array(
            'hierarchical' => true,
            'labels'       => array(
                'name'              => _x('MSN', 'MSN general name'),
                'singular_name'     => _x('MSN', 'taxonomy singular name'),
                'search_items'      => __('Search MSN'),
                'all_items'         => __('All MSN'),
                'parent_item'       => __('Parent MSN'),
                'parent_item_colon' => __('Parent MSN:'),
                'edit_item'         => __('Edit MSN'),
                'update_item'       => __('Update MSN'),
                'add_new_item'      => __('Add New MSN'),
                'new_item_name'     => __('New MSN Name'),
                'menu_name'         => __('MSN')
            )
        )
    );
}

add_action('init', 'ud_feed_msn_init');

function ud_feed_line_init()
{
    register_taxonomy(
        'line',
        'post',
        array(
            'hierarchical' => true,
            'labels'       => array(
                'name'              => _x('Line', 'Line general name'),
                'singular_name'     => _x('Line', 'taxonomy singular name'),
                'search_items'      => __('Search Line'),
                'all_items'         => __('All Line'),
                'parent_item'       => __('Parent Line'),
                'parent_item_colon' => __('Parent Line:'),
                'edit_item'         => __('Edit Line'),
                'update_item'       => __('Update Line'),
                'add_new_item'      => __('Add New Line'),
                'new_item_name'     => __('New Line Name'),
                'menu_name'         => __('Line')
            )
        )
    );
}

add_action('init', 'ud_feed_line_init');

add_filter(
    'image_send_to_editor',
    function ($html, $id) {
        return str_ireplace(
            '<img ',
            sprintf('<img data-has-syndication-rights="%s" data-portal-copyright="%s" ', '1', UDGENFEED_COPYRIGHT),
            $html
        );
    },
    10,
    2
);

class SimpleXMLExtended extends SimpleXMLElement
{
    public function addCData($cdata_text)
    {
        $node = dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }
}

function udgenfeed_rss_init()
{
    //    global $wp_rewrite;
    //    $wp_rewrite->flush_rules( false );
    add_feed('udgenfeedmsn', 'udgenfeed_function');
    add_feed('udgenfeedline', 'udgenfeed_line_function');
    add_feed('udgenfeedmsngallery', 'udgenfeed_msn_gallery_function');
    add_feed('udgenfeedxml', 'udgenfeedxml_function');
}

add_action('init', 'udgenfeed_rss_init');

function udgenfeed_function()
{
    header('Content-type: text/xml');
    $slug = $_GET['slug'];
    if (empty($slug)) {
        return false;
    }
    $xml_root = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8" ?' . '><rss xmlns:atom="https://www.w3.org/2005/Atom" xmlns:dc="' . UDGENFEED_XMLNS_DC . '" xmlns:media="' . UDGENFEED_XMLNS_MEDIA . '" xmlns:mi="' . UDGENFEED_XMLNS_MI . '" xmlns:content="https://purl.org/rss/1.0/modules/content/" xmlns:dcterms="https://purl.org/dc/terms/" version="2.0"></rss>');

    $xml_data = $xml_root->addChild("channel");

    $news = new WP_query(
        array(
            'posts_per_page' => 100,
            'no_found_rows'  => true,
            "tax_query"      => array(
                array(
                    "taxonomy" => "msn",
                    "field"    => "slug",
                    "terms"    => $slug
                )
            )
        )
    );
    global $post;
    $xml_data->addChild("title", $slug);
    $xml_data->addChild("language", "th-TH");
    $xml_data->addChild("version", UDGENFEED_VERSION);
    $xml_data->addChild("link", get_bloginfo('url') . '?feed=udgenfeedmsn&amp;slug=' . $slug);

    while ($news->have_posts()) : $news->the_post();

    $xml_item = $xml_data->addChild("item");
    $xml_item->addChild("title")->addCData(get_the_title());
    $xml_item->addChild("link")->addCData(get_the_permalink());

    $post_date_time = strtotime($post->post_date);
    $post_modified_time = strtotime($post->post_modified_gmt);

    $xml_item->addChild("pubDate", date("Y-m-d", $post_date_time) . 'T' . date("H:i:s", $post_date_time) . '+0700');
    $temp1 = $xml_item->addChild("guid", $post->ID);
    $temp1->addAttribute('isPermaLink', 'false');

    $content = get_the_content();
    $content = strip_shortcodes($content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);

    //reject overly long 2 byte sequences, as well as characters above U+10000 and replace with ?
    $content = preg_replace(
        '/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]' .
            '|[\x00-\x7F][\x80-\xBF]+' .
            '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*' .
            '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})' .
            '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
        '',
        $content
    );

    //reject overly long 3 byte sequences and UTF-16 surrogates and replace with ?
    $content = preg_replace('/\xE0[\x80-\x9F][\x80-\xBF]' .
            '|\xED[\xA0-\xBF][\x80-\xBF]/S', '', $content);

    $excerpt_length = apply_filters('excerpt_length', 200);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
    $excerpt = wp_trim_words($content, $excerpt_length, $excerpt_more);

    $xml_item->addChild('description', htmlspecialchars($excerpt));
    //$xml_item->addChild("description", nl2br(htmlspecialchars($content)));

    $xml_item->addChild("content:encoded", null, "https://purl.org/rss/1.0/modules/content/")->addCData((htmlspecialchars($content)));

    $usera = get_userdata($post->post_author);
    $xml_item->addChild("dc:alternative", get_the_title(), UDGENFEED_XMLNS_DC);
    $xml_item->addChild("dc:abstract", get_the_title(), UDGENFEED_XMLNS_DC);
    $xml_item->addChild("dc:publisher", null, UDGENFEED_XMLNS_DC);
    $xml_item->addChild("dc:creator", null, UDGENFEED_XMLNS_DC);
    $xml_item->addChild("dc:modified", date("Y-m-d", $post_modified_time) . 'T' . date("H:i:s", $post_modified_time) . '+0700', UDGENFEED_XMLNS_DC);
    $xml_item->addChild("mi:dateTimeWritten", date("Y-m-d", $post_modified_time) . 'T' . date("H:i:s", $post_modified_time) . '+0700', UDGENFEED_XMLNS_MI);

    // feature image
    $feature_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
    $feature_image_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');

    if (($feature_image) && ($feature_image_thumbnail)) {
        $image_obj = get_post(get_post_thumbnail_id($post->ID));
        $post_mime_type = "image";
        $post_title = "";
        if ($image_obj) {
            $post_mime_type = $image_obj->post_mime_type;
            $post_title = $image_obj->post_title;
        }
        $xml_feature = $xml_item->addChild("media:content", null, UDGENFEED_XMLNS_MEDIA);
        $xml_feature->addAttribute('url', $feature_image[0]);
        $xml_feature->addAttribute('type', $post_mime_type);
        $xml_feature->addAttribute('medium', "image");
        $attr = $xml_feature->addChild("media:thumbnail");
        $attr->addAttribute('url', $feature_image_thumbnail[0]);
        $attr->addAttribute('type', $post_mime_type);

        $xml_feature->addChild("media:credit", UDGENFEED_WEBSITE);
        $xml_feature->addChild("media:copyright", UDGENFEED_WEBSITE);
        $xml_feature->addChild("media:title", 'ภาพประกอบข่าว');
        $xml_feature->addChild("media:text", 'ภาพประกอบข่าว');

        $xml_region = $xml_feature->addChild("mi:focalRegion", null, UDGENFEED_XMLNS_MI);
        $xml_region->addChild("mi:x1", (isset($feature_image[1]) ? $feature_image[1] : 0));
        $xml_region->addChild("mi:y1", (isset($feature_image[2]) ? $feature_image[2] : 0));
        $xml_region->addChild("mi:x2", (isset($feature_image[1]) ? $feature_image[1] : 0));
        $xml_region->addChild("mi:y2", (isset($feature_image[2]) ? $feature_image[2] : 0));
        $xml_feature->addChild("mi:hasSyndicationRights", 1, UDGENFEED_XMLNS_MI);
        $xml_feature->addChild("mi:licenseId", UDGENFEED_WEBSITE, UDGENFEED_XMLNS_MI);
        $xml_feature->addChild("mi:licensorName", UDGENFEED_WEBSITE, UDGENFEED_XMLNS_MI);
    }

    $medias = get_attached_media('image', $post->ID);
    if ($medias) {
        foreach ($medias as $attachment) {
            $temp = get_post_meta($attachment->ID, '_wp_attachment_metadata', true);
            $xml_enclousure = $xml_item->addChild("media:content", null, UDGENFEED_XMLNS_MEDIA);
            $xml_enclousure->addAttribute('url', $attachment->guid);
            $xml_enclousure->addAttribute('type', $attachment->post_mime_type);
            $xml_enclousure->addAttribute('medium', 'image');
            $attr = $xml_enclousure->addChild("media:thumbnail");
            $attr->addAttribute('url', wp_get_attachment_thumb_url($attachment->ID));
            $attr->addAttribute('type', get_post_mime_type($attachment->ID));

            $meta_credit = 0;
            $meta_copyright = 0;
            if (isset($temp['image_meta'])) {
                if (isset($temp['image_meta']['credit'])) {
                    $meta_credit = $temp['image_meta']['credit'];
                }
                if (isset($temp['image_meta']['copyright'])) {
                    $meta_copyright = $temp['image_meta']['copyright'];
                }
            }

            $xml_enclousure->addChild("media:credit", UDGENFEED_WEBSITE);
            $xml_enclousure->addChild("media:copyright", UDGENFEED_WEBSITE);
            $xml_enclousure->addChild("media:title", 'ภาพประกอบข่าว');
            $xml_enclousure->addChild("media:text", 'ภาพประกอบข่าว');

            $xml_region = $xml_enclousure->addChild("mi:focalRegion", null, UDGENFEED_XMLNS_MI);
            $xml_region->addChild("mi:x1", (isset($temp['width']) ? $temp['width'] : 0));
            $xml_region->addChild("mi:y1", (isset($temp['height']) ? $temp['height'] : 0));
            $xml_region->addChild("mi:x2", (isset($temp['width']) ? $temp['width'] : 0));
            $xml_region->addChild("mi:y2", (isset($temp['height']) ? $temp['height'] : 0));
            $xml_enclousure->addChild("mi:hasSyndicationRights", 1, UDGENFEED_XMLNS_MI);
            $xml_enclousure->addChild("mi:licenseId", $meta_copyright, UDGENFEED_XMLNS_MI);
            $xml_enclousure->addChild("mi:licensorName", $meta_copyright, UDGENFEED_XMLNS_MI);
        }
    }

    $videos = get_attached_media('video', $post->ID);
    if ($videos) {
        foreach ($videos as $attachment) {
            //echo print_r($attachment);
            $temp = get_post_meta($attachment->ID, '_wp_attachment_metadata', true);
            $xml_enclousure = $xml_item->addChild("media:content", null, true);
            $xml_enclousure->addAttribute('url', $attachment->guid);
            $xml_enclousure->addAttribute('id', $attachment->ID);
            $xml_enclousure->addAttribute('duration', $temp['length']);
            $xml_enclousure->addAttribute('type', get_post_mime_type($attachment->ID));
            $xml_enclousure->addChild("media:thumbnail");
            $xml_enclousure->addChild("media:copyright");
            $xml_enclousure->addChild("media:title", $attachment->post_title);
            $xml_enclousure->addChild("media:description", $attachment->post_name);
        }
    }
    endwhile;
    echo $xml_root->asXML();
}

function udgenfeed_msn_gallery_function()
{
    header('Content-type: text/xml');
    $datequery = date('Y-m-d', strtotime("-1 days"));
    $timequery = strtotime($datequery);
    $xml_root = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8" ?' . '><rss xmlns:dc="' . UDGENFEED_XMLNS_DC . '" xmlns:media="' . UDGENFEED_XMLNS_MEDIA . '" xmlns:mi="' . UDGENFEED_XMLNS_MI . '" version="2.0"></rss>');
    $xml_data = $xml_root->addChild("channel");
    $news = new WP_query(
        array(
            'posts_per_page' => 100,
            'no_found_rows'  => true,
            'date_query'     => array(
                array(
                    'year'  => date('Y', $timequery),
                    'month' => date('m', $timequery),
                    'day'   => date('d', $timequery),
                )
            )
        )
    );
    global $post;
    $xml_data->addChild("title", 'Gallery');
    $xml_data->addChild("language", "th-TH");
    $xml_data->addChild("version", "1.12");
    $xml_data->addChild("link", get_bloginfo('url') . '?feed=udgenfeedmsngallery');

    while ($news->have_posts()) : $news->the_post();

    $xml_item = $xml_data->addChild("item");
    $xml_item->addChild("title")->addCData(get_the_title());
    $xml_item->addChild("link")->addCData(get_the_permalink());

    $post_date_time = strtotime($post->post_date);
    $post_modified_time = strtotime($post->post_modified_gmt);

    $xml_item->addChild("pubDate", date("Y-m-d", $post_date_time) . 'T' . date("H:i:s", $post_date_time) . '+0700');
    $temp1 = $xml_item->addChild("guid", $post->ID);
    $temp1->addAttribute('isPermaLink', 'false');
    $xml_item->addChild("description", nl2br(htmlspecialchars(get_the_content())));

    $usera = get_userdata($post->post_author);
    $xml_item->addChild("dc:alternative", get_the_title(), UDGENFEED_XMLNS_DC);
    $xml_item->addChild("dc:abstract", get_the_title(), UDGENFEED_XMLNS_DC);
    $xml_item->addChild("dc:publisher", $usera->data->display_name, UDGENFEED_XMLNS_DC);
    $xml_item->addChild("dc:creator", $usera->data->display_name, UDGENFEED_XMLNS_DC);
    $xml_item->addChild("dc:modified", date("Y-m-d", $post_modified_time) . 'T' . date("H:i:s", $post_modified_time) . '+0700', UDGENFEED_XMLNS_DC);
    $xml_item->addChild("mi:dateTimeWritten", date("Y-m-d", $post_modified_time) . 'T' . date("H:i:s", $post_modified_time) . '+0700', UDGENFEED_XMLNS_MI);

    // feature image
    $feature_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
    $feature_image_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');

    if (($feature_image) && ($feature_image_thumbnail)) {
        $image_obj = get_post(get_post_thumbnail_id($post->ID));
        $post_mime_type = "image";
        $post_title = "";
        if ($image_obj) {
            $post_mime_type = $image_obj->post_mime_type;
            $post_title = $image_obj->post_title;
        }
        $xml_feature = $xml_item->addChild("media:content", null, UDGENFEED_XMLNS_MEDIA);
        $xml_feature->addAttribute('url', $feature_image[0]);
        $attr = $xml_feature->addChild("media:thumbnail");
        $attr->addAttribute('url', $feature_image_thumbnail[0]);
        $attr->addAttribute('type', $post_mime_type);

        $xml_feature->addChild("media:credit", UDGENFEED_WEBSITE);
        $xml_feature->addChild("media:copyright", UDGENFEED_WEBSITE);
        $xml_feature->addChild("media:title", 'ภาพประกอบข่าว');
        $xml_feature->addChild("media:text", 'ภาพประกอบข่าว');

        $xml_region = $xml_feature->addChild("mi:focalRegion", null, UDGENFEED_XMLNS_MI);
        $xml_region->addChild("mi:x1", (isset($feature_image[1]) ? $feature_image[1] : 0));
        $xml_region->addChild("mi:y1", (isset($feature_image[2]) ? $feature_image[2] : 0));
        $xml_region->addChild("mi:x2", (isset($feature_image[1]) ? $feature_image[1] : 0));
        $xml_region->addChild("mi:y2", (isset($feature_image[2]) ? $feature_image[2] : 0));
        $xml_feature->addChild("mi:hasSyndicationRights", 1, UDGENFEED_XMLNS_MI);
        $xml_feature->addChild("mi:licenseId", UDGENFEED_WEBSITE, UDGENFEED_XMLNS_MI);
        $xml_feature->addChild("mi:licensorName", UDGENFEED_WEBSITE, UDGENFEED_XMLNS_MI);
    }

    $medias = get_attached_media('image', $post->ID);
    if ($medias) {
        foreach ($medias as $attachment) {
            $temp = get_post_meta($attachment->ID, '_wp_attachment_metadata', true);
            $xml_enclousure = $xml_item->addChild("media:content", null, UDGENFEED_XMLNS_MEDIA);
            $xml_enclousure->addAttribute('url', $attachment->guid);
            $attr = $xml_enclousure->addChild("media:thumbnail");
            $attr->addAttribute('url', wp_get_attachment_thumb_url($attachment->ID));
            $attr->addAttribute('type', get_post_mime_type($attachment->ID));

            $meta_credit = 0;
            $meta_copyright = 0;
            if (isset($temp['image_meta'])) {
                if (isset($temp['image_meta']['credit'])) {
                    $meta_credit = $temp['image_meta']['credit'];
                }
                if (isset($temp['image_meta']['copyright'])) {
                    $meta_copyright = $temp['image_meta']['copyright'];
                }
            }

            $xml_enclousure->addChild("media:credit", UDGENFEED_WEBSITE);
            $xml_enclousure->addChild("media:copyright", UDGENFEED_WEBSITE);
            $xml_enclousure->addChild("media:title", 'ภาพประกอบข่าว');
            $xml_enclousure->addChild("media:text", 'ภาพประกอบข่าว');

            $xml_region = $xml_enclousure->addChild("mi:focalRegion", null, UDGENFEED_XMLNS_MI);
            $xml_region->addChild("mi:x1", (isset($temp['width']) ? $temp['width'] : 0));
            $xml_region->addChild("mi:y1", (isset($temp['height']) ? $temp['height'] : 0));
            $xml_region->addChild("mi:x2", (isset($temp['width']) ? $temp['width'] : 0));
            $xml_region->addChild("mi:y2", (isset($temp['height']) ? $temp['height'] : 0));
            $xml_enclousure->addChild("mi:hasSyndicationRights", 1, UDGENFEED_XMLNS_MI);
            $xml_enclousure->addChild("mi:licenseId", $meta_copyright, UDGENFEED_XMLNS_MI);
            $xml_enclousure->addChild("mi:licensorName", $meta_copyright, UDGENFEED_XMLNS_MI);
        }
    }

    $videos = get_attached_media('video', $post->ID);
    if ($videos) {
        foreach ($videos as $attachment) {
            //echo print_r($attachment);
            $temp = get_post_meta($attachment->ID, '_wp_attachment_metadata', true);
            $xml_enclousure = $xml_item->addChild("media:content", null, true);
            $xml_enclousure->addAttribute('url', $attachment->guid);
            $xml_enclousure->addAttribute('id', $attachment->ID);
            $xml_enclousure->addAttribute('duration', $temp['length']);
            $xml_enclousure->addAttribute('type', get_post_mime_type($attachment->ID));
            $xml_enclousure->addChild("media:thumbnail");
            $xml_enclousure->addChild("media:copyright");
            $xml_enclousure->addChild("media:title", $attachment->post_title);
            $xml_enclousure->addChild("media:description", $attachment->post_name);
        }
    }
    endwhile;
    echo $xml_root->asXML();
}

function udgenfeed_line_function()
{
    Header('Content-type: text/xml');
    //Header('Content-type: text/html');
    $slug = $_GET['slug'];
    if (empty($slug)) {
        return false;
    }
    $currenttime = time();
    $the_query = new WP_query(
        array(
            'posts_per_page' => 100,
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'no_found_rows'  => true,
            'order'          => 'DESC',
            'orderby'        => 'modified',
            "tax_query"      => array(
                array(
                    "taxonomy" => "line",
                    "field"    => "slug",
                    "terms"    => $slug
                )
            ),
        )
    );

    $rec_query = new WP_query(
        array(
            'posts_per_page' => 3,
            'orderby'        => 'publish_date',
            'order'          => 'DESC',
            "tax_query"      => array(
                array(
                    "taxonomy" => "line",
                    "field"    => "slug",
                    "terms"    => $slug
                )
            )
        )
    );
    $rec_posts = $rec_query->posts;

    global $post;

    $xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8" ?' . '><articles></articles>');
    $xml->addChild('UUID', time());
    $xml->addChild('time', time() . '000');

    $cate2 = get_terms('category', array('parent' => 0, 'fields' => 'names'));

    while ($the_query->have_posts()) : $the_query->the_post();
    //TODO: dirty hack prevent posts which their published date is "0000-00-00 00:00:00"
    // don't know why?
    if ($post->post_date_gmt === '0000-00-00 00:00:00') {
        continue;
    }

    $xml_article = $xml->addChild('article');
    $xml_article->addChild('ID', $post->ID);
    $xml_article->addChild('nativeCountry', 'TH');
    $xml_article->addChild('language', 'th');
    $xml_article->addChild('publishCountries', 'th');
    $xml_article->addChild('excludedCountries', '');
    $xml_article->addChild('startYmdtUnix', time() . '000'); //nano timestamp // (strtotime($post->post_date_gmt) + intval(60 * 2)) * 1000
        $xml_article->addChild('endYmdtUnix', strtotime('+90 days') . '000'); //nanotimestamp // (strtotime($post->post_date_gmt) + intval(60 * 60 * 24 * 35)) * 1000
        $xml_article->addChild('title', htmlspecialchars($post->post_title));

    $main_category = get_main_category($post->ID);
    $main_category_name = '';
    if (! empty($main_category)) {
        $main_category_name = $main_category->name;
    }
    //$xml_article->addChild('category', implode(', ', wp_get_post_categories( $post->ID , array( 'fields' => 'names' ))));
    $xml_article->addChild('category', $main_category_name);
    // $xml_article->addChild('subCategory', '');
    $xml_article->addChild('publishTimeUnix', strtotime($post->post_date_gmt) . '000'); // nanotimestamp
    $xml_article->addChild('updateTimeUnix', strtotime($post->post_modified_gmt) . '000'); // nanotimestamp
    $xml_content = $xml_article->addChild('contents');
    $xml_image = $xml_content->addChild('image');
    $attach = get_post(get_post_thumbnail_id());
    $xml_image->addChild('title', htmlspecialchars($attach->post_title));
    $xml_image->addChild('description', $attach->post_excerpt);
    $xml_image->addChild('url', $attach->guid);
    $xml_image->addChild('thumbnail', wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail')[0]);
    $xml_text = $xml_content->addChild('text');

    $content = get_the_content();
    $content2 = $content;
    $content = strip_shortcodes($content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);

    //reject overly long 2 byte sequences, as well as characters above U+10000 and replace with ?
    $content = preg_replace(
        '/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]' .
            '|[\x00-\x7F][\x80-\xBF]+' .
            '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*' .
            '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})' .
            '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
        '',
        $content
    );

    //reject overly long 3 byte sequences and UTF-16 surrogates and replace with ?
    $content = preg_replace('/\xE0[\x80-\x9F][\x80-\xBF]' .
            '|\xED[\xA0-\xBF][\x80-\xBF]/S', '', $content);

    $excerpt_length = apply_filters('excerpt_length', 200);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
    $excerpt = wp_trim_words($content, $excerpt_length, $excerpt_more);
    $excerpt .= '<a href="' . get_permalink($post->ID) . '" >อ่านต่อ</a>';

    //$xml_text->addChild('content_short', htmlspecialchars(apply_filters('the_content', $excerpt)));
    $xml_text->addChild('content')->addCData(htmlspecialchars($content));
    $pattern = get_shortcode_regex();
    if (preg_match_all('/' . $pattern . '/s', $content2, $matches)
            && array_key_exists(2, $matches)
            && in_array('udplayer2', $matches[2])) {
        foreach ($matches[0] as $m) {
            $attr = shortcode_parse_atts($m);
            if (isset($attr['originalsrc'])) {
                $xml_video = $xml_content->addChild('video');
                $xml_video->addChild('title', $post->post_title);
                $xml_video->addChild('description', "");
                $xml_video->addChild('url', $attr['originalsrc']);
            }
        }
    }

    if (preg_match_all('@(https?://)?(?:www\.)?(youtu(?:\.be/([-\w]+)|be\.com/watch\?v=([-\w]+)))\S*@im', $content2, $matches)) {
        if ((isset($matches[0])) && (isset($matches[4]))) {
            if ((is_array($matches[0])) && (is_array($matches[4])) && (sizeof($matches[0]) == sizeof($matches[4]))) {
                $youtubes = $matches[0];
                $code = $matches[4];
                $index = 0;
                foreach ($youtubes as $youtube) {
                    $xml_video = $xml_content->addChild('video');
                    $xml_video->addChild('title', 'youtube');
                    $xml_video->addChild('description', "youtube");
                    $xml_video->addChild('url', "https://www.youtube.com/embed/" . $code[$index]);
                    $xml_video->addChild('original_url', htmlspecialchars($youtube));
                    $index++;
                }
            }
        }
    }

    //        $xml_article->addChild('author', get_the_author_meta('display_name', $post->post_author));
    $xml_article->addChild('sourceUrl', get_permalink($post->ID));

    // rec article

    $xml_rec_articles = $xml_article->addChild('recommendArticles');
    foreach ($rec_posts as $rec_post) {
        $xml_rec_article = $xml_rec_articles->addChild('article');
        $xml_rec_article->addChild('title', htmlspecialchars($rec_post->post_title));
        $xml_rec_article->addChild('url', get_permalink($rec_post->ID));
        $xml_rec_article->addChild('thumbnail', get_the_post_thumbnail_url($rec_post->ID, 'post-thumbnail'));
    }
    endwhile;
    /* $content = $xml->asXML();
    echo $content; */
    $dom_sxe = dom_import_simplexml($xml);
    $dom_output = new DOMDocument('1.0');
    $dom_output->formatOutput = true;
    $dom_sxe = $dom_output->importNode($dom_sxe, true);
    $dom_sxe = $dom_output->appendChild($dom_sxe);
    echo $dom_output->saveXML($dom_output, LIBXML_NOEMPTYTAG);
}

function udgenfeedxml_function()
{
    Header('Content-type: text/xml');
    $the_query = new WP_query(
        array(
            'post_type'      => 'post',
            'posts_per_page' => 20,
            'no_found_rows'  => true,
            'post_status'    => 'publish'
        )
    );
    global $post;
    $xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . '><content  ></content>');
    while ($the_query->have_posts()) : $the_query->the_post();
    $xml_article = $xml->addChild('article');
    $xml_article->addChild('pub', UDGENFEED_WEBSITE);
    $xml_article->addChild('artid', $post->ID);
    $xml_article->addChild('launchdate', date('Y-m-d H:i:s', strtotime($post->post_date_gmt)));
    $xml_article->addChild('copyright', UDGENFEED_WEBSITE);

    $categories_arr = [];
    $categories = get_the_category($post->ID);
    foreach ($categories as $cate) {
        array_push($categories_arr, $cate->name);
    }

    $xml_article->addChild('category1', implode(', ', $categories_arr));
    $xml_article->addChild('link', get_the_permalink($post->ID));
    $xml_article->addChild('kick', '');
    $xml_article->addChild('headline1', (htmlspecialchars(get_the_title($post->ID), ENT_XML1)));
    $xml_article->addChild('abstract', (htmlspecialchars(get_the_excerpt($post->ID), ENT_XML1)));

    $content = get_the_content($post->ID);
    $content = strip_shortcodes($content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);

    $xml_article->addChild('content', (htmlspecialchars($content, ENT_XML1)));
    $xml_article->addChild('author', get_the_author_meta('display_name', $post->post_author));
    $xml_article->addChild('title', '');
    $xml_article->addChild('backgroundstory', '');
    $xml_article->addChild('article_image', get_the_post_thumbnail_url($post->ID, 'post-thumbnail'));
    $xml_article->addChild('caption', htmlspecialchars(get_the_title($post->ID), ENT_XML1));
    $xml_article->addChild('credit', UDGENFEED_WEBSITE);
    $xml_article->addChild('attachments', '');
    endwhile;
    echo $xml->asXML();
}

<?php

namespace UDFixAndOptimize\Core;

use UDFixAndOptimize\UDFixAndOptimize;
use UDFixAndOptimize\UDOptionFramework\OptionFramework;
use UDFixAndOptimize\Util\ImageEditor;

if (! defined('ABSPATH')) {
    exit;
}

class PageSpeed
{

    public function __construct()
    {
        if (true === OptionFramework::getOptionValue('pagespeed_image_optimize_enable', UDFixAndOptimize::OPTION_KEY)) {
            add_filter('wp_image_editors', array($this, 'addImageEditorHook'), 10, 1);

            if (true === OptionFramework::getOptionValue('pagespeed_original_image_optimize_enable', UDFixAndOptimize::OPTION_KEY)) {
                add_filter('add_attachment', array($this, 'addAttachmentHook'));
            }
        }

        if (! empty(OptionFramework::getOptionValue('pagespeed_defer_js_list', UDFixAndOptimize::OPTION_KEY))
            or ! empty(OptionFramework::getOptionValue('pagespeed_async_js_list', UDFixAndOptimize::OPTION_KEY))) {
            add_filter('script_loader_tag', array($this, 'scriptLoaderTagHook'), 10, 3);
        }
    }

    public function addImageEditorHook($editors = array())
    {
        array_unshift($editors, ImageEditor::class);

        return $editors;
    }

    public function addAttachmentHook($attachment_id)
    {
        $this->optimizeOriginalImage($attachment_id);

        return $attachment_id;
    }

    public function scriptLoaderTagHook($tag, $handle, $src)
    {
        $tag = $this->optimizeJSLoading($tag, $handle, $src);

        return $tag;
    }

    public function optimizeOriginalImage($attachment_id)
    {
        $attachment = get_post($attachment_id);
        $mime_type = get_post_mime_type($attachment);

        $file = get_attached_file($attachment_id);
        if ($file === false) {
            return new \WP_Error('image_save_error', "Can't get attached file");
        }

        if ('image/jpeg' == $mime_type || 'image/png' == $mime_type) {

            /**
             * @var ImageEditor $editor
             */
            $editor = wp_get_image_editor($file);
            if (is_wp_error($editor)) {
                return $editor;
            }

            if( $editor instanceof ImageEditor){
                $editor->optimizeImage();
            }

            $result = $editor->save($file);

            if (is_wp_error($result)) {
                return $result;
            }

            unset($editor);
        }

        return true;
    }

    private function optimizeJSLoading($tag, $handle, $src)
    {

        $defer_js_list = array_map('trim', explode(',', OptionFramework::getOptionValue('pagespeed_defer_js_list', UDFixAndOptimize::OPTION_KEY)));
        $async_js_list = array_map('trim', explode(',', OptionFramework::getOptionValue('pagespeed_async_js_list', UDFixAndOptimize::OPTION_KEY)));

        if (in_array($handle, $defer_js_list)) {
            return '<script src="' . $src . '" type="text/javascript" defer></script>' . "\n";
        }

        if (in_array($handle, $async_js_list)) {
            return '<script src="' . $src . '" type="text/javascript" async></script>' . "\n";
        }

        return $tag;
    }
}

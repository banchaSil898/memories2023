<?php

namespace UDStickyPostManager\Util;

if (! defined('ABSPATH')) {
    exit;
}

class JSBuffer
{
    private static $js_header_var_buffer = '';
    private static $js_footer_var_buffer = '';

    private static $js_header_buffer_rendered = false;
    private static $js_footer_buffer_rendered = false;

    public function __construct()
    {
        add_action('wp_head', array(JSBuffer::class, 'renderHeaderJSVar'));
        add_action('wp_footer', array(JSBuffer::class, 'renderFooterJSVar'));
    }

    public static function addJSVar($name, $value, $in_footer = false)
    {
        if (false === $in_footer) {
            if (true === self::$js_header_buffer_rendered) {
                throw new \ErrorException('JSBuffer::add_js_var_to_header - already rendered js variable on header, this function was called too late');
            }
            self::$js_header_var_buffer .= 'var ' . $name . '=' . wp_json_encode($value) . ';';
        } else {
            if (true === self::$js_footer_buffer_rendered) {
                throw new \ErrorException('JSBuffer::add_js_var_to_footer - already rendered js variable on footer, this function was called too late');
            }
            self::$js_footer_var_buffer .= 'var ' . $name . '=' . wp_json_encode($value) . ';';
        }
    }

    public static function renderHeaderJSVar()
    {
        self::$js_header_buffer_rendered = true;
        echo '<script type="text/javascript">' . self::$js_header_var_buffer . '</script>';
    }

    public static function renderFooterJSVar()
    {
        self::$js_footer_buffer_rendered = true;
        echo '<script type="text/javascript">' . self::$js_footer_var_buffer . '</script>';
    }
}

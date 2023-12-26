<?php

namespace UDFixAndOptimize\Util;

if (! defined('ABSPATH')) {
    exit;
}

class ImageEditor extends \WP_Image_Editor_Imagick
{
    public function optimizeImage()
    {
        try {
            if ('image/jpeg' == $this->mime_type) {
                $this->image->setSamplingFactors(array('2x2', '1x1', '1x1'));
                $this->image->transformImageColorspace(\Imagick::COLORSPACE_SRGB);
            }
        } catch (\Exception $e) {
            return new \WP_Error('image_resize_error', $e->getMessage());
        }

        $result = $this->thumbnail_image($this->size['width'], $this->size['height']);
        if (is_wp_error($result)) {
            return $result;
        }

        return true;
    }

    protected function strip_meta()
    {
        $result = parent::strip_meta();

        if (is_wp_error($result)) {
            return $result;
        }

        try {
            // Strip all profiles.
            foreach ($this->image->getImageProfiles('*', true) as $key => $value) {
                $this->image->removeImageProfile($key);
            }
        } catch (\Exception $e) {
            return new \WP_Error('image_strip_meta_error', $e->getMessage());
        }

        return true;
    }


    protected function thumbnail_image($dst_w, $dst_h, $filter_name = 'FILTER_TRIANGLE', $strip_meta = true)
    {
        $result = parent::thumbnail_image($dst_w, $dst_h, $filter_name, $strip_meta);

        if (is_wp_error($result)) {
            return $result;
        }

        try {
            if ('image/jpeg' == $this->mime_type) {
                $this->image->setInterlaceScheme(\Imagick::INTERLACE_JPEG);
            }
        } catch (\Exception $e) {
            return new \WP_Error('image_resize_error', $e->getMessage());
        }

        return $result;
    }
}

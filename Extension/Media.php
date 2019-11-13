<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait Media
{
    public function addMedia()
    {
        $this->wpGetAttachmentImage();
    }

    public function wpGetAttachmentImage()
    {
        if (function_exists('wp_get_attachment_image')) {
            $this->twig->addFunction(new TwigFunction('wp_get_attachment_image', function (int $attachment_id, $size = 'thumbnail', bool $icon = false, $attr = '') {
                return wp_get_attachment_image($attachment_id, $size, $icon, $attr);
            }, ['is_safe' => ['html']]));
        }
    }
}

<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait PostThumbnailTemplate
{
    public function addPostThumbnailTemplate()
    {
        $this->getThePostThumbnail();
        $this->wpGetAttachmentImage();
    }

    public function getThePostThumbnail()
    {
        if (function_exists('get_the_post_thumbnail')) {
            $this->twig->addFunction(new TwigFunction('get_the_post_thumbnail', function ($post = null, $size = 'post-thumbnail', $attr = '') {
                return get_the_post_thumbnail($post, $size, $attr);
            }, ['is_safe' => ['html']]));
        }
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

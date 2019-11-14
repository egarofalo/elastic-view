<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait PostThumbnailTemplate
{
    public function addPostThumbnailTemplate()
    {
        $this->getThePostThumbnail();
    }

    public function getThePostThumbnail()
    {
        if (function_exists('get_the_post_thumbnail')) {
            $this->twig->addFunction(new TwigFunction('get_the_post_thumbnail', function ($post = null, $size = 'post-thumbnail', $attr = '') {
                return get_the_post_thumbnail($post, $size, $attr);
            }, ['is_safe' => ['html']]));
        }
    }
}

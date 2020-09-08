<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait PostThumbnailTemplate
{
    public function addPostThumbnailTemplate()
    {
        $this->getThePostThumbnail();
        $this->hasPostThumbnail();
    }

    public function getThePostThumbnail()
    {
        if (function_exists('get_the_post_thumbnail')) {
            $this->twig->addFunction(
                new TwigFunction(
                    'get_the_post_thumbnail',
                    function ($post = null, $size = 'post-thumbnail', $attr = '') {
                        return get_the_post_thumbnail($post, $size, $attr);
                    },
                    ['is_safe' => ['html']]
                )
            );
        }
    }

    public function hasPostThumbnail()
    {
        if (function_exists('has_post_thumbnail')) {
            $this->twig->addFunction(
                new TwigFunction(
                    'has_post_thumbnail',
                    function ($post = null) {
                        return has_post_thumbnail($post);
                    }
                )
            );
        }
    }
}

<?php namespace RainLab\Blog\Components;

use Cms\Classes\ComponentBase;

class Post extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => 'Blog Post',
            'description' => 'Displays a blog post on the page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'param-id' => [
                'description' => 'The URL route parameter used for looking up the post by its ID or slug.',
                'title' => 'ID parameter',
                'default' => ':post',
                'type'=>'string'
            ]
        ];
    }

}
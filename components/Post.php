<?php namespace RainLab\Blog\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;

class Post extends ComponentBase
{
    public $post;

    public function componentDetails()
    {
        return [
            'name'        => 'Blog Post',
            'description' => 'Displays a blog post on the page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'idParam' => [
                'title'       => 'Slug param name',
                'description' => 'The URL route parameter used for looking up the post by its slug.',
                'default'     => ':slug',
                'type'        => 'string'
            ],
        ];
    }

    public function onRun()
    {
        $this->post = $this->page['post'] = $this->loadPost();
    }

    protected function loadPost()
    {
        $slug = $this->propertyOrParam('idParam');
        return BlogPost::isPublished()->where('slug', '=', $slug)->first();
    }
}
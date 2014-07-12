<?php namespace RainLab\Blog\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;

class Post extends ComponentBase
{
    public $post;
    public $categoryPage;
    public $categoryPageIdParam;

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
            'categoryPage' => [
                'title'       => 'Category page',
                'description' => 'Name of the category page file for the category links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'blog/category',
                'group'       => 'Links',
            ],
            'categoryPageIdParam' => [
                'title'       => 'Category page param name',
                'description' => 'The expected parameter name used when creating links to the category page.',
                'type'        => 'string',
                'default'     => ':slug',
                'group'       => 'Links',
            ],
        ];
    }

    public function onRun()
    {
        $this->post = $this->page['post'] = $this->loadPost();
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->categoryPageIdParam = $this->page['categoryPageIdParam'] = $this->property('categoryPageIdParam');
    }

    protected function loadPost()
    {
        $slug = $this->propertyOrParam('idParam');
        return BlogPost::isPublished()->where('slug', '=', $slug)->first();
    }
}
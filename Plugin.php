<?php namespace RainLab\Blog;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name' => 'Blog',
            'description' => 'A robust blogging platform.',
            'author' => 'Alexey Bobkov, Samuel Georges',
            'icon' => 'icon-pencil'
        ];
    }

    public function registerComponents()
    {
        return [
            'RainLab\Blog\Components\Post' => 'blogPost',
        ];
    }

    public function registerNavigation()
    {
        return [
            'blog' => [
                'label' => 'Blog',
                'url' => Backend::url('rainlab/blog/posts'),
                'icon' => 'icon-pencil',
                'permissions' => ['blog:*'],
                'order' => 500,
                'sideMenu' => [
                    'posts' => [
                        'label' => 'Posts',
                        'icon' => 'icon-copy',
                        'url' => Backend::url('rainlab/blog/posts'),
                        'permissions' => ['blog:access_posts'],
                    ],
                    'categories' => [
                        'label' => 'Categories',
                        'icon' => 'icon-copy',
                        'url' => Backend::url('rainlab/blog/categories'),
                        'permissions' => ['blog:access_categories'],
                    ],
                ]
            ]
        ];
    }

}
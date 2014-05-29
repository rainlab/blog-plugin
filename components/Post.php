<?php namespace RainLab\Blog\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;
use RainLab\Blog\Models\Category as BlogCategory;

class Post extends ComponentBase
{
    public $post;
    public $categoryFilter;

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
            'paramId' => [
                'title'       => 'Slug param name',
                'description' => 'The URL route parameter used for looking up the post by its slug.',
                'default'     => 'slug',
                'type'        => 'string'
            ],
            'categoryFilter' => [
                'title' => 'Category filter',
                'description' => 'Name of the category to filter by.',
                'type' => 'dropdown',
                'default' => null
            ],
        ];
    }

    protected function getCategoryFilterData()
    {
        return array_merge([[-1, 'N/A', false]],
            array_map(function($val) {
                    return [$val['id'], $val['name'], true];
                }, BlogCategory::select('id', 'name')
                    ->get()
                    ->toArray()
            ));
    }

    public function getCategoryFilterOptions()
    {
        return array_map(function($val) {
            return $val[1];
        }, $this->getCategoryFilterData());
    }

    public function onRun()
    {
        $this->categoryFilter = $this->page['categoryFilter'] = $this->getCategoryFilterData()[$this->property('categoryFilter')];
        $this->categoryFilter = $this->categoryFilter[2] ? [ $this->categoryFilter[0] ] : null;

        $this->post = $this->page['blogPost'] = $this->loadPost();
    }

    protected function loadPost()
    {
        $slug = $this->param($this->property('paramId'));
        $posts = BlogPost::isPublished()->where('slug', '=', $slug);

        if ($this->categoryFilter)
            $posts->whereHas('categories', function($query)
            {
                $query->whereIn('id', $this->categoryFilter);
            });

        return $posts->first();
    }
}
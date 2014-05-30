<?php namespace RainLab\Blog\Components;

use DB;
use App;
use Request;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Category as BlogCategory;

class Categories extends ComponentBase
{
    public $categories;
    public $categoryPage;
    public $currentCategorySlug;
    public $categoryPageIdParam;

    public function componentDetails()
    {
        return [
            'name'        => 'Blog Category List',
            'description' => 'Displays a list of blog categories on the page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'idParam' => [
                'title'       => 'Slug param name',
                'description' => 'The URL route parameter used for looking up the current category by its slug. This property is used by the default component partial for marking the currently active category.',
                'default'     => ':slug',
                'type'        => 'string'
            ],
            'categoryPage' => [
                'title'       => 'Category page',
                'description' => 'Name of the category page file for the category links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'blog/category'
            ],
            'categoryPageIdParam' => [
                'title'       => 'Category page param name',
                'description' => 'The expected parameter name used when creating links to the category page.',
                'type'        => 'string',
                'default'     => ':slug',
            ],
            'displayEmpty' => [
                'title'       => 'Display empty categories',
                'description' => 'Show categories that do not have any posts.',
                'type'        => 'checkbox',
                'default'     => 0
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->categories = $this->page['categories'] = $this->loadCategories();
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->categoryPageIdParam = $this->page['categoryPageIdParam'] = $this->property('categoryPageIdParam');
        $this->currentCategorySlug = $this->page['currentCategorySlug'] = $this->propertyOrParam('idParam');
    }

    protected function loadCategories()
    {
        $categories = BlogCategory::orderBy('name');
        if (!$this->property('displayEmpty')) {
            $categories->whereExists(function($query) {
                $query->select(DB::raw(1))
                ->from('rainlab_blog_posts_categories')
                ->join('rainlab_blog_posts', 'rainlab_blog_posts.id', '=', 'rainlab_blog_posts_categories.post_id')
                ->whereNotNull('rainlab_blog_posts.published')
                ->where('rainlab_blog_posts.published', '=', 1)
                ->whereRaw('rainlab_blog_categories.id = rainlab_blog_posts_categories.category_id');
            });
        }

        return $categories->get();
    }
}
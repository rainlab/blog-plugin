<?php namespace RainLab\Blog\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Category as BlogCategory;
use Cms\Classes\CmsPropertyHelper;
use Request;
use App;
use DB;

class Categories extends ComponentBase
{
    public $categories;
    public $categoryPage;
    public $currentCategorySlug;
    
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
            'categoryPage' => [
                'title' => 'Category page',
                'description' => 'Name of the category page file for the category links. This property is used by the default component partial.',
                'type'=>'dropdown',
                'default' => 'blog/category'
            ],
            'displayEmpty' => [
                'title' => 'Display empty categories',
                'description' => 'Show categories that do not have any posts.',
                'type'=>'checkbox',
                'default' => 0
            ],
            'paramId' => [
                'description' => 'The URL route parameter used for looking up the current category by its slug. This property is used by the default component partial for marking the currently active category.',
                'title'       => 'Slug param name',
                'default'     => 'slug',
                'type'        => 'string'
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return CmsPropertyHelper::listPages();;
    }

    public function onRun()
    {
        $this->categories = $this->page['categories'] = $this->loadCategories();
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->currentCategorySlug = $this->page['currentCategorySlug'] = $this->param($this->property('paramId'));
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
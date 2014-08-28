<?php namespace RainLab\Blog\Components;

use DB;
use App;
use Request;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Category as BlogCategory;

class Categories extends ComponentBase
{
    /**
     * @var Collection A collection of categories to display
     */
    public $categories;

    /**
     * @var string Reference to the page name for linking to categories.
     */
    public $categoryPage;

    /**
     * @var string Reference to the current category slug.
     */
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
            'idParam' => [
                'title'       => 'Slug param name',
                'description' => 'The URL route parameter used for looking up the current category by its slug. This property is used by the default component partial for marking the currently active category.',
                'default'     => ':slug',
                'type'        => 'string'
            ],
            'displayEmpty' => [
                'title'       => 'Display empty categories',
                'description' => 'Show categories that do not have any posts.',
                'type'        => 'checkbox',
                'default'     => 0
            ],
            'categoryPage' => [
                'title'       => 'Category page',
                'description' => 'Name of the category page file for the category links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'blog/category',
                'group'       => 'Links',
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->currentCategorySlug = $this->page['currentCategorySlug'] = $this->propertyOrParam('idParam');
        $this->categories = $this->page['categories'] = $this->loadCategories();
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

        $categories = $categories->get();

        /*
         * Add a "url" helper attribute for linking to each category
         */
        $categories->each(function($category){
            $category->setUrl($this->categoryPage, $this->controller);
        });

        return $categories;
    }
}
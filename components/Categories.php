<?php namespace RainLab\Blog\Components;

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

    /**
     * componentDetails
     */
    public function componentDetails()
    {
        return [
            'name' => "Category List",
            'description' => "Displays a list of blog categories on the page.",
        ];
    }

    /**
     * defineProperties
     */
    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => "Category slug",
                'description' => "Look up the blog category using the supplied slug value. This property is used by the default component partial for marking the currently active category.",
                'default' => '{{ :slug }}',
                'type' => 'string',
            ],
            'displayEmpty' => [
                'title' => "Display empty categories",
                'description' => "Show categories that do not have any posts.",
                'type' => 'checkbox',
                'default' => 0,
            ],
            'categoryPage' => [
                'title' => "Category page",
                'description' => "Name of the category page file for the category links. This property is used by the default component partial.",
                'type' => 'dropdown',
                'default' => 'blog/category',
                'group' => "Links",
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->currentCategorySlug = $this->page['currentCategorySlug'] = $this->property('slug');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->categories = $this->page['categories'] = $this->loadCategories();
    }

    /**
     * Load all categories or, depending on the <displayEmpty> option, only those that have blog posts
     * @return mixed
     */
    protected function loadCategories()
    {
        $categories = BlogCategory::with('posts_count')->getNested();

        if (!$this->property('displayEmpty')) {
            $iterator = function ($categories) use (&$iterator) {
                return $categories->reject(function ($category) use (&$iterator) {
                    if ($category->getNestedPostCount() == 0) {
                        return true;
                    }
                    if ($category->children) {
                        $category->children = $iterator($category->children);
                    }
                    return false;
                });
            };
            $categories = $iterator($categories);
        }

        // Add a "url" helper attribute for linking to each category
        return $this->linkCategories($categories);
    }

    /**
     * Sets the URL on each category according to the defined category page
     * @return void
     */
    protected function linkCategories($categories)
    {
        return $categories->each(function ($category) {
            $category->setUrl($this->categoryPage, $this->controller);

            if ($category->children) {
                $this->linkCategories($category->children);
            }
        });
    }
}

<?php namespace RainLab\Blog\Components;

use Lang;
use Response;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;
use RainLab\Blog\Models\Category as BlogCategory;

class RssFeed extends ComponentBase
{
    /**
     * A collection of posts to display
     * @var Collection
     */
    public $posts;

    /**
     * If the post list should be filtered by a category, the model to use.
     * @var Model
     */
    public $category;

    /**
     * Reference to the page name for the main blog page.
     * @var string
     */
    public $blogPage;

    /**
     * Reference to the page name for linking to posts.
     * @var string
     */
    public $postPage;

    public function componentDetails()
    {
        return [
            'name' => "RSS Feed",
            'description' => "Generates an RSS feed containing posts from the blog.",
        ];
    }

    public function defineProperties()
    {
        return [
            'categoryFilter' => [
                'title' => "Category filter",
                'description' => "Enter a category slug or URL parameter to filter the posts by. Leave empty to show all posts.",
                'type' => 'string',
                'default' => '',
            ],
            'sortOrder' => [
                'title' => "Post order",
                'description' => "Attribute on which the posts should be ordered",
                'type' => 'dropdown',
                'default' => 'created_at desc',
            ],
            'postsPerPage' => [
                'title' => "Posts per page",
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => "Invalid format of the posts per page value",
                'default' => '10',
            ],
            'blogPage' => [
                'title' => "Blog page",
                'description' => "Name of the main blog page file for generating links. This property is used by the default component partial.",
                'type' => 'dropdown',
                'default' => 'blog/post',
                'group' => "Links",
            ],
            'postPage' => [
                'title' => "Post page",
                'description' => "Name of the blog post page file for the \"Learn more\" links. This property is used by the default component partial.",
                'type' => 'dropdown',
                'default' => 'blog/post',
                'group' => "Links",
            ],
        ];
    }

    /**
     * getBlogPageOptions
     */
    public function getBlogPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * getPostPageOptions
     */
    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * getSortOrderOptions
     */
    public function getSortOrderOptions()
    {
        $options = BlogPost::$allowedSortingOptions;

        foreach ($options as $key => $value) {
            $options[$key] = Lang::get($value);
        }

        return $options;
    }

    /**
     * onRun
     */
    public function onRun()
    {
        $this->prepareVars();

        $xmlFeed = $this->renderPartial('@default');

        return Response::make($xmlFeed, '200')->header('Content-Type', 'text/xml');
    }

    /**
     * prepareVars
     */
    protected function prepareVars()
    {
        $this->blogPage = $this->page['blogPage'] = $this->property('blogPage');
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->category = $this->page['category'] = $this->loadCategory();
        $this->posts = $this->page['posts'] = $this->listPosts();

        $this->page['link'] = $this->pageUrl($this->blogPage);
        $this->page['rssLink'] = $this->currentPageUrl();
    }

    /**
     * listPosts
     */
    protected function listPosts()
    {
        $category = $this->category ? $this->category->id : null;

        // List all the posts, eager load their categories
        $posts = BlogPost::with('categories')->listFrontEnd([
            'sort' => $this->property('sortOrder'),
            'perPage' => $this->property('postsPerPage'),
            'category' => $category
        ]);

        // Add a "url" helper attribute for linking to each post and category
        $posts->each(function($post) {
            $post->setUrl($this->postPage, $this->controller);
        });

        return $posts;
    }

    /**
     * loadCategory
     */
    protected function loadCategory()
    {
        if (!$categoryId = $this->property('categoryFilter')) {
            return null;
        }

        if (!$category = BlogCategory::whereSlug($categoryId)->first()) {
            return null;
        }

        return $category;
    }
}

<?php namespace RainLab\Blog\Components;

use App;
use Request;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;
use RainLab\Blog\Models\Category as BlogCategory;

class Posts extends ComponentBase
{
    public $posts;
    public $postPage;
    public $pageParam;
    public $category;
    public $categoryPage;
    public $noPostsMessage;
    public $postPageIdParam;
    public $categoryPageIdParam;

    public function componentDetails()
    {
        return [
            'name'        => 'Blog Post List',
            'description' => 'Displays a list of latest blog posts on the page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'postsPerPage' => [
                'title'             => 'Posts per page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value',
                'default'           => '10',
            ],
            'pageParam' => [
                'title'       => 'Pagination parameter name',
                'description' => 'The expected parameter name used by the pagination pages.',
                'type'        => 'string',
                'default'     => ':page',
            ],
            'categoryFilter' => [
                'title'       => 'Category filter',
                'description' => 'Enter a category slug or URL parameter to filter the posts by. Leave empty to show all posts.',
                'type'        => 'string',
                'default'     => ''
            ],
            'categoryPage' => [
                'title'       => 'Category page',
                'description' => 'Name of the category page file for the "Posted into" category links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'blog/category'
            ],
            'categoryPageIdParam' => [
                'title'       => 'Category page param name',
                'description' => 'The expected parameter name used when creating links to the category page.',
                'type'        => 'string',
                'default'     => ':slug',
            ],
            'postPage' => [
                'title'       => 'Post page',
                'description' => 'Name of the blog post page file for the "Learn more" links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'blog/post'
            ],
            'postPageIdParam' => [
                'title'       => 'Post page param name',
                'description' => 'The expected parameter name used when creating links to the post page.',
                'type'        => 'string',
                'default'     => ':slug',
            ],
            'noPostsMessage' => [
                'title'        => 'No posts message',
                'description'  => 'Message to display in the blog post list in case if there are no posts. This property is used by the default component partial.',
                'type'         => 'string',
                'default'      => 'No posts found'
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->category = $this->page['category'] = $this->loadCategory();
        $this->posts = $this->page['posts'] = $this->listPosts();

        $currentPage = $this->propertyOrParam('pageParam');
        if ($currentPage > ($lastPage = $this->posts->getLastPage()) && $currentPage > 1)
            return Redirect::to($this->controller->currentPageUrl([$this->property('pageParam') => $lastPage]));

        $this->prepareVars();
    }

    protected function prepareVars()
    {
        $this->pageParam = $this->page['pageParam'] = $this->property('pageParam');
        $this->noPostsMessage = $this->page['noPostsMessage'] = $this->property('noPostsMessage');

        /*
         * Page links
         */
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->postPageIdParam = $this->page['postPageIdParam'] = $this->property('postPageIdParam');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->categoryPageIdParam = $this->page['categoryPageIdParam'] = $this->property('categoryPageIdParam');
    }

    protected function listPosts()
    {
        $categories = $this->category ? $this->category->id : null;

        return BlogPost::make()->listFrontEnd([
            'page'       => $this->propertyOrParam('pageParam'),
            'sort'       => ['published_at', 'updated_at'],
            'perPage'    => $this->property('postsPerPage'),
            'categories' => $categories
        ]);
    }

    public function loadCategory()
    {
        if (!$categoryId = $this->propertyOrParam('categoryFilter'))
            return null;

        if (!$category = BlogCategory::whereSlug($categoryId)->first())
            return null;

        return $category;
    }
}
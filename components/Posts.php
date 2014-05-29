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
    public $categoryPage;
    public $postPage;
    public $noPostsMessage;

    private $categoryFilter;

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
                'default'           => '10',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value'
            ],
            'categoryFilter' => [
                'title'       => 'Category filter',
                'description' => 'Name of the category to filter by.',
                'type'        => 'dropdown',
                'default'     => ''
            ],
            'categoryPage' => [
                'title'       => 'Category page',
                'description' => 'Name of the category page file for the "Posted into" category links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'blog/category'
            ],
            'postPage' => [
                'title'       => 'Post page',
                'description' => 'Name of the blog post page file for the "Learn more" links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'blog/post'
            ],
            'noPostsMessage' => [
                'title'        => 'No posts message',
                'description'  => 'Message to display in the blog post list in case if there are no posts. This property is used by the default component partial.',
                'type'         => 'string',
                'default'      => 'No posts found'
            ]
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

    public function getCategoryFilterOptions()
    {
        return [''=>'- none -'] + BlogCategory::orderBy('name')->lists('name', 'id');
    }

    public function onRun()
    {
        $this->posts = $this->page['posts'] = $this->loadPosts();

        $currentPage = $this->param('page');
        if ($currentPage > ($lastPage = $this->posts->getLastPage()) && $currentPage > 1)
            return Redirect::to($this->controller->currentPageUrl(['page'=>$lastPage]));

        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->noPostsMessage = $this->page['noPostsMessage'] = $this->property('noPostsMessage');
    }

    protected function loadPosts()
    {
        $categories = ($category = $this->getCategoryFilter()) ? $category->id : null;

        return BlogPost::make()->listFrontEnd([
            'page'       => $this->param('page'),
            'sort'       => ['published_at', 'updated_at'],
            'perPage'    => $this->property('postsPerPage'),
            'categories' => $categories
        ]);
    }

    public function getCategoryFilter()
    {
        if ($this->categoryFilter !== null)
            return $this->categoryFilter;

        if (!$categoryId = $this->property('categoryFilter'))
            return null;

        if (!$category = BlogCategory::find($categoryId))
            return null;

        return $this->categoryFilter = $category;
    }
}
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
    public $categoryFilter;
    public $categoryPage;
    public $postPage;
    public $noPostsMessage;

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
                'title' => 'Category filter',
                'description' => 'Name of the category to filter by.',
                'type' => 'dropdown',
                'default' => null
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
        return BlogPost::make()->listFrontEnd([
            'page' => $this->param('page'),
            'sort' => ['published_at', 'updated_at'],
            'perPage' => $this->property('postsPerPage'),
            'categories' => $this->categoryFilter
        ]);
    }
}
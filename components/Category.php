<?php namespace RainLab\Blog\Components;

use App;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Category as BlogCategory;
use RainLab\Blog\Models\Post as BlogPost;

class Category extends ComponentBase
{
    public $category;
    public $postPage;
    public $posts;

    public function componentDetails()
    {
        return [
            'name'        => 'Blog Category',
            'description' => 'Displays posts from a specific category.'
        ];
    }

    public function defineProperties()
    {
        return [
            'paramId' => [
                'title'       => 'Slug param name',
                'description' => 'The URL route parameter used for looking up the category by its slug.',
                'default'     => 'slug',
                'type'        => 'string'
            ],
            'postPage' => [
                'title'       => 'Post page',
                'description' => 'Name of the blog post page file for the "Learn more" links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'blog/post'
            ],
            'postsPerPage' => [
                'title'             => 'Posts per',
                'default'           => '10',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value'
            ],
            'noPostsMessage' => [
                'title'       => 'No posts message',
                'description' => 'Message to display in the blog post list in case if there are no posts. This property is used by the default component partial.',
                'type'        => 'string',
                'default'     => 'No posts found'
            ]
        ];
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->category = $this->page['category'] = $this->loadCategory();
        $this->postPage = $this->page['postPage'] = $this->property('postPage');

        if ($this->category) {
            $this->posts = $this->page['posts'] = $this->loadPosts();

            $currentPage = $this->param('page');
            if ($currentPage > ($lastPage = $this->posts->getLastPage()) && $currentPage > 1)
                return Redirect::to($this->controller->currentPageUrl(['page'=>$lastPage]));
        }
    }

    protected function loadCategory()
    {
        $slug = $this->param($this->property('paramId'));
        return BlogCategory::where('slug', '=', $slug)->first();
    }

    protected function loadPosts()
    {
        return BlogPost::make()->listFrontEnd([
            'page' => $this->param('page'),
            'sort' => ['published_at', 'updated_at'],
            'perPage' => $this->property('postsPerPage'),
            'categories' => $this->category->id
        ]);
    }
}
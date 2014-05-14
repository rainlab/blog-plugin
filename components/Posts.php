<?php namespace RainLab\Blog\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\CmsPropertyHelper;
use RainLab\Blog\Models\Post as BlogPost;
use Request;
use Redirect;
use App;

class Posts extends ComponentBase
{
    public $posts;
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
                'title' => 'Posts per page',
                'default' => '10',
                'type'=>'string',
                'validationPattern'=>'^[0-9]+$',
                'validationMessage'=>'Invalid format of the posts per page value'
            ],
            'categoryPage' => [
                'title' => 'Category page',
                'description' => 'Name of the category page file for the "Posted into" category links. This property is used by the default component partial.',
                'type'=>'dropdown',
                'default' => 'blog/category'
            ],
            'postPage' => [
                'title' => 'Post page',
                'description' => 'Name of the blog post page file for the "Learn more" links. This property is used by the default component partial.',
                'type'=>'dropdown',
                'default' => 'blog/post'
            ],
            'noPostsMessage' => [
                'title' => 'No posts message',
                'description' => 'Message to display in the blog post list in case if there are no posts. This property is used by the default component partial.',
                'type'=>'string',
                'default' => 'No posts found'
            ]
        ];
    }

    public function getCategoryPageOptions()
    {
        return CmsPropertyHelper::listPages();
    }

    public function getPostPageOptions()
    {
        return CmsPropertyHelper::listPages();
    }

    public function onRun()
    {
        $this->posts = $this->page['blogPosts'] = $this->loadPosts();

        $currentPage = $this->param('page');
        if ($currentPage > ($lastPage = $this->posts->getLastPage()) && $currentPage > 1)
            return Redirect::to($this->controller->currentPageUrl(['page'=>$lastPage]));

        $this->categoryPage = $this->page['blogCategoryPage'] = $this->property('categoryPage');
        $this->postPage = $this->page['blogPostPage'] = $this->property('postPage');
        $this->noPostsMessage = $this->page['blogNoPostsMessage'] = $this->property('noPostsMessage');
    }

    protected function loadPosts()
    {
        $currentPage = $this->param('page');
        App::make('paginator')->setCurrentPage($currentPage);

        return BlogPost::isPublished()->orderBy('published_at', 'desc')->paginate($this->property('postsPerPage'));
    }
}
<?php namespace RainLab\Blog\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post;

class Posts extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Blog Post List',
            'description' => 'Displays a list of latest blog posts on the page.'
        ];
    }

    public function defineProperties()
    {
        
    }

    public function onRun()
    {
        $this->page['blogPosts'] = $this->loadPosts();
    }

    protected function loadPosts()
    {
        $currentPage = Request::input('page');
        $posts = Post::isPublished()->orderBy('created_at', 'desc');

        return $posts->paginate(20);
    }
}
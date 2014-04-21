<?php namespace RainLab\Blog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use RainLab\Blog\Models\Post;

class Posts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.Blog', 'blog', 'posts');
        $this->addCss('/plugins/rainlab/blog/assets/css/rainlab.blog-preview.css');
        $this->addCss('/plugins/rainlab/blog/assets/css/rainlab.blog-preview-theme-default.css');

        $this->addCss('/plugins/rainlab/blog/assets/vendor/prettify/prettify.css');
        $this->addCss('/plugins/rainlab/blog/assets/vendor/prettify/theme-desert.css');

        $this->addJs('/plugins/rainlab/blog/assets/js/post-form.js');
        $this->addJs('/plugins/rainlab/blog/assets/vendor/prettify/prettify.js');
    }

    public function onRefreshPreview()
    {
        $data = post('Post');

        $previewHtml = Post::formatHtml($data['content'], true);

        return [
            'preview' => $previewHtml
        ];
    }
}
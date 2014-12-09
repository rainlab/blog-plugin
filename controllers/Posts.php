<?php namespace RainLab\Blog\Controllers;

use Backend\Facades\Backend;
use Backend\Facades\BackendAuth;
use Flash;
use BackendMenu;
use Backend\Classes\Controller;
use RainLab\Blog\Models\Post;
use System\Classes\ApplicationException;

class Posts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $bodyClass = 'compact-container';

    public $requiredPermissions = ['rainlab.blog.access_other_posts', 'rainlab.blog.access_posts'];

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

    public function index()
    {
        $this->vars['postsTotal'] = Post::count();
        $this->vars['postsPublished'] = Post::isPublished()->count();
        $this->vars['postsDrafts'] = $this->vars['postsTotal'] - $this->vars['postsPublished'];

        $this->asExtension('ListController')->index();
    }

    public function update($recordId, $context = null)
    {
        $user = BackendAuth::getUser();
        $post = Post::find($recordId);

        if (!$post->userCanEdit($user)) {
            return \Redirect::to(Backend::url('rainlab/blog/posts'));
        }

        return $this->asExtension('FormController')->update($recordId, $context);

    }

    public function listExtendQueryBefore($query)
    {
        $user = BackendAuth::getUser();

        if( !$user->hasAnyAccess(['rainlab.blog.access_other_posts']) ) {
            $query->where('user_id', '=', $user->id);
        }
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            $user = BackendAuth::getUser();

            foreach ($checkedIds as $postId) {
                if (!$post = Post::find($postId))
                    continue;

                if($post->userCanEdit($user)) {
                    $post->delete();
                }
            }

            Flash::success('Successfully deleted those posts.');
        }

        return $this->listRefresh();
    }

    public function update_onDelete($postId)
    {
        $user = BackendAuth::getUser();
        $post = Post::find($postId);

        if($post->userCanEdit($user)) {
            return $this->asExtension('FormController')->update_onDelete($postId);
        }

        return \Redirect::to(Backend::url('rainlab/blog/posts'));
    }

    public function update_onSave($postId)
    {
        $user = BackendAuth::getUser();
        $post = Post::find($postId);

        if($post->userCanEdit($user)) {
            return $this->asExtension('FormController')->update_onSave($postId);
        }

        return \Redirect::to(Backend::url('rainlab/blog/posts'));
    }

    /**
     * {@inheritDoc}
     */
    public function listInjectRowClass($record, $definition = null)
    {
        if (!$record->published)
            return 'safe disabled';
    }

    public function formBeforeCreate($model)
    {
        $model->user_id = BackendAuth::getUser()->id;
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
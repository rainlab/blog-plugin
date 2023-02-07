<?php namespace RainLab\Blog\Controllers;

use Lang;
use Flash;
use BackendMenu;
use RainLab\Blog\Models\Post;
use RainLab\Blog\Models\Settings as BlogSettings;
use Backend\Classes\Controller;
use Cms\Classes\Controller as CmsController;
use Cms\Classes\Theme;

/**
 * Posts
 */
class Posts extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\ImportExportController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    /**
     * @var array requiredPermissions
     */
    public $requiredPermissions = ['rainlab.blog.access_other_posts', 'rainlab.blog.access_posts'];

    /**
     * @var bool turboVisitControl
     */
    public $turboVisitControl = 'disable';

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.Blog', 'blog', 'posts');
    }

    public function index()
    {
        $this->vars['postsTotal'] = Post::count();
        $this->vars['postsPublished'] = Post::isPublished()->count();
        $this->vars['postsDrafts'] = $this->vars['postsTotal'] - $this->vars['postsPublished'];

        $this->asExtension('ListController')->index();
    }

    public function create()
    {
        BackendMenu::setContextSideMenu('new_post');

        $this->bodyClass = 'compact-container';
        $this->addCss('/plugins/rainlab/blog/assets/css/rainlab.blog-preview.css');
        $this->addJs('/plugins/rainlab/blog/assets/js/post-form.js');

        return $this->asExtension('FormController')->create();
    }

    public function update($recordId = null)
    {
        $this->bodyClass = 'compact-container';
        $this->addCss('/plugins/rainlab/blog/assets/css/rainlab.blog-preview.css');
        $this->addJs('/plugins/rainlab/blog/assets/js/post-form.js');

        $result = $this->asExtension('FormController')->update($recordId);
        $this->setPreviewPageUrlVars();
        return $result;
    }

    /**
     * setPreviewPageUrlVars
     */
    protected function setPreviewPageUrlVars()
    {
        if (
            ($model = $this->formGetModel()) &&
            ($cmsPage = BlogSettings::get('preview_cms_page'))
        ) {
            $controller = new CmsController(Theme::getActiveTheme());
            $model->setUrl($cmsPage, $controller);
            $this->vars['pageUrl'] = $model->url;
        }
    }

    public function export()
    {
        $this->addCss('/plugins/rainlab/blog/assets/css/rainlab.blog-export.css');

        return $this->asExtension('ImportExportController')->export();
    }

    public function listExtendQuery($query)
    {
        if (!$this->user->hasAnyAccess(['rainlab.blog.access_other_posts'])) {
            $query->where('user_id', $this->user->id);
        }
    }

    public function formExtendQuery($query)
    {
        if (!$this->user->hasAnyAccess(['rainlab.blog.access_other_posts'])) {
            $query->where('user_id', $this->user->id);
        }
    }

    public function formExtendFieldsBefore($widget)
    {
        if (!$model = $widget->model) {
            return;
        }

        // Support for October CMS 3.0 and below
        if (!class_exists('Site') && $model instanceof Post && $model->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')) {
            $widget->secondaryTabs['fields']['content']['type'] = 'RainLab\Blog\FormWidgets\MLBlogMarkdown';
        }

        if (BlogSettings::get('use_legacy_editor', false)) {
            $widget->secondaryTabs['fields']['content']['legacyMode'] = true;
        }

        // Force richeditor by settings
        if ($model instanceof Post && BlogSettings::get('force_richeditor_editor', false)) {
            $widget->secondaryTabs['fields']['content']['type'] = 'richeditor';
        }
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $postId) {
                if ((!$post = Post::find($postId)) || !$post->canEdit($this->user)) {
                    continue;
                }

                $post->delete();
            }

            Flash::success(Lang::get('rainlab.blog::lang.post.delete_success'));
        }

        return $this->listRefresh();
    }

    /**
     * {@inheritDoc}
     */
    public function listInjectRowClass($record, $definition = null)
    {
        if (!$record->published) {
            return 'safe disabled';
        }
    }

    public function formBeforeCreate($model)
    {
        $model->user_id = $this->user->id;
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

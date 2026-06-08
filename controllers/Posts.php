<?php namespace RainLab\Blog\Controllers;

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
    /**
     * @var array implement
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\ImportExportController::class
    ];

    /**
     * @var string formConfig
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var string importExportConfig
     */
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

    /**
     * index
     */
    public function index()
    {
        $this->vars['postsTotal'] = Post::count();
        $this->vars['postsPublished'] = Post::isPublished()->count();
        $this->vars['postsDrafts'] = $this->vars['postsTotal'] - $this->vars['postsPublished'];

        $this->asExtension('ListController')->index();
    }

    /**
     * create
     */
    public function create()
    {
        BackendMenu::setContextSideMenu('new_post');

        return $this->asExtension('FormController')->create();
    }

    /**
     * update
     */
    public function update($recordId = null)
    {
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

    /**
     * export
     */
    public function export()
    {
        $this->addCss('/plugins/rainlab/blog/assets/css/rainlab.blog-export.css');

        return $this->asExtension('ImportExportController')->export();
    }

    /**
     * listExtendQuery
     */
    public function listExtendQuery($query)
    {
        if (!$this->user->hasAnyAccess(['rainlab.blog.access_other_posts'])) {
            $query->where('user_id', $this->user->id);
        }
    }

    /**
     * formExtendQuery
     */
    public function formExtendQuery($query)
    {
        if (!$this->user->hasAnyAccess(['rainlab.blog.access_other_posts'])) {
            $query->where('user_id', $this->user->id);
        }
    }

    /**
     * formExtendFieldsBefore
     */
    public function formExtendFieldsBefore($widget)
    {
        if (!$model = $widget->model) {
            return;
        }

        if ($model instanceof Post && BlogSettings::get('force_richeditor_editor', false)) {
            $widget->secondaryTabs['fields']['content']['type'] = 'richeditor';
        }
    }

    /**
     * index_onDelete
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $postId) {
                if ((!$post = Post::find($postId)) || !$post->canEdit($this->user)) {
                    continue;
                }

                $post->delete();
            }

            Flash::success(__("Successfully deleted those posts."));
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

    /**
     * formBeforeCreate
     */
    public function formBeforeCreate($model)
    {
        $model->user_id = $this->user->id;
    }
}

<?php namespace RainLab\Blog\Controllers;

use Flash;
use BackendMenu;
use Backend\Classes\Controller;
use RainLab\Blog\Models\Category;

/**
 * Categories
 */
class Categories extends Controller
{
    /**
     * @var array implement
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
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
     * @var string requiredPermissions
     */
    public $requiredPermissions = ['rainlab.blog.access_categories'];

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.Blog', 'blog', 'categories');
    }

    /**
     * index_onDelete
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $categoryId) {
                if ((!$category = Category::find($categoryId))) {
                    continue;
                }

                $category->delete();
            }

            Flash::success(__("Successfully deleted those categories."));
        }

        return $this->listRefresh();
    }
}

<?php namespace Plugins\October\Blog\Controllers;

use BackendMenu;
use Modules\Backend\Classes\BackendController;

class Categories extends BackendController
{
    public $implement = [
        'Modules.Backend.Behaviors.FormController',
        'Modules.Backend.Behaviors.ListController'
    ];

    public $formConfig = 'form_config.yaml';
    public $listConfig = 'list_config.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.Blog', 'blog');
    }
}
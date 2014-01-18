<?php namespace RainLab\Blog\Controllers;

use BackendMenu;
use Backend\Classes\BackendController;

class Categories extends BackendController
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.Blog', 'blog', 'categories');
    }
}
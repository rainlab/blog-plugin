<?php
namespace RainLab\Blog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use RainLab\Blog\Models\Setting;
use Flash;

class Settings extends Controller {
    public $implement = ['Backend.Behaviors.FormController'];

    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = ['rainlab.blog.access_settings'];

    public function __construct() {
        parent::__construct();
        $this -> pageTitle = 'rainlab.blog::lang.setting.title';
        BackendMenu::setContext('RainLab.Blog', 'blog', 'settings');
    }

    public function index() {
        $this -> initForm($this);
        $formWidget = $this -> widget -> form;
        $formWidget -> addFields(Setting::getFields());
        $this -> addJs('/plugins/rainlab/blog/assets/js/settings-form.js');
    }

    public function index_onSave($context = null) {
        try {
            $fields = \Request::input();
            $model = Setting::get();
            $map = [];

            foreach ($model as $key => $field) {
                $map[$field['name']] = $field['id'];
            }

            foreach ($fields['Settings'] as $key => $field) {
                $setting = Setting::find($map[$key]);
                $setting -> class = $field;
                $setting -> save();
            }
                
            Flash::success("Settings Saved.");
        } catch(Exception $e) {
            Flash::error("Something went wrong.");
        }

        return \Backend::url('rainlab/blog/categories');
    }

}

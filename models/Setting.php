<?php
namespace RainLab\Blog\Models;

use Str;
use Model;
use URL;
use October\Rain\Router\Helper as RouterHelper;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;

class Setting extends Model {

    public $table = 'rainlab_blog_settings';
    protected $fillable = [];
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
    
    static public function getFields(){
        $model = self::get();
        $fields = [];
        foreach($model as $key => $field){
            $fields[$field -> name] = [
                'span' => 'left',
                'label' => $field -> label,
                'placeholder' => $field -> placeholder,
                'attributes' => [
                    'data-target' => $field -> target,
                    'data-id' => $field -> id
                ],
                'default' => $field -> class,
                'cssClass' => 'setting-field'
            ];
        }
        return $fields;
    }

}

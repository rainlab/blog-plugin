<?php namespace Plugins\October\Blog\Models;

use Model;

class Post extends Model
{
    public $table = 'october_blog_posts';

    /*
     * Validation
     */
    public $rules = [
        'title' => 'required',
        'excerpt' => 'required',
    ];

    protected $guarded = [];

    /*
     * Relations
     */
    public $belongsTo = [
        'user' => ['Modules\Backend\Models\User', 'foreignKey' => 'user_id']
    ];

    public $belongsToMany = [
        'categories' => ['Plugins\October\Blog\Models\Category', 'table' => 'october_blog_posts_categories']
    ];

    public $morphMany = [
        'featured_images' => ['Modules\System\Models\File', 'name' => 'attachment']
    ];

}
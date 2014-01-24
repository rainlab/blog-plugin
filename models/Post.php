<?php namespace RainLab\Blog\Models;

use Model;

class Post extends Model
{
    public $table = 'rainlab_blog_posts';

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
        'user' => ['Backend\Models\User', 'foreignKey' => 'user_id']
    ];

    public $belongsToMany = [
        'categories' => ['RainLab\Blog\Models\Category', 'table' => 'rainlab_blog_posts_categories']
    ];

    public $attachMany = [
        'featured_images' => ['System\Models\File']
    ];

}
<?php namespace RainLab\Blog\Models;

use Model;

class Category extends Model
{
    public $table = 'rainlab_blog_categories';

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required',
    ];

    protected $guarded = [];

}
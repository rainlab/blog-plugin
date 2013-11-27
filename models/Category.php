<?php namespace Plugins\October\Blog\Models;

use Model;

class Category extends Model
{
    public $table = 'october_blog_categories';

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required',
    ];

    protected $guarded = [];

}
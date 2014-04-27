<?php namespace RainLab\Blog\Models;

use Str;
use Model;
use RainLab\Blog\Models\Post;

class Category extends Model
{
    public $table = 'rainlab_blog_categories';

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|between:3,64|unique:rainlab_blog_categories',
        'code' => 'unique:rainlab_blog_categories',
    ];

    protected $guarded = [];

    public function beforeValidate()
    {
        // Generate a URL slug for this model
        if (!$this->exists && !$this->slug)
            $this->slug = Str::slug($this->name);
    }

    public function posts()
    {
        // @todo: declare this relationship as the class field when the conditions option is implemented
        return $this->belongsToMany('RainLab\Blog\Models\Post', 'rainlab_blog_posts_categories')->isPublished()->orderBy('published_at', 'desc');
    }

    public function postCount()
    {
        return $this->posts()->count();
    }
}
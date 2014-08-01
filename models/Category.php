<?php namespace RainLab\Blog\Models;

use Str;
use Model;
use RainLab\Blog\Models\Post;

class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;

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

    public $belongsToMany = [
        'posts' => ['RainLab\Blog\Models\Post', 'table' => 'rainlab_blog_posts_categories', 'order' => 'published_at desc', 'scope' => 'isPublished']
    ];

    public function beforeValidate()
    {
        // Generate a URL slug for this model
        if (!$this->exists && !$this->slug)
            $this->slug = Str::slug($this->name);
    }

    public function postCount()
    {
        return $this->posts()->count();
    }
}
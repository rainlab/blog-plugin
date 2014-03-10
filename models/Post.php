<?php namespace RainLab\Blog\Models;

use Str;
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
        'user' => ['Backend\Models\User']
    ];

    public $belongsToMany = [
        'categories' => ['RainLab\Blog\Models\Category', 'table' => 'rainlab_blog_posts_categories', 'order' => 'name']
    ];

    public $attachMany = [
        'featured_images' => ['System\Models\File']
    ];

    //
    // Events
    //

    public function beforeValidate()
    {
        // Generate a URL slug for this model
        if (!$this->exists && !$this->slug)
            $this->slug = Str::slug($this->name);
    }

}
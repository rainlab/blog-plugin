<?php namespace RainLab\Blog\Models;

use Backend\Models\ExportModel;
use ApplicationException;

/**
 * Post Export Model
 */
class PostExport extends ExportModel
{
    public $table = 'rainlab_blog_posts';

    public $belongsToMany = [
        'post_categories' => [
            'RainLab\Blog\Models\Category',
            'table' => 'rainlab_blog_posts_categories',
            'order' => 'name',
            'key' => 'post_id',
            'otherKey' => 'category_id'
        ]
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = ['categories'];

    public function exportData($columns, $sessionKey = null)
    {
        $result = self::with('post_categories')->get()->toArray();

        return $result;
    }

    public function getCategoriesAttribute()
    {
        $categories = $this->post_categories->lists('name');
        return $this->encodeArrayValue($categories);
    }
}
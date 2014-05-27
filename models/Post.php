<?php namespace RainLab\Blog\Models;

use App;
use Str;
use Model;
use October\Rain\Support\Markdown;
use October\Rain\Support\ValidationException;
use RainLab\Blog\Classes\TagProcessor;

class Post extends Model
{
    public $table = 'rainlab_blog_posts';

    /*
     * Validation
     */
    public $rules = [
        'title' => 'required',
        'slug' => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i'],
        'content' => 'required',
        'excerpt' => ''
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['published_at'];

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
        'featured_images' => ['System\Models\File'],
        'content_images' => ['System\Models\File']
    ];

    public $preview = null;

    /**
     * Lists posts for the front end
     * @param  array $options Display options
     * @return self
     */
    public function listFrontEnd($options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page' => 1,
            'perPage' => 30,
            'sort' => 'created_at',
            'categories' => null,
            'search' => '',
            'published' => true
        ], $options));
        
        $allowedSortingOptions = ['created_at', 'updated_at', 'published_at'];
        $searchableFields = ['title', 'slug', 'excerpt', 'content'];

        App::make('paginator')->setCurrentPage($page);
        $obj = $this->newQuery();

        if ($published)
            $obj->isPublished();

        /*
         * Sorting
         */
        if (!is_array($sort)) $sort = [$sort];
        foreach ($sort as $_sort) {

            $parts = explode(' ', $_sort);
            if (count($parts) < 2) array_push($parts, 'desc');
            list($sortField, $sortDirection) = $parts;

            if (in_array($sortField, $allowedSortingOptions))
                $obj->orderBy($_sort, 'desc');
        }

        /*
         * Search
         */
        $search = trim($search);
        if (strlen($search)) {
            $obj->searchWhere($search, $searchableFields);
        }

        /*
         * Categories
         */
        if ($categories !== null) {
            if (!is_array($categories)) $categories = [$categories];
            $obj->whereHas('categories', function($q) use ($categories) {
                $q->whereIn('id', $categories);
            });
        }

        return $obj->paginate($perPage);
    }

    public static function formatHtml($input, $preview = false)
    {
        $result = Markdown::parse(trim($input));

        if ($preview)
            $result = str_replace('<pre>', '<pre class="prettyprint">', $result);

        $result = TagProcessor::instance()->processTags($result, $preview);

        return $result;
    }

    public function afterValidate()
    {
        if ($this->published && !$this->published_at)
            throw new ValidationException([
               'published_at' => 'Please specify the published date'
            ]);
    }

    public function scopeIsPublished($query)
    {
        return $query
            ->whereNotNull('published')
            ->where('published', '=', 1)
        ;
    }

    public function beforeSave()
    {
        $this->content_html = self::formatHtml($this->content);
    }
}
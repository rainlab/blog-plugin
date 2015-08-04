<?php namespace RainLab\Blog\Models;

use Backend\Models\ImportModel;
use ApplicationException;

/**
 * Post Import Model
 */
class PostImport extends ImportModel
{
    public $table = 'rainlab_blog_posts';

    /**
     * Validation rules
     */
    public $rules = [
        'title' => 'required',
        'content' => 'required',
    ];

    protected $categoryNameCache = [];

    public function getPostCategoriesOptions()
    {
        return Category::lists('name', 'id');
    }

    public function importData($results, $sessionKey = null)
    {
        $firstRow = reset($results);

        /*
         * Validation
         */
        if ($this->auto_create_categories && !array_key_exists('categories', $firstRow)) {
            throw new ApplicationException('Please specify a match for the Categories column.');
        }

        /*
         * Import
         */
        foreach ($results as $row => $data) {
            try {

                if (!$slug = array_get($data, 'slug')) {
                    $this->logSkipped($row, 'Missing slug address');
                    continue;
                }

                $post = Post::firstOrNew(['slug' => $slug]);
                $postExists = $post->exists;

                foreach ($data as $attribute => $value) {
                    $post->{$attribute} = $value;
                }

                $post->forceSave();

                if ($categoryIds = $this->getCategoryIdsForPost($data)) {
                    $post->categories()->sync($categoryIds, false);
                }

                if ($postExists) {
                    $this->logUpdated();
                }
                else {
                    $this->logCreated();
                }
            }
            catch (Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }

    }

    protected function getCategoryIdsForPost($data)
    {
        $ids = [];

        if ($this->auto_create_categories) {
            $categoryNames = $this->decodeArrayValue(array_get($data, 'categories'));

            foreach ($categoryNames as $name) {
                if (!$name = trim($name)) continue;

                if (isset($this->categoryNameCache[$name])) {
                    $ids[] = $this->categoryNameCache[$name];
                }
                else {
                    $newCategory = Category::firstOrCreate(['name' => $name]);
                    $ids[] = $this->categoryNameCache[$name] = $newCategory->id;
                }
            }
        }
        elseif ($this->categories) {
            $ids = (array) $this->categories;
        }

        return $ids;
    }

}
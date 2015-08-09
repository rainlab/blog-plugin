<?php namespace RainLab\Blog\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;

/**
 * Search Form Component
 */
class SearchForm extends ComponentBase
{
    /**
     * @var string Reference to the search results page.
     */
    public $resultPage;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'rainlab.blog::lang.settings.search_form',
            'description' => 'rainlab.blog::lang.settings.search_form_description'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'resultPage' => [
                'title'       => 'rainlab.blog::lang.settings.search_results_page',
                'description' => 'rainlab.blog::lang.settings.search_results_page_description',
                'type'        => 'dropdown',
                'default'     => 'blog/search'
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function getResultPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Prepare vars
     */
    public function onRun()
    {
        $this->resultPage = $this->page[ 'resultPage' ] = $this->property('resultPage');
    }
}

<?php namespace RainLab\Blog\Components;

use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;

class ArchiveList extends ComponentBase
{
    /**
     * A collection of the past x months
     * @var Collection
     */
    public $archiveMonths;

    /**
     * @var string Reference to the page name for linking to archive.
     */
    public $archivePage;

    /**
     * @var string Reference to the current archive slug.
     */
    public $currentArchiveSlug;

    public function componentDetails()
    {
        return [
            'name'        => 'rainlab.blog::lang.settings.archive_list_name',
            'description' => 'rainlab.blog::lang.settings.archive_list_description'
        ];
    }

    public function defineProperties()
    {
        return [
            'monthsToShow' => [
                'title'             => 'rainlab.blog::lang.settings.archive_list_months_to_show',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.archive_list_months_to_show_validation',
                'default'           => '5',
            ],
            'slug' => [
                'title'             => 'rainlab.blog::lang.settings.archive_list_slug',
                'description'       => 'rainlab.blog::lang.settings.archive_list_slug_description',
                'default'           => '{{ :slug }}',
                'type'              => 'string'
            ],
            'archivePage' => [
                'title'             => 'rainlab.blog::lang.settings.archive_list_page',
                'description'       => 'rainlab.blog::lang.settings.archive_list_page_description',
                'type'              => 'dropdown',
                'default'           => 'blog/archive',
                'group'             => 'Links',
            ],
        ];
    }

    public function getArchivePageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->archiveMonths = $this->page['archiveMonths'] = $this->loadMonths($this->property('monthsToShow'));
        $this->currentArchiveSlug = $this->page['currentArchiveSlug'] = $this->property('slug');
        $this->archivePage = $this->page['archivePage'] = $this->property('archivePage');
    }

    protected function loadMonths($monthsToShow)
    {
        /*
         * Make a collection of the past x months
         */
        $archiveMonths = collect([]);
        
        for ($m=1; $m<=$monthsToShow; $m++)
        {
            $monthTime = strtotime('-'.$m.' months');
            $archiveMonths->push(collect([
                'month' => date('F Y', $monthTime),
                'url'   => $this->controller->pageUrl($this->property('archivePage'), ['slug' => urlencode(date('F Y', $monthTime))])
            ]));
        }

        return $archiveMonths;
    }
}

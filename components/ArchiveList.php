<?php namespace RainLab\Blog\Components;

use DB;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;
use Carbon\Carbon;

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
        $this->archivePage = $this->page['archivePage'] = $this->property('archivePage');
    }

    protected function loadMonths($monthsToShow)
    {
        $latestPost = BlogPost::orderBy('published_at', 'DESC')->first();
        $currentMonth = Carbon::parse($latestPost->published_at);
        $archiveRange = [];
        
        for ($i=0; $i < $monthsToShow; $i++) {
            $currentMonthStart = $currentMonth->copy()->startOfMonth()->toDateTimeString();
            $currentMonthEnd = $currentMonth->copy()->endOfMonth()->toDateTimeString();
            $currentMonthCount = BlogPost::whereBetween('published_at', [$currentMonthStart, $currentMonthEnd])->count();

            if ($currentMonthCount > 0) {
                $archiveRange[] = [
                    'month' => $currentMonth->format('F Y'),
                    'count' => $currentMonthCount,
                    'url' => $this->controller->pageUrl($this->property('archivePage'), ['month' => urlencode($currentMonth->format('F Y'))])
                ];
            }
            else {
                $i--;
            }

            $currentMonth->subMonth();
        }

        return $archiveRange;
    }
}

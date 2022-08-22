<?php namespace RainLab\Blog\Models;

use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;
use October\Rain\Database\Model;

class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'rainlab_blog_settings';

    public $settingsFields = 'fields.yaml';

    public $rules = [
        'show_all_posts' => ['boolean'],
    ];

    /**
     * Get Preview CMS Page dropdown options
     *
     * @return array
     */
    public function getPreviewCmsPageOptions()
    {
        $theme = Theme::getActiveTheme();

        $pages = CmsPage::listInTheme($theme, true);
        $result = [];

        foreach ($pages as $page) {
            if (!$page->hasComponent('blogPost')) {
                continue;
            }

            /*
             * Component must use a categoryPage filter with a routing parameter and post slug
             * eg: categoryPage = "{{ :somevalue }}", slug = "{{ :somevalue }}"
             */
            $properties = $page->getComponentProperties('blogPost');
            if (!isset($properties['categoryPage']) || !preg_match('/{{\s*:/', $properties['slug'])) {
                continue;
            }

            $baseName = $page->getBaseFileName();
            $pos = strrpos($baseName, '/');
            $dir = $pos !== false ? substr($baseName, 0, $pos).' / ' : null;

            $result[$baseName] = strlen($page->title) ? $dir.$page->title : $baseName;
        }

        return $result;
    }
}

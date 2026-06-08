<?php namespace RainLab\Blog\Models;

use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;
use System\Models\SettingModel;

/**
 * Setting configuration
 *
 * @property bool show_all_posts
 * @property string preview_cms_page
 * @property bool force_richeditor_editor
 *
 * @package rainlab\blog
 * @author Alexey Bobkov, Samuel Georges
 */
class Setting extends SettingModel
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string settingsCode is a unique code for this object
     */
    public $settingsCode = 'rainlab_blog_settings';

    /**
     * @var mixed settingsFields definition file
     */
    public $settingsFields = 'fields.yaml';

    /**
     * @var array rules for validation
     */
    public $rules = [
        'show_all_posts' => ['boolean'],
    ];

    /**
     * initSettingsData
     */
    public function initSettingsData()
    {
        $this->show_all_posts = true;
        $this->force_richeditor_editor = false;
    }

    /**
     * getPreviewCmsPageOptions returns the dropdown options for the preview CMS page setting
     */
    public function getPreviewCmsPageOptions(): array
    {
        $theme = Theme::getActiveTheme();

        $pages = CmsPage::listInTheme($theme, true);
        $result = [];

        foreach ($pages as $page) {
            if (!$page->hasComponent('blogPost')) {
                continue;
            }

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

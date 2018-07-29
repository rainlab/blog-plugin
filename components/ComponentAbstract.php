<?php

namespace RainLab\Blog\Components;

use Cms\Classes\Page;
use Cms\Classes\Theme;
use Cms\Classes\ComponentBase;

abstract class ComponentAbstract extends ComponentBase
{
    /**
     * Reference to the page name for linking to posts
     *
     * @var string
     */
    public $postPage;

    /**
     * Reference to the page name for linking to categories
     *
     * @var string
     */
    public $categoryPage;

    /**
     * @param string $componentName
     * @param string $page
     * @return ComponentBase|null
     */
    protected function getComponent(string $componentName, string $page)
    {
        $component = Page::load(Theme::getActiveTheme(), $page)->getComponent($componentName);

        if (!is_null($component) && is_callable([$this->controller, 'setComponentPropertiesFromParams'])) {
            $this->controller->setComponentPropertiesFromParams($component);
        }

        return $component;
    }

    /**
     * A helper function to return property value
     *
     * @param ComponentBase|null $component
     * @param string $name
     *
     * @return string|null
     */
    protected function urlProperty(ComponentBase $component = null, string $name)
    {
        return $component ? $component->propertyName($name, $name) : null;
    }
}

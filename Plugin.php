<?php namespace RainLab\Blog;

use Event;
use Backend;
use System\Classes\PluginBase;
use RainLab\Blog\Models\Post;
use RainLab\Blog\Models\Category;

/**
 * Plugin registration for Blog
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails
     */
    public function pluginDetails()
    {
        return [
            'name' => "Blog",
            'description' => "A robust blogging platform.",
            'author' => 'Alexey Bobkov, Samuel Georges',
            'icon' => 'icon-pencil',
            'homepage' => 'https://github.com/rainlab/blog-plugin'
        ];
    }

    /**
     * boot the module events.
     */
    public function boot()
    {
        $this->bootPageManagerLookups();
    }

    /**
     * registerComponents
     */
    public function registerComponents()
    {
        return [
            \RainLab\Blog\Components\Post::class => 'blogPost',
            \RainLab\Blog\Components\Posts::class => 'blogPosts',
            \RainLab\Blog\Components\Categories::class => 'blogCategories',
            \RainLab\Blog\Components\RssFeed::class => 'blogRssFeed',
        ];
    }

    /**
     * registerPermissions
     */
    public function registerPermissions()
    {
        return [
            'rainlab.blog.manage_settings' => [
                'tab' => "Blog",
                'label' => "Manage blog settings",
            ],
            'rainlab.blog.access_posts' => [
                'tab' => "Blog",
                'label' => "Manage the blog posts",
            ],
            'rainlab.blog.access_categories' => [
                'tab' => "Blog",
                'label' => "Manage the blog categories",
            ],
            'rainlab.blog.access_other_posts' => [
                'tab' => "Blog",
                'label' => "Manage other users blog posts",
            ],
            'rainlab.blog.access_import_export' => [
                'tab' => "Blog",
                'label' => "Allowed to import and export posts",
            ],
            'rainlab.blog.access_publish' => [
                'tab' => "Blog",
                'label' => "Allowed to publish posts",
            ],
        ];
    }

    /**
     * registerNavigation
     */
    public function registerNavigation()
    {
        return [
            'blog' => [
                'label' => "Blog",
                'url' => Backend::url('rainlab/blog/posts'),
                'icon' => 'icon-pencil',
                'iconSvg' => 'plugins/rainlab/blog/assets/images/blog-icon.svg',
                'permissions' => ['rainlab.blog.*'],
                'order' => 300,
                'sideMenu' => [
                    'new_post' => [
                        'label' => "New Post",
                        'icon' => 'icon-plus',
                        'url' => Backend::url('rainlab/blog/posts/create'),
                        'permissions' => ['rainlab.blog.access_posts'],
                    ],
                    'posts' => [
                        'label' => "Posts",
                        'icon' => 'icon-copy',
                        'url' => Backend::url('rainlab/blog/posts'),
                        'permissions' => ['rainlab.blog.access_posts'],
                    ],
                    'categories' => [
                        'label' => "Categories",
                        'icon' => 'icon-list-ul',
                        'url' => Backend::url('rainlab/blog/categories'),
                        'permissions' => ['rainlab.blog.access_categories'],
                    ],
                ],
            ],
        ];
    }

    /**
     * registerSettings
     */
    public function registerSettings()
    {
        return [
            'blog' => [
                'label' => "Blog",
                'description' => "Manage blog settings",
                'category' => "Blog",
                'icon' => 'icon-pencil',
                'class' => \RainLab\Blog\Models\Settings::class,
                'order' => 500,
                'keywords' => 'blog post category',
                'permissions' => ['rainlab.blog.manage_settings'],
            ],
        ];
    }

    /**
     * bootPageManagerLookups registers menu items for the RainLab.Pages plugin
     */
    protected function bootPageManagerLookups()
    {
        Event::listen('cms.pageLookup.listTypes', function() {
            return [
                'blog-category' => "Blog Category",
                'all-blog-categories' => ["All Blog Categories", true],
                'blog-post' => "Blog Post",
                'all-blog-posts' => ["All Blog Posts", true],
                'category-blog-posts' => ["Blog Category Posts", true],
            ];
        });

        Event::listen('pages.menuitem.listTypes', function() {
            return [
                'blog-category' => "Blog Category",
                'all-blog-categories' => "All Blog Categories",
                'blog-post' => "Blog Post",
                'all-blog-posts' => "All Blog Posts",
                'category-blog-posts' => "Blog Category Posts",
            ];
        });

        Event::listen(['cms.pageLookup.getTypeInfo', 'pages.menuitem.getTypeInfo'], function($type) {
            if ($type === 'blog-category' || $type === 'all-blog-categories') {
                return Category::getMenuTypeInfo($type);
            }
            elseif ($type === 'blog-post' || $type === 'all-blog-posts' || $type === 'category-blog-posts') {
                return Post::getMenuTypeInfo($type);
            }
        });

        Event::listen(['cms.pageLookup.resolveItem', 'pages.menuitem.resolveItem'], function($type, $item, $url, $theme) {
            if ($type === 'blog-category' || $type === 'all-blog-categories') {
                return Category::resolveMenuItem($item, $url, $theme);
            }
            elseif ($type === 'blog-post' || $type === 'all-blog-posts' || $type === 'category-blog-posts') {
                return Post::resolveMenuItem($item, $url, $theme);
            }
        });
    }
}

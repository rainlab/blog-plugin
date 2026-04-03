<?php namespace RainLab\Blog\Components;

use Lang;
use Redirect;
use BackendAuth;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use October\Rain\Database\Model;
use October\Rain\Database\Collection;
use RainLab\Blog\Models\Post as BlogPost;
use RainLab\Blog\Models\Category as BlogCategory;
use RainLab\Blog\Models\Settings as BlogSettings;

class Posts extends ComponentBase
{
    /**
     * A collection of posts to display
     *
     * @var Collection
     */
    public $posts;

    /**
     * Parameter to use for the page number
     *
     * @var string
     */
    public $pageParam;

    /**
     * If the post list should be filtered by a category, the model to use
     *
     * @var Model
     */
    public $category;

    /**
     * Message to display when there are no messages
     *
     * @var string
     */
    public $noPostsMessage;

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
     * If the post list should be ordered by another attribute
     *
     * @var string
     */
    public $sortOrder;

    public function componentDetails()
    {
        return [
            'name'        => "Post List",
            'description' => "Displays a list of latest blog posts on the page.",
        ];
    }

    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title'       => "Page number",
                'description' => "This value is used to determine what page the user is on.",
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'categoryFilter' => [
                'title'       => "Category filter",
                'description' => "Enter a category slug or URL parameter to filter the posts by. Leave empty to show all posts.",
                'type'        => 'string',
                'default'     => '',
            ],
            'postsPerPage' => [
                'title'             => "Posts per page",
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => "Invalid format of the posts per page value",
                'default'           => '10',
            ],
            'noPostsMessage' => [
                'title'             => "No posts message",
                'description'       => "Message to display in the blog post list in case if there are no posts. This property is used by the default component partial.",
                'type'              => 'string',
                'default'           => __("No posts found"),
                'showExternalParam' => false,
            ],
            'sortOrder' => [
                'title'       => "Post order",
                'description' => "Attribute on which the posts should be ordered",
                'type'        => 'dropdown',
                'default'     => 'published_at desc',
            ],
            'categoryPage' => [
                'title'       => "Category page",
                'description' => "Name of the category page file for the \"Posted into\" category links. This property is used by the default component partial.",
                'type'        => 'dropdown',
                'default'     => 'blog/category',
                'group'       => "Links",
            ],
            'postPage' => [
                'title'       => "Post page",
                'description' => "Name of the blog post page file for the \"Learn more\" links. This property is used by the default component partial.",
                'type'        => 'dropdown',
                'default'     => 'blog/post',
                'group'       => "Links",
            ],
            'exceptPost' => [
                'title'             => "Except post",
                'description'       => "Enter ID/URL or variable with post ID/URL you want to exclude. You may use a comma-separated list to specify multiple posts.",
                'type'              => 'string',
                'validationPattern' => '^[a-z0-9\-_,\s]+$',
                'validationMessage' => "Post exceptions must be a single slug or ID, or a comma-separated list of slugs and IDs",
                'default'           => '',
                'group'             => "Exceptions",
            ],
            'exceptCategories' => [
                'title'             => "Except categories",
                'description'       => "Enter a comma-separated list of category slugs or variable with such a list of categories you want to exclude",
                'type'              => 'string',
                'validationPattern' => '^[a-z0-9\-_,\s]+$',
                'validationMessage' => "Category exceptions must be a single category slug, or a comma-separated list of slugs",
                'default'           => '',
                'group'             => "Exceptions",
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getSortOrderOptions()
    {
        $options = BlogPost::$allowedSortingOptions;

        foreach ($options as $key => $value) {
            $options[$key] = Lang::get($value);
        }

        return $options;
    }

    public function onRun()
    {
        $this->prepareVars();

        $this->category = $this->page['category'] = $this->loadCategory();
        $this->posts = $this->page['posts'] = $this->listPosts();

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->posts->lastPage()) && $currentPage > 1) {
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
            }
        }
    }

    protected function prepareVars()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noPostsMessage = $this->page['noPostsMessage'] = $this->property('noPostsMessage');

        /*
         * Page links
         */
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
    }

    protected function listPosts()
    {
        $category = $this->category ? $this->category->id : null;
        $categorySlug = $this->category ? $this->category->slug : null;

        /*
         * List all the posts, eager load their categories
         */
        $isPublished = !$this->checkEditor();

        $posts = BlogPost::with(['categories', 'featured_images'])->listFrontEnd([
            'page'             => $this->property('pageNumber'),
            'sort'             => $this->property('sortOrder'),
            'perPage'          => $this->property('postsPerPage'),
            'search'           => trim(input('search')),
            'category'         => $category,
            'published'        => $isPublished,
            'exceptPost'       => is_array($this->property('exceptPost'))
                ? $this->property('exceptPost')
                : preg_split('/,\s*/', $this->property('exceptPost'), -1, PREG_SPLIT_NO_EMPTY),
            'exceptCategories' => is_array($this->property('exceptCategories'))
                ? $this->property('exceptCategories')
                : preg_split('/,\s*/', $this->property('exceptCategories'), -1, PREG_SPLIT_NO_EMPTY),
        ]);

        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        $posts->each(function($post) use ($categorySlug) {
            $post->setUrl($this->postPage, $this->controller, ['category' => $categorySlug]);

            $post->categories->each(function($category) {
                $category->setUrl($this->categoryPage, $this->controller);
            });
        });

        return $posts;
    }

    protected function loadCategory()
    {
        if (!$slug = $this->property('categoryFilter')) {
            return null;
        }

        $category = new BlogCategory;

        $category = $category->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
            ? $category->transWhere('slug', $slug)
            : $category->where('slug', $slug);

        $category = $category->first();

        return $category ?: null;
    }

    protected function checkEditor()
    {
        $backendUser = BackendAuth::getUser();

        return $backendUser &&
            $backendUser->hasAccess('rainlab.blog.access_posts') &&
            BlogSettings::get('show_all_posts', true);
    }
}

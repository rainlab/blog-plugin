<?php

use RainLab\Blog\Models\Post;
use RainLab\Blog\Models\Category;
use System\Models\SiteDefinition;

/**
 * TranslatedSlugTest checks posts and categories resolve by translated slugs (#596)
 */
class TranslatedSlugTest extends PluginTestCase
{
    /**
     * @var SiteDefinition enSite is the secondary site using the English locale
     */
    protected $enSite;

    /**
     * setUp creates a primary (es) and secondary (en) site definition
     */
    public function setUp(): void
    {
        parent::setUp();

        SiteDefinition::query()->delete();

        $primarySite = new SiteDefinition;
        $primarySite->name = 'Primary Site';
        $primarySite->code = 'primary';
        $primarySite->locale = 'es';
        $primarySite->is_primary = true;
        $primarySite->is_enabled = true;
        $primarySite->is_enabled_edit = true;
        $primarySite->save();

        $this->enSite = new SiteDefinition;
        $this->enSite->name = 'English Site';
        $this->enSite->code = 'english';
        $this->enSite->locale = 'en';
        $this->enSite->is_enabled = true;
        $this->enSite->is_enabled_edit = true;
        $this->enSite->save();

        Site::resetCache();
    }

    /**
     * testFindsPostByTranslatedSlug replicates the OP scenario where a translated
     * slug should resolve the post on the localized site
     */
    public function testFindsPostByTranslatedSlug()
    {
        $post = $this->createPost('primer-post');
        $post->setTranslation('slug', 'en', 'first-post');
        $post->save();

        $found = Site::withContext($this->enSite->id, function() {
            return Post::transWhere('slug', 'first-post')->first();
        });

        $this->assertNotNull($found);
        $this->assertEquals($post->id, $found->id);
        $this->assertEquals('first-post', $found->slug);
    }

    /**
     * testFindsPostByInheritedSlug checks a post without a translated slug still
     * resolves by its base slug on the localized site
     */
    public function testFindsPostByInheritedSlug()
    {
        $post = $this->createPost('sin-traduccion');

        $found = Site::withContext($this->enSite->id, function() {
            return Post::transWhere('slug', 'sin-traduccion')->first();
        });

        $this->assertNotNull($found);
        $this->assertEquals($post->id, $found->id);
    }

    /**
     * testFindsPostByBaseSlugOnPrimarySite checks the default locale matches the
     * base slug only, not the translated slug
     */
    public function testFindsPostByBaseSlugOnPrimarySite()
    {
        $post = $this->createPost('primer-post');
        $post->setTranslation('slug', 'en', 'first-post');
        $post->save();

        $this->assertNotNull(Post::transWhere('slug', 'primer-post')->first());
        $this->assertNull(Post::transWhere('slug', 'first-post')->first());
    }

    /**
     * testFindsPostByExplicitLocale checks the locale can be supplied directly
     * without a site context
     */
    public function testFindsPostByExplicitLocale()
    {
        $post = $this->createPost('primer-post');
        $post->setTranslation('slug', 'en', 'first-post');
        $post->save();

        $found = Post::transWhere('slug', 'first-post', 'en')->first();

        $this->assertNotNull($found);
        $this->assertEquals($post->id, $found->id);
    }

    /**
     * testFindsCategoryByTranslatedSlug checks the category filter lookup used by
     * the Posts component resolves translated slugs
     */
    public function testFindsCategoryByTranslatedSlug()
    {
        $category = new Category;
        $category->name = 'Noticias';
        $category->slug = 'noticias';
        $category->save();

        $category->setTranslation('slug', 'en', 'news');
        $category->save();

        $found = Site::withContext($this->enSite->id, function() {
            return Category::transWhere('slug', 'news')->first();
        });

        $this->assertNotNull($found);
        $this->assertEquals($category->id, $found->id);
    }

    /**
     * createPost creates a valid post with the given slug
     */
    protected function createPost(string $slug): Post
    {
        $post = new Post;
        $post->title = 'Test Post';
        $post->slug = $slug;
        $post->content = 'Test content';
        $post->save();

        return $post;
    }
}

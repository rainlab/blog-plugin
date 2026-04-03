<?php namespace RainLab\Blog\Updates;

use Carbon\Carbon;
use RainLab\Blog\Models\Post;
use RainLab\Blog\Models\Category;
use October\Rain\Database\Updates\Seeder;

/**
 * SeedTables for the blog plugin
 */
class SeedTables extends Seeder
{
    /**
     * run migration
     */
    public function run()
    {
        if (Post::count() > 0) {
            return;
        }

        Post::create([
            'title' => 'First blog post',
            'slug' => 'first-blog-post',
            'content' => '
This is your first ever **blog post**! It might be a good idea to update this post with some more relevant content.

You can edit this content by selecting **Blog** from the administration back-end menu.

*Enjoy the good times!*
            ',
            'excerpt' => 'The first ever blog post is here. It might be a good idea to update this post with some more relevant content.',
            'published_at' => Carbon::now(),
            'published' => true
        ]);

        if (Category::where('slug', 'uncategorized')->count() === 0) {
            Category::create([
                'name' => trans('rainlab.blog::lang.categories.uncategorized'),
                'slug' => 'uncategorized',
            ]);
        }
    }
}

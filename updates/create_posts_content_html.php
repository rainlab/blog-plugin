<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePostsContentHtml extends Migration
{

    public function up()
    {
        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->text('content_html')->nullable();
        });
    }

    public function down()
    {
        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->dropColumn('content_html');
        });
    }
}

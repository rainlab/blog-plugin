<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use RainLab\Blog\Models\Category as CategoryModel;

class PostsAddIsFeaturedField extends Migration
{

    public function up()
    {

        // Column featured already present, do nothing
        if (Schema::hasColumn('rainlab_blog_posts', 'is_featured')) {
            return;
        }


        // Add the column
        Schema::table('rainlab_blog_categories', function($table)
        {
            $table->boolean('is_featured')->default(false);
        });

    }

    public function down()
    {
        // Remove the added column
        Schema::table('rainlab_blog_categories', function($table)
        {
            $table->dropColumn('is_featured');
        });
    }

}


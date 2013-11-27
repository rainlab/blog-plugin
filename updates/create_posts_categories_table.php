<?php namespace Plugins\October\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePostsCategoriesTable extends Migration 
{

    public function up()
    {
        Schema::create('october_blog_posts_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->primary(array('post_id', 'category_id'));
        });
    }

    public function down()
    {
        Schema::drop('october_blog_posts_categories');
    }

}

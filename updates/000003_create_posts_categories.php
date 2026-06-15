<?php

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rainlab_blog_posts_categories', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->primary(['post_id', 'category_id'], 'blog_post_category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rainlab_blog_posts_categories');
    }
};

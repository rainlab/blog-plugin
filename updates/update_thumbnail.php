<?php namespace RainLab\Blog\Updates;

use Schema;
use DbDongle;
use October\Rain\Database\Updates\Migration;

class UpdateThumbnail extends Migration
{

    public function up()
    {
        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->text('blog_image_thumbnail')->nullable();
        });
    }

    public function down()
    {
        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->dropColumn('blog_image_thumbnail');
        });
    }

}

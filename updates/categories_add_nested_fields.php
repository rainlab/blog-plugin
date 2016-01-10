<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use RainLab\Blog\Models\Category as CategoryModel;

class CategoriesAddNestedFields extends Migration
{

    public function up()
    {
        Schema::table('rainlab_blog_categories', function($table)
        {
            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();
        });

        foreach (CategoryModel::all() as $category) {
            $category->setDefaultLeftAndRight();
            $category->save();
        }
    }

    public function down()
    {
        Schema::table('rainlab_blog_categories', function($table)
        {
            $table->dropColumn('parent_id');
            $table->dropColumn('nest_left');
            $table->dropColumn('nest_right');
            $table->dropColumn('nest_depth');
        });
    }

}
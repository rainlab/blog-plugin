<?php
namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use RainLab\Blog\Models\Setting;

class CreateSettingsTable extends Migration {

	public function up() {
		$table = "rainlab_blog_settings";
        $fields = [
            [
                'name' => 'ulroot',
                'label' => 'UL Root Class',
                'placeholder' => 'Class Name',
                'target' => '.ul-root',
                'class' => ''
            ],
            [
                'name' => 'liitem',
                'label' => 'LI Item Class',
                'placeholder' => 'Class Name',
                'target' => '.li-item',
                'class' => ''
            ],
            [
                'name' => 'h3title',
                'label' => 'H3 Title Class',
                'placeholder' => 'Class Name',
                'target' => '.h3-title',
                'class' => ''
            ],
            [
                'name' => 'h3atitle',
                'label' => 'H3 > A Title Class',
                'placeholder' => 'Class Name',
                'target' => '.h3-a-title',
                'class' => ''
            ],
            [
                'name' => 'pinfo',
                'label' => 'P.info Class',
                'placeholder' => 'Class Name',
                'target' => '.p-info',
                'class' => ''
            ],
            [
                'name' => 'pinfoa',
                'label' => 'P.info > a Class',
                'placeholder' => 'Class Name',
                'target' => '.p-info-a',
                'class' => ''
            ],
            [
                'name' => 'pexcerpt',
                'label' => 'P.excerpt',
                'placeholder' => 'Class Name',
                'target' => '.p-excerpt',
                'class' => ''
            ]
        ];
        
        
		Schema::create($table, function($table) {
			$table -> engine = 'InnoDB';
			$table -> increments('id');
			$table -> text('name') -> nullable();
            $table -> text('label') -> nullable();
            $table -> text('placeholder') -> nullable();
            $table -> text('target') -> nullable();
            $table -> text('class') -> nullable();
			$table -> timestamps();
		});
		
        foreach($fields as $key => $field){
            $setting = new Setting();
            $setting -> name = $field['name'];
            $setting -> label = $field['label'];
            $setting -> placeholder = $field['placeholder'];
            $setting -> target = $field['target'];
            $setting -> class = $field['class'];
            $setting -> save();
        }
	}

	public function down() {
		Schema::drop('rainlab_blog_settings');
	}

}

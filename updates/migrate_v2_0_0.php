<?php

use October\Rain\Database\Updates\Migration;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('rainlab_translate_attributes') || !Schema::hasTable('system_translate_attributes')) {
            return;
        }

        $modelTypes = [
            'RainLab\Blog\Models\Post',
            'RainLab\Blog\Models\Category',
        ];

        // Migrate attribute JSON blobs to per-row records
        Db::table('rainlab_translate_attributes')
            ->whereIn('model_type', $modelTypes)
            ->whereNotNull('model_id')
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    if (!is_numeric($row->model_id)) {
                        continue;
                    }

                    $data = json_decode($row->attribute_data, true);
                    if (!is_array($data)) {
                        continue;
                    }

                    foreach ($data as $attribute => $value) {
                        if ($value === null || $value === '') {
                            continue;
                        }

                        Db::table('system_translate_attributes')->upsert(
                            [
                                'model_type' => $row->model_type,
                                'model_id' => (int) $row->model_id,
                                'locale' => $row->locale,
                                'attribute' => $attribute,
                                'value' => is_array($value) ? json_encode($value) : $value,
                            ],
                            ['model_type', 'model_id', 'locale', 'attribute'],
                            ['value']
                        );
                    }
                }
            });

        // Migrate indexes (catches indexed values like slugs)
        if (!Schema::hasTable('rainlab_translate_indexes')) {
            return;
        }

        Db::table('rainlab_translate_indexes')
            ->whereIn('model_type', $modelTypes)
            ->whereNotNull('model_id')
            ->whereNotNull('item')
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    if (!is_numeric($row->model_id) || $row->value === null || $row->value === '') {
                        continue;
                    }

                    Db::table('system_translate_attributes')->upsert(
                        [
                            'model_type' => $row->model_type,
                            'model_id' => (int) $row->model_id,
                            'locale' => $row->locale,
                            'attribute' => $row->item,
                            'value' => $row->value,
                        ],
                        ['model_type', 'model_id', 'locale', 'attribute'],
                        ['value']
                    );
                }
            });
    }

    public function down()
    {
    }
};

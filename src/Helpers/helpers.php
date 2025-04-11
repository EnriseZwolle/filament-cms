<?php

use Enrisezwolle\FilamentCms\Models\SystemLabel;

if (! function_exists('get_model_for_label')) {
    function get_model_for_label(string $label, ?array $load = null, bool $ignoreCache = false): mixed
    {
        static $cache = [];

        if ($ignoreCache || ! array_key_exists($label, $cache)) {
            $model = SystemLabel::getModel($label);

            if ($load) {
                $model->load($load);
            }

            $cache[$label] = $model;
        }

        return $cache[$label];
    }
}

<?php

namespace WebId\Flan\Filters\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MagicCollector
{
    /**
     * @return array<string>
     */
    public static function getClasses(): array
    {
        $filterClassDirectory = app_path(config('flan.filter_class_directory'));
        if (data_get($_ENV, 'DB_TEST_MODE', false) == true) {
            $filterClassDirectory = __DIR__ . '/../';
        }

        if (! File::exists($filterClassDirectory)) {
            return [];
        }

        $classes = [];
        $filesInFolder = File::files($filterClassDirectory);
        foreach ($filesInFolder as $path) {
            $file = pathinfo($path);
            $fileName = $file['filename'];
            if (str_ends_with($fileName, 'Filter')) {
                $configName = substr($fileName, 0, -6);
                $configName = Str::lower(Str::plural($configName));
                $classes[$configName] = '\WebId\Flan\Filters\\' . $fileName;
            }
        }

        return $classes;
    }
}

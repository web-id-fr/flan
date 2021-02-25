<?php

namespace WebId\Flan\Filters\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MagicCollector
{
    /**
     * @return array
     */
    public static function getClasses(): array
    {
        if (!File::exists(app_path(config('flan.filter_class_directory')))) {
            return [];
        }

        $classes = [];
        $filesInFolder = File::files(app_path(config('flan.filter_class_directory')));
        foreach($filesInFolder as $path) {
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

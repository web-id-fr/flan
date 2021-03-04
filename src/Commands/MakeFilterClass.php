<?php

namespace WebId\Flan\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeFilterClass extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $signature = 'make:filter:class {name : The name of the model}';

    /**
     * @var string
     */
    protected $name = 'make:filter:class';

    /**
     * @var string
     */
    protected $description = 'Create an filter class';

    /**
     * @param  string  $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
            ->replaceModelName($stub, $name)
            ->replaceModel($stub, $name)
            ->replaceConfigName($stub, $name)
            ->replaceClass($stub, $name);
    }

    /**
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceModel(&$stub, $name)
    {
        $className = explode('\\', $name);
        $className = $className[count($className) - 1];
        $stub = str_replace('DummyModel', config('flan.default_model_namespace') . '\\' . ucfirst($className), $stub);

        return $this;
    }

    /**
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceModelName(&$stub, $name)
    {
        $className = explode('\\', $name);
        $className = $className[count($className) - 1];
        $stub = str_replace('DummyModelName', ucfirst($className), $stub);

        return $this;
    }

    /**
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceConfigName(&$stub, $name)
    {
        $className = explode('\\', $name);
        $className = $className[count($className) - 1];
        $stub = str_replace('dummyConfigName', Str::lower(Str::plural($className)), $stub);

        return $this;
    }

    /**
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $className = explode('\\', $name);
        $className = ucfirst($className[count($className) - 1]);

        return config('flan.filter_class_directory') . '/' . $className .'Filter.php';
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/filter-class-model.stub';
    }

    /**
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        return config('flan.default_filter_class_namespace') . '\\' . $name;
    }
}

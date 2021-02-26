<?php

namespace WebId\Flan\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeFilterClass extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter:class {name : The name of the model}';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:filter:class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an filter class';

    /**
     * Build the class with the given name.
     *
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
     * Replace the model key-word on stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceModel(&$stub, $name)
    {
        $className = explode('\\', $name);
        $className = $className[count($className) - 1];
        $stub = str_replace('DummyModel', 'App\\Models\\' . ucfirst($className), $stub);

        return $this;
    }

    /**
     * Replace the model key-word on stub.
     *
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
     * Replace the model key-word on stub.
     *
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
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . config('flan.filter_class_directory');
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $className = explode('\\', $name);
        $className[count($className) - 1] = ucfirst($className[count($className) - 1]);
        $name = implode('\\', $className);

        return app_path(str_replace('\\', '/', $name).'Filter.php');
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/filter-class-model.stub';
    }
}

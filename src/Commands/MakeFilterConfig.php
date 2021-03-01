<?php

namespace WebId\Flan\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeFilterConfig extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter:config {name : The name of the model}';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:filter:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an filter config';

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
    protected function replaceConfigName(&$stub, $name)
    {
        $className = explode('\\', $name);
        $className = $className[count($className) - 1];
        $stub = str_replace('dummyConfigName', Str::lower(Str::plural($className)), $stub);

        return $this;
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
        $className = Str::lower(Str::plural($className[count($className) - 1]));

        return config('flan.filter_config_directory')  . '/' . $className . '.php';
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/filter-config-model.stub';
    }
}

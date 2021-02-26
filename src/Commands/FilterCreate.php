<?php

namespace WebId\Flan\Commands;

use Illuminate\Console\Command;

class FilterCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filter:create {name : The name of the model}';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'filter:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an filter class with filter config';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->call('make:filter:class', [
            'name' => $name,
        ]);

        $this->call('make:filter:config', [
            'name' => $name,
        ]);
    }
}

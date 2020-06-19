<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeRouterCRUD extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:router';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new router class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Router';

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);
        $model = $this->argument('model');
        $stub = str_replace('DummyModel', $model, $stub);
        $stub = str_replace('DummyController', $model."Controller", $stub);
        $version = $this->argument('version');
        $stub = str_replace('DummyVersion', $version, $stub);
        $prefix = $this->argument('prefix');
        return str_replace("DummyPrefix", $prefix, $stub);
    }
    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $version = $this->argument('version');
        $version = strtolower($version);
        return $rootNamespace."\Http\Routes\\".$version;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return resource_path('stubs/router.stub');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the router.'],
            ['model', InputArgument::REQUIRED, 'The name of the model.'],
            ['version', InputArgument::REQUIRED, 'The version of router.'],
            ['prefix', InputArgument::REQUIRED, 'The prefix of routes.'],
        ];
    }
}
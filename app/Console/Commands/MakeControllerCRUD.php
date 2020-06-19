<?php

namespace App\Console\Commands;


use App\Core\Http\BaseController;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeControllerCRUD extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

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
        $stub = str_replace('DummyService', $model."Service", $stub);
        $version = $this->argument('version');
        $stub = str_replace('DummyVersion', $version, $stub);
        $baseController = BaseController::class;
        return str_replace("DummyBaseController", $baseController, $stub);
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
        return $rootNamespace."\Http\Controllers\API\\".$version;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return resource_path('stubs/controller.stub');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller.'],
            ['model', InputArgument::REQUIRED, 'The name of the model.'],
            ['version', InputArgument::REQUIRED, 'The version of controller.'],
        ];
    }
}
<?php

namespace App\Console\Commands;

use App\Core\Data\BaseRepository;
use App\Core\Domain\BaseService;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeSwaggerModel extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:swagger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new swagger model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Swager model';

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
        return str_replace('DummyModel', $model, $stub);
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
        return $rootNamespace."\Http\Swagger\\".$version."\Models";
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return resource_path('stubs/swaggermodel.stub');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the repository.'],
            ['model', InputArgument::REQUIRED, 'The name of the model.'],
            ['version', InputArgument::REQUIRED, 'The version of repository.'],
        ];
    }
}
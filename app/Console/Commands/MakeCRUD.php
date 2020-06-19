<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeCRUD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command can make and prepare CRUD actions with repository pattern';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = $this->ask('What is your model name?');
        $table = $this->anticipate('What is your table name?', [strtolower($model)]);
        if ($this->confirm('Do you want to create model?')) {
            $this->call('make:model', [
                'name' => $model,
                'table' => $table
            ]);
        }
        if ($this->confirm('Do you want to create swagger model?')) {
            $version = $this->anticipate('What is your swagger model version?', ["V1"]);
            $this->call('crud:swagger', [
                'name' => $model,
                'model' => $model,
                'version' => $version
            ]);
        }
        if ($this->confirm('Do you want to create repository?')) {
            $version = $this->anticipate('What is your repository version?', ["V1"]);
            $this->call('crud:repository', [
                'name' => "{$model}Repository",
                'model' => $model,
                'version' => $version
            ]);
        }
        if ($this->confirm('Do you want to create service?')) {
            $version = $this->anticipate('What is your service version?', ["V1"]);
            $this->call('crud:service', [
                'name' => "{$model}Service",
                'model' => $model,
                'version' => $version
            ]);
        }
        if ($this->confirm('Do you want to create controller?')) {
            $version = $this->anticipate('What is your controller version?', ["V1"]);
            $this->call('crud:controller', [
                'name' => "{$model}Controller",
                'model' => $model,
                'version' => $version
            ]);
        }
        if ($this->confirm('Do you want to create router?')) {
            $version = $this->anticipate('What is your router version?', ["v1"]);
            $prefix = $this->anticipate('What is your router prefix?', [$table]);
            $this->call('crud:router', [
                'name' => "{$model}Router",
                'model' => $model,
                'version' => $version,
                'prefix' => $prefix
            ]);
        }
    }
}

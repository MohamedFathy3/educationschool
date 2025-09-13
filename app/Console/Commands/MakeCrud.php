<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCrud extends Command
{
    protected $signature = 'make:crud {name}';
    protected $description = 'Generate a CRUD structure (controller, requests, resource)';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $modelVariable = lcfirst($name);
        $modelPlural = Str::plural($name);

        $paths = [
            'controller' => app_path("Http/Controllers/{$name}Controller.php"),
            'resource' => app_path("Http/Resources/{$name}Resource.php"),
            'createRequest' => app_path("Http/Requests/{$name}CreateRequest.php"),
            'updateRequest' => app_path("Http/Requests/{$name}UpdateRequest.php"),
        ];

        $stubs = [
            'controller' => base_path('stubs/controller.stub'),
            'resource' => base_path('stubs/resource.stub'),
            'createRequest' => base_path('stubs/create_request.stub'),
            'updateRequest' => base_path('stubs/update_request.stub'),
        ];

        foreach ($paths as $key => $outputPath) {
            if (!file_exists($stubs[$key])) {
                $this->error("Stub for {$key} does not exist. Please create a stub at: " . $stubs[$key]);
                return;
            }

            $stubContent = file_get_contents($stubs[$key]);
            $replacements = [
                '{{ namespace }}' => "App\\Http\\" . ucfirst(Str::camel($key)) . "s",
                '{{ class }}' => "{$name}" . Str::studly(str_replace(['createRequest', 'updateRequest'], ['CreateRequest', 'UpdateRequest'], $key)),
                '{{ model }}' => $name,
                '{{ modelVariable }}' => $modelVariable,
                '{{ modelPlural }}' => $modelPlural,
            ];

            foreach ($replacements as $key => $value) {
                $stubContent = str_replace($key, $value, $stubContent);
            }

            file_put_contents($outputPath, $stubContent);
            $this->info("Created: {$outputPath}");
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[Signature('make:module {name} {--admin}')]
#[Description('Create full module (Controller, Service, Repository, Model, View, Routes)')]
class MakeModuleCommand extends Command
{
	const MODULE_ADMIN = 'Admin';
	const MODULE_CLIENT = 'Client';
	public function handle()
	{
		$name = Str::studly($this->argument('name'));
		$isAdmin = $this->option('admin');

		$type = $isAdmin ? self::MODULE_ADMIN : self::MODULE_CLIENT;

		$basePath = base_path("modules/{$type}/{$name}");

		$this->createDirectories($basePath);
		$this->createFiles($basePath, $name, $type);

		$this->info("Module {$name} created in {$type}");
	}

	private function createDirectories($basePath)
	{
		$dirs = [
			'Controllers',
			'Services',
			'Repositories',
			'Models',
			'Views',
		];

		foreach ($dirs as $dir) {
			File::makeDirectory("{$basePath}/{$dir}", 0755, true, true);
		}
	}

	private function createFiles($basePath, $name, $type)
	{
		$this->put(
			"{$basePath}/Controllers/{$name}Controller.php",
			$this->controllerStub($name, $type)
		);

		$this->put(
			"{$basePath}/Services/{$name}Service.php",
			$this->serviceStub($name, $type)
		);

		$this->put(
			"{$basePath}/Repositories/{$name}Repository.php",
			$this->repositoryStub($name, $type)
		);

		$this->put(
			"{$basePath}/Models/{$name}Model.php",
			$this->modelStub($name, $type)
		);

		$viewPath = $type === self::MODULE_ADMIN
			? "Views/index.blade.php"
			: "Views/pages/{$name}s/index.blade.php";

		$this->put("{$basePath}/{$viewPath}", "<h1>{$name}</h1>");

		$this->put(
			"{$basePath}/routes.php",
			$this->routeStub($name)
		);
	}

	private function put($path, $content)
	{
		File::ensureDirectoryExists(dirname($path));
		File::put($path, $content);
	}

	/*
    |--------------------------------------------------------------------------
    | STUBS
    |--------------------------------------------------------------------------
    */

	private function controllerStub($name, $type)
	{
		$namespace = "Modules\\{$type}\\{$name}\\Controllers";

		return <<<PHP
<?php

namespace {$namespace};

class {$name}Controller
{
    public function index()
    {
        return view('{$name}::index');
    }
}
PHP;
	}

	private function serviceStub($name, $type)
	{
		$namespace = "Modules\\{$type}\\{$name}\\Services";

		return <<<PHP
<?php

namespace {$namespace};

class {$name}Service
{

}
PHP;
	}

	private function repositoryStub($name, $type)
	{
		$namespace = "Modules\\{$type}\\{$name}\\Repositories";

		return <<<PHP
<?php

namespace {$namespace};

class {$name}Repository
{

}
PHP;
	}

	private function modelStub($name, $type)
	{
		$namespace = "Modules\\{$type}\\{$name}\\Models";

		return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Database\Eloquent\Model;

class {$name}Model extends Model
{
    protected \$table = strtolower('{$name}s');
}
PHP;
	}

	private function routeStub($name)
	{
		$lower = Str::lower($name);

		return <<<PHP
<?php

use Illuminate\Support\Facades\Route;

Route::get('/{$lower}', function () {
    return 'Hello {$name}';
});
PHP;
	}
}

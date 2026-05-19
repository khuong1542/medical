<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[Signature('make:module-client {name}')]
#[Description('Generate Controller, Service, Repository, Model, View')]
class MakeModuleClientCommand extends Command
{
	const string CLIENT = 'Client';
	const int SUCCESS = 0;

	public function handle(): int
	{
		$name = Str::studly($this->argument('name'));
		$type = 'Client';

		$this->createController($name, $type);
		$this->createService($name, $type);
		$this->createRepository($name, $type);
		$this->createModel($name);
		$this->createView($name);

		$this->components->info("Generated $name for $type successfully.");
		return self::SUCCESS;
	}

	// ========================
	// CREATE FILES
	// ========================

	private function createController(string $name, string $type): void
	{
		$path = app_path("Http\\Controllers\\$type\\{$name}Controller.php");

		$this->put($path, $this->controllerStub($name, $type));
	}

	private function createService(string $name, string $type): void
	{
		$path = app_path("Http\\Services\\$type\\{$name}Service.php");

		$this->put($path, $this->serviceStub($name, $type));
	}

	private function createRepository(string $name, string $type): void
	{
		$path = app_path("Http\\Repositories\\$type\\{$name}Repository.php");

		$this->put($path, $this->repositoryStub($name, $type));
	}

	private function createModel(string $name): void
	{
		$path = app_path("Models\\$name.php");

		$this->put($path, $this->modelStub($name));
	}

	private function createView(string $name): void
	{
		$lower = Str::lower($name);
		$plural = Str::plural($lower);

		$viewBase = "client\\pages\\$plural";

		$pathIndex = resource_path("views\\$viewBase\\index.blade.php");

		$this->put($pathIndex, $this->viewStub($name));
	}

	private function put(string $path, string $content): void
	{
		$relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $path);
		if (File::exists($path)) {
			$this->components->error("$relativePath already exists.");
			return;
		}

		File::ensureDirectoryExists(dirname($path));
		File::put($path, $content);

		$this->components->twoColumnDetail($relativePath, 'Created');
	}

	// ========================
	// STUBS
	// ========================

	private function controllerStub(string $name, string $type): string
	{
		$namespace = "App\\Http\\Controllers\\$type";
		$service = "App\\Http\\Services\\$type\\{$name}Service";

		$lower = strtolower($name);
		$plural = \Str::plural($lower);

		$viewBase = "client.pages.$plural";

		return <<<PHP
		<?php

		namespace $namespace;

		use App\Http\Controllers\Controller;
		use $service;
		use Throwable;

		class {$name}Controller extends Controller
		{
			public function __construct(private readonly {$name}Service \$service) {}

			public function index()
			{
				\$result = [];
				return view('$viewBase.index', \$result);
			}
		}
		PHP;
	}

	private function serviceStub(string $name, string $type): string
	{
		$namespace = "App\\Http\\Services\\$type";
		$repository = "App\\Http\\Repositories\\$type\\{$name}Repository";

		return <<<PHP
		<?php

		namespace $namespace;

		use App\Base\BaseService;
		use $repository;
		use Throwable;

		class {$name}Service extends BaseService
		{
			public function __construct()
			{
				parent::__construct();
			}

			public function repository(): string
			{
				return {$name}Repository::class;
			}
		}
		PHP;
	}

	private function repositoryStub(string $name, string $type): string
	{
		$namespace = "App\\Http\\Repositories\\$type";
		$model = "App\\Models\\$name";

		return <<<PHP
		<?php

		namespace $namespace;

		use App\Base\BaseRepository;
		use $model;

		class {$name}Repository extends BaseRepository
		{
			public function __construct()
			{
				parent::__construct();
			}

			public function model(): string
			{
				return $name::class;
			}
		}
		PHP;
	}

	private function modelStub(string $name): string
	{
		return <<<PHP
		<?php

		namespace App\Models;

		use Illuminate\Database\Eloquent\Attributes\Fillable;
		use Illuminate\Database\Eloquent\Model;

		#[Fillable(['id', 'code', 'name', 'order', 'status', 'created_at', 'updated_at'])]
		class $name extends Model
		{
			protected \$table = '{$this->tableName($name)}';

			public \$incrementing = false;

			public \$sortable = ['order'];

			public \$casts = [
				'status' => 'boolean',
			];
		}
		PHP;
	}

	private function viewStub(string $name): string
	{
		$lower = strtolower($name);

		$pathCss = "assets/admin/css/pages/$lower.css";
		$pathJs = "assets/client/js/pages/$lower.js";

		$urlCss = '';
		$urlJs = '';

		if (File::exists(base_path($pathCss))) {
			$urlCss = asset($pathCss);
		}

		if (File::exists(base_path($pathJs))) {
			$urlJs = asset($pathJs);
		}

		return <<<PHP
		@extends('client.index')

		@section('style')
			<link rel="stylesheet" href="$urlCss">
		@endsection

		@section('content')

		@endsection

		@section('script')
			<script src="$urlJs"></script>
		@endsection
		PHP;
	}

	private function tableName(string $name): string
	{
		return Str::snake(Str::pluralStudly($name));
	}
}

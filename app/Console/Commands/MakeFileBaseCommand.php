<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[Signature('make:base {name} {--admin}')]
#[Description('Generate Controller, Service, Repository, Model, View')]
class MakeFileBaseCommand extends Command
{
	const string ADMIN = 'Admin';
	const string CLIENT = 'Client';
	const int SUCCESS = 0;

	public function handle(): int
	{
		$name = Str::studly($this->argument('name'));
		$isAdmin = $this->option('admin');

		$type = $isAdmin ? self::ADMIN : self::CLIENT;

		$this->createController($name, $type);
		$this->createRequest($name, $type);
		$this->createService($name, $type);
		$this->createRepository($name, $type);
		$this->createModel($name);
		$this->createView($name, $type);

		$this->info("Generated {$name} for {$type}");

		return self::SUCCESS;
	}

	// ========================
	// CREATE FILES
	// ========================

	private function createController($name, $type): void
	{
		$path = app_path("Http/Controllers/{$type}/{$name}Controller.php");

		$this->put($path, $this->controllerStub($name, $type));
	}

	private function createRequest($name, $type): void
	{
		$basePath = app_path("Http/Requests/{$type}/{$name}");

		$storePath = "{$basePath}/Store{$name}Request.php";
		$updatePath = "{$basePath}/Update{$name}Request.php";

		$this->put($storePath, $this->requestStub($name, $type, 'Store'));
		$this->put($updatePath, $this->requestStub($name, $type, 'Update'));
	}

	private function createService($name, $type): void
	{
		$path = app_path("Http/Services/{$type}/{$name}Service.php");

		$this->put($path, $this->serviceStub($name, $type));
	}

	private function createRepository($name, $type): void
	{
		$path = app_path("Http/Repositories/{$type}/{$name}Repository.php");

		$this->put($path, $this->repositoryStub($name, $type));
	}

	private function createModel(string $name): void
	{
		$path = app_path("Models/{$name}.php");

		if (!File::exists($path)) {
			$this->put($path, $this->modelStub($name));
		}
	}

	private function createView(string $name, string $type): void
	{
		$lower = Str::lower($name);
		$plural = Str::plural($lower);

		$viewBase = $type === self::ADMIN ? "admin/pages/{$plural}" : "client/pages/{$plural}";

		$pathIndex = resource_path("views/{$viewBase}/index.blade.php");
		if (File::exists($pathIndex)) {
			$this->put($pathIndex, "<h1>{$name}</h1>");
		}

		if ($type === self::ADMIN) {
			$pathLoadList = resource_path("views/{$viewBase}/loadList.blade.php");
			$pathForm = resource_path("views/{$viewBase}/form.blade.php");

			if (File::exists($pathLoadList)) {
				$this->put($pathLoadList, "<h1>List {$name}</h1>");
			}

			if (File::exists($pathForm)) {
				$this->put($pathForm, "<h1>Form {$name}</h1>");
			}
		}
	}

	private function put($path, $content): void
	{
		File::ensureDirectoryExists(dirname($path));
		File::put($path, $content);
	}

	// ========================
	// STUBS
	// ========================

	private function controllerStub(string $name, string $type): string
	{
		$namespace = "App\\Http\\Controllers\\{$type}";
		$service = "App\\Http\\Services\\{$type}\\{$name}Service";
		$storeRequest = "App\\Http\\Requests\\{$type}\\{$name}\\Store{$name}Request";
		$updateRequest = "App\\Http\\Requests\\{$type}\\{$name}\\Update{$name}Request";

		$lower = strtolower($name);
		$plural = \Str::plural($lower);

		$viewBase = $type === self::ADMIN ? "admin.pages.{$plural}" : "client.pages.{$plural}";

        return <<<PHP
		<?php

		namespace {$namespace};

		use App\Http\Controllers\Controller;
		use {$storeRequest};
		use {$updateRequest};
		use {$service};
		use Illuminate\Http\RedirectResponse;
		use Illuminate\Http\Request;
		use Illuminate\View\View;
		use Throwable;

		class {$name}Controller extends Controller
		{
			public function __construct(private readonly {$name}Service \$service) {}

			public function index()
			{
				\$result = [];
				return view('{$viewBase}.index', \$result);
			}

			/**
			* Load list data
			*
			* @param Request \$request
			* @return array
			*
			* @throws Throwable
			*/
			public function loadList(Request \$request)
			{
				\$result = [
					'datas' => \$this->service->loadList(\$request->all()),
				];
				return [
					'arrData' => view('{$viewBase}.loadList', \$result)->render(),
					'perPage' => \$request->offset ?? OFFSET,
				];
			}

			/**
			* Show the form for creating a new resource.
			*
			* @return View
			*/
			public function create()
			{
				\$result = [
					'checked' => "checked=true",
					'order' => \$this->service->count() + 1,
				];
				return view('{$viewBase}.form', \$result);
			}

			/**
			* Store a newly created resource in storage.
			*
			* @param Store{$name}Request \$request
			* @return RedirectResponse
			*
			* @throws Throwable
			*/
			public function store(Store{$name}Request \$request)
			{
				try {
					\$this->service->updateOrStore(\$request->all());
					return redirect(route('{$plural}.index'));
				} catch (\Exception \$e) {
					return redirect()->back()->withInput()->with('error', 'Create failed, please try again');
				}
			}

			/**
			* Display the specified resource.
			*
			* @param string \$id Resource identifier
			* @return View
			*/
			public function show(string \$id)
			{
				\$data = \$this->service->find(\$id);
				return view('{$viewBase}.show', compact('data'));
			}

			/**
			* Show the form for editing the specified resource.
			*
			* @param string \$id Resource identifier
			* @return View
			*/
			public function edit(string \$id)
			{
				\$result = [
					'data' => \$this->service->find(\$id),
					'checked' => "checked=true",
					'order' => \$this->service->count() + 1,
				];
				return view('{$viewBase}.form', \$result);
			}

			/**
			* Update resource and redirect to index.
			*
			* @param Update{$name}Request \$request
			* @param string \$id
			* @return RedirectResponse
			*
			* @throws Throwable
			*/
			public function update(Update{$name}Request \$request, string \$id)
			{
				try {
					\$this->service->updateOrStore(\$request->validated(), \$id);
					return redirect()->route('{$plural}.index');
				} catch (\Exception \$e) {
					return redirect()->back()->withInput()->with('error', 'Update failed, please try again');
				}
			}

			/**
			* Delete multiple records.
			*
			* @param Request \$request
			* @return array
			*
			* @throws Throwable
			*/
			public function destroy(Request \$request)
			{
				return \$this->service->destroy(\$request->all());
			}

			/**
			* Normalize order field for all records (1 → N).
			*
			* @param Request \$request
			* @return array
			*
			* @throws Throwable
			*/
			public function updateOrder(Request \$request)
			{
				return \$this->service->updateOrder(\$request->all());
			}

			/**
			* Update resource status.
			*
			* @param Request \$request
			* @param string \$id
			* @return array
			*
			* @throws Throwable
			*/
			public function changeStatus(Request \$request, string \$id)
			{
				return \$this->service->changeStatus(\$request->all(), \$id);
			}
		}
		PHP;
	}

	private function requestStub($name, $type, $action): string
    {
		$namespace = "App\\Http\\Requests\\{$type}\\{$name}";
		$className = "{$action}{$name}Request";

		return <<<PHP
		<?php

		namespace {$namespace};

		use Illuminate\Contracts\Validation\ValidationRule;
		use Illuminate\Foundation\Http\FormRequest;

		class {$className} extends FormRequest
		{
			/**
			* Determine if the user is authorized to make this request.
			*/
			public function authorize(): bool
			{
				return false;
			}

			/**
			* Get the validation rules that apply to the request.
			*
			* @return array<string, ValidationRule|array|string>
			*/
			public function rules(): array
			{
				return [
					// 'name' => 'required',
					// 'description' => 'nullable',
				];
			}

			/**
			* @return array<string, string>
			*/
			public function messages(): array
			{
				return [
					// 'name.required' => 'Name is required',
				];
			}
		}
		PHP;
	}

	private function serviceStub(string $name, string $type): string
	{
		$namespace = "App\\Http\\Services\\{$type}";
		$repository = "App\\Http\\Repositories\\{$type}\\{$name}Repository";

		return <<<PHP
		<?php

		namespace {$namespace};

		use App\Base\BaseService;
		use App\Http\Helpers\LoggerHelper;
		use {$repository};
		use Illuminate\Support\Facades\DB;
		use Throwable;

		class {$name}Service extends BaseService
		{
			private LoggerHelper \$logger;
			public function __construct()
			{
				parent::__construct();
				\$this->logger = new LoggerHelper;
				\$this->logger->setFileName('{$name}Service');
			}

			public function repository(): string
			{
				return {$name}Repository::class;
			}

			/**
			* Get list
			*
			* @param array \$payload
			* @return mixed
			*/
			public function loadList(array \$payload): mixed
			{
				\$conditions = [];
				\$options = \$this->buildListOptions(\$payload, ['name', 'code']);
				return \$this->repository->list(\$conditions, [], \$options);
			}

			/**
			* Create or update record with transaction.
			*
			* @param array \$data Input data
			* @param string|int|null \$id
			* @return array
			*
			* @throws Throwable
			*/
			public function updateOrStore(array \$data, int|string|null \$id = null): array
			{
				DB::beginTransaction();
				try {
					\$data['id'] = \$id;
					\$channel = \$id ? '{$name}Update' : '{$name}Store';
					\$this->logger->setChannel(\$channel)->log('Param', \$data);
					\$data = \$this->repository->updateOrStore(\$data);
					DB::commit();
					return \$data;
				} catch (\Exception \$e) {
					DB::rollback();
					\$this->logger->setChannel(\$channel)->log('Error', [\$e->getMessage(), \$e->getFile(), \$e->getLine()]);
					throw \$e;
				}
			}

			/**
			* Delete multiple records by IDs.
			*
			* @param array \$payload Input data
			* @return array
			*
			* @throws Throwable
			*/
			public function destroy(array \$payload): array
			{
				\$arrIds = explode(',', \$payload['ids']);
				DB::beginTransaction();
				try {
					\$this->logger->setChannel('{$name}Delete')->log('Params', \$arrIds);
					foreach (\$arrIds as \$id) {
						\$this->repository->deleteChild(\$id);
					}
					DB::commit();
					return array('status' => true, 'message' => 'Deleted Successfully.');
				} catch (\Exception \$e) {
					DB::rollback();
					\$this->logger->setChannel('{$name}Delete')->log('Messages', ['Line:' => \$e->getLine(), 'Message:' => \$e->getMessage(), 'FileName:' => \$e->getFile()]);
					return array('status' => false, 'message' => 'Deleted Failed.!');
				}
			}

			/**
			* Normalize order field for all records (1 → N).
			*
			* @param array \$payload Input data
			* @return array
			*
			* @throws Throwable
			*/
			public function updateOrder(array \$payload): array
			{
				DB::beginTransaction();
				try {
					\$this->logger->setChannel('UpdateOrder')->log('Params', \$payload);
					\$data = \$this->repository->select('*')->orderBy('order')->get();
					\$i = 1;
					foreach (\$data as \$key => \$value) {
						\$value->update(['order' => \$i++]);
					}
					DB::commit();
					return array('status' => true, 'message' => 'Updated Successfully.!');
				} catch (\Exception \$e) {
					DB::rollback();
					\$this->logger->setChannel('UpdateOrder')->log('Message', ['Line:' => \$e->getLine(), 'Message:' => \$e->getMessage(), 'FileName:' => \$e->getFile()]);
					return array('status' => false, 'message' => 'Updated Failed.!');
				}
			}

			/**
			* Update record status by ID.
			*
			* @param array \$payload Input data
			* @param string|int \$id
			* @return array
			*
			* @throws Throwable
			*/
			public function changeStatus(array \$payload, string|int \$id): array
			{
				DB::beginTransaction();
				try {
					\$this->logger->setChannel('ChangeStatus')->log('Params', \$payload);
					\$this->repository->update(\$id, ['status' => \$payload['status']]);
					DB::commit();
					return array('status' => true, 'message' => 'Updated Successfully.!');
				} catch (\Exception \$e) {
					DB::rollback();
					\$this->logger->setChannel('ChangeStatus')->log('Message', ['Line:' => \$e->getLine(), 'Message:' => \$e->getMessage(), 'FileName:' => \$e->getFile()]);
					return array('status' => false, 'message' => 'Updated Failed.!');
				}
			}
		}
		PHP;
	}

	private function repositoryStub(string $name, string $type): string
	{
		$namespace = "App\\Http\\Repositories\\{$type}";
		$model = "App\\Models\\{$name}";

		return <<<PHP
		<?php

		namespace {$namespace};

		use App\Base\BaseRepository;
		use {$model};
		use Illuminate\Database\Eloquent\Model;
		use Illuminate\Database\Eloquent\ModelNotFoundException;

		class {$name}Repository extends BaseRepository
		{
			public function __construct()
			{
				parent::__construct();
			}

			public function model(): string
			{
				return {$name}::class;
			}

			/**
			* Create or update
			* @param array \$data Input data
			* @return Model
			* @throws ModelNotFoundException
			*/
			public function updateOrStore(array \$data): Model
			{
				if (hasValue(\$data, 'order')) {
					updateOrder(new \$this->model(), \$data);
				}

				\$id = \$data['id'] ?? null;

				\$columns = [
					'code',
					'name',
					'order',
				];

				\$payload = [];

				foreach (\$columns as \$column) {
					\$payload[\$column] = !isNullOrUnset(\$data, \$column) ? \$data[\$column] : null;
				}

				\$payload['status'] = hasValue(\$data, 'status') && \$data['status'] === 'on' ? 1 : 0;

				\$modelClass = \$this->model;

				return \$modelClass::query()->updateOrCreate(
					['id' => \$id ?? (string) \Str::uuid()],
					\$payload
				);
			}
		}
		PHP;
	}

	private function modelStub(string $name): string
	{
		return <<<PHP
		<?php

		namespace App\Models;

		use Illuminate\Database\Eloquent\Model;

		class {$name} extends Model
		{
			protected \$table = '{$this->tableName($name)}';
		}
		PHP;
	}

	private function tableName(string $name): string
	{
		return Str::snake(Str::pluralStudly($name));
	}
}

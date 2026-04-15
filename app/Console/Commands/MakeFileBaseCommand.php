<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[Signature('make:base {name} {--admin}')]
#[Description('Generate Controller, Service, Repository, Model, View')]
class MakeModuleCommand extends Command
{
	const ADMIN = 'Admin';
	const CLIENT = 'Client';

	public function handle(): int
	{
		$name = Str::studly($this->argument('name'));
		$isAdmin = $this->option('admin');

		$type = $isAdmin ? self::ADMIN : self::CLIENT;

		$this->createController($name, $type);
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

	private function createController($name, $type)
	{
		$path = app_path("Http/Controllers/{$type}/{$name}Controller.php");

		$this->put($path, $this->controllerStub($name, $type));
	}

	private function createService($name, $type)
	{
		$path = app_path("Http/Services/{$type}/{$name}Service.php");

		$this->put($path, $this->serviceStub($name, $type));
	}

	private function createRepository($name, $type)
	{
		$path = app_path("Http/Repositories/{$type}/{$name}Repository.php");

		$this->put($path, $this->repositoryStub($name, $type));
	}

	private function createModel($name)
	{
		$path = app_path("Models/{$name}.php");

		if (!File::exists($path)) {
			$this->put($path, $this->modelStub($name));
		}
	}

	private function createView($name, $type)
	{
		$lower = Str::lower($name);
		$plural = Str::plural($lower);

		$viewBase = $type === self::ADMIN ? "admin/pages/{$plural}" : "client/pages/{$plural}";

		$pathIndex = resource_path("views/{$viewBase}/index.blade.php");
		$this->put($pathIndex, "<h1>{$name}</h1>");

		if ($type === self::ADMIN) {
			$pathLoadList = resource_path("views/{$viewBase}/loadList.blade.php");
			$pathForm = resource_path("views/{$viewBase}/form.blade.php");

			$this->put($pathLoadList, "<h1>List {$name}</h1>");
			$this->put($pathForm, "<h1>Form {$name}</h1>");
		}
	}

	private function put($path, $content)
	{
		File::ensureDirectoryExists(dirname($path));
		File::put($path, $content);
	}

	// ========================
	// STUBS
	// ========================

	private function controllerStub($name, $type)
	{
		$namespace = "App\\Http\\Controllers\\{$type}";
		$service = "App\\Http\\Services\\{$type}\\{$name}Service";

		$lower = strtolower($name);
		$plural = \Str::plural($lower);

		$viewBase = $type === self::ADMIN ? "admin.pages.{$plural}" : "client.pages.{$plural}";

		return <<<PHP
		<?php

		namespace {$namespace};

		use App\Http\Controllers\Controller;
		use {$service};
		use Illuminate\Http\Request;

		class {$name}Controller extends Controller
		{
			public function __construct(private {$name}Service \$service) {}

			public function index()
			{
				\$result = [];
				return view('{$viewBase}.index', \$result);
			}

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

			public function create()
			{
				\$result = [
				'checked' => "checked=true",
				'order' => \$this->service->count() + 1,
				];
				return view('{$viewBase}.form', \$result);
			}

			public function store(Request \$request)
			{
				try {
					\$result = \$this->service->updateOrStore(\$request->all());
					return redirect(route('{$plural}.index'));
				} catch (\Exception \$e) {
					return array('status' => false, 'message' => \$e->getMessage());
				}
			}

			public function show(string \$id)
			{
				\$data = \$this->service->find(\$id);
				return view('{$viewBase}.show', compact('{\$data}'));
			}

			public function edit(string \$id)
			{
				\$result = [
					'data' => \$this->service->find(\$id),
					'checked' => "checked=true",
					'order' => \$this->service->count() + 1,
				];
				return view('{$viewBase}.form', \$result);
			}

			public function update(Request \$request, string \$id)
			{
				try {
					\$result = \$this->service->updateOrStore(\$request->all(), \$id);
					return redirect(route('{$plural}.index'));
				} catch (\Exception \$e) {
					return array('status' => false, 'message' => \$e->getMessage());
				}
			}

			public function destroy(Request \$request)
			{
				try {
					return \$this->service->destroy(\$request->all());
				} catch (\Exception \$e) {
					return array('status' => false, 'message' => \$e->getMessage());
				}
			}

			public function updateOrder(Request \$request)
			{
				try {
					return \$this->service->updateOrder(\$request->all());
				} catch (\Exception \$e) {
					return array('status' => false, 'message' => \$e->getMessage());
				}
			}

			public function changeStatus(Request \$request, string \$id)
			{
				try {
					\$result = \$this->service->changeStatus(\$request->all(), \$id);
					return \$result;
				} catch (\Exception \$e) {
					return array('status' => false, 'message' => \$e->getMessage());
				}
			}
		}
		PHP;
	}

	private function serviceStub($name, $type)
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

			class {$name}Service extends BaseService
			{
				private \$logger;
				public function __construct()
				{
					parent::__construct();
					\$this->logger = new LoggerHelper;
					\$this->logger->setFileName('{$name}Service');
				}

				public function repository()
				{
					return {$name}Repository::class;
				}

				public function loadList(array \$params)
				{
					\$conditions = [];
					\$options = [
						'page' => \$params['page'] ?? OFFSET,
						'limit' => \$params['limit'] ?? LIMIT,
						'orderBy' => [
							\$params['sort'] ?? 'order' => isset(\$params['sortType']) && \$params['sortType'] == 1 ? 'asc' : 'desc' ?? 'desc',
						],
						'keyword' => \$params['keyword'] ?? '',
						'search_field' => ['name', 'code']
					];
					return \$this->repository->list(\$conditions, ['facilities', 'specialty'], \$options);
				}

				public function updateOrStore(array \$data, int|string|null \$id = null)
				{
					DB::beginTransaction();
					try {
						\$data['id'] = \$id;
						\$this->logger->log('Param', \$data);
						\$data = \$this->repository->updateOrStore(\$data);
						DB::commit();
						return \$data;
					} catch (\Exception \$e) {
						DB::rollback();
						\$this->logger->log('Error', [\$e->getMessage(), \$e->getFile(), \$e->getLine()]);
						throw \$e->getMessage();
					}
				}

				public function destroy(array \$params): array
				{
					\$arrIds = explode(',', \$params['ids']);
					DB::beginTransaction();
					try {
						\$this->logger->setChannel('Delete')->log('Params', \$arrIds);
						foreach(\$arrIds as \$id){
							\$this->repository->deleteChild(\$id);
						}
						DB::commit();
						return array('status' => true, 'message' => 'Xóa thành công.');
					} catch (\Exception \$e) {
						DB::rollback();
						\$this->logger->setChannel('Delete')->log('Messages', ['Line:' => \$e->getLine(), 'Message:' => \$e->getMessage(), 'FileName:' => \$e->getFile()]);
						return array('status' => false, 'message' => 'Xóa thất bại!');
					}
				}

				public function updateOrder(array \$params): array
				{
					DB::beginTransaction();
					try {
						\$this->logger->setChannel('UpdateOrder')->log('Params', \$params);
						\$data = \$this->repository->select('*')->orderBy('order')->get();
						\$i = 1;
						foreach (\$data as \$key => \$value) {
							\$value->update(['order' => \$i++]);
						}
						DB::commit();
						return array('status' => true, 'message' => 'Cập nhật thành công!');
					} catch (\Exception \$e) {
						DB::rollback();
						\$this->logger->setChannel('UpdateOrder')->log('Message', ['Line:' => \$e->getLine(), 'Message:' => \$e->getMessage(), 'FileName:' => \$e->getFile()]);
						return array('status' => false, 'message' => 'Cập nhật thất bại!');
					}
				}

				public function changeStatus(array \$params): array
				{
					DB::beginTransaction();
					try {
						\$this->logger->setChannel('ChangeStatus')->log('Params', \$params);
						\$this->repository->update(\$params['id'], ['status' => \$params['status']]);
						DB::commit();
						return array('status' => true, 'message' => 'Cập nhật thành công!');
					} catch (\Exception \$e) {
						DB::rollback();
						\$this->logger->setChannel('ChangeStatus')->log('Message', ['Line:' => \$e->getLine(), 'Message:' => \$e->getMessage(), 'FileName:' => \$e->getFile()]);
						return array('status' => false, 'message' => 'Cập nhật thất bại!');
					}
				}
			}
		PHP;
	}

	private function repositoryStub($name, $type)
	{
		$namespace = "App\\Http\\Repositories\\{$type}";
		$model = "App\\Models\\{$name}";

		return <<<PHP
			<?php

			namespace {$namespace};

			use App\Base\BaseRepository;
			use {$model};

			class {$name}Repository extends BaseRepository
			{
				public function __construct()
				{
					parent::__construct();
				}

				public function model()
				{
					return {$name}::class;
				}

				public function updateOrStore({$name} \$data)
				{
					if (hasValue(\$data, 'order')) {
						updateOrder(new \$this->model(), \$data);
					}

					if (hasValue(\$data, 'id')) {
						\$sql = \$this->find(\$data['id']);
						\$sql->updated_at = now();
					} else {
						\$sql = new \$this->model();
						\$sql->id = (string)\Str::uuid();
						\$sql->created_at = now();
					}

					\$columns = ['code', 'name', 'order'];
					foreach (\$columns as \$column) {
						\$sql->{\$column} = !isNullOrUnset(\$data, \$column) ? \$data[\$column] : null;
					}
					\$sql->status = hasValue(\$data, 'status') && \$data['status'] === 'on' ? 1 : 0;
					\$sql->save();
					return \$sql;
				}
			}
		PHP;
	}

	private function modelStub($name)
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

	private function tableName($name)
	{
		return Str::snake(Str::pluralStudly($name));
	}
}

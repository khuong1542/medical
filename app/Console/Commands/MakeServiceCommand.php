<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[Signature('app:make-service {name}')]
#[Description('Generate Service class')]
class MakeServiceCommand extends Command
{
	/**
	 * Execute the console command.
	 */
	public function handle(): void
	{
		$name = trim($this->argument('name'));

		// Chuẩn hóa slash
		$name = str_replace('\\', '/', $name);

		// Explode Folder
		$segments = explode('/', $name);

		// Chuyển name thành StudlyCase
		$name = Str::studly(array_pop($segments));

		// Chuyển các folder thành StudlyCase
		$subFolder = implode('/', array_map(
			fn($item) => Str::studly($item),
			$segments
		));

		// Đường dẫn folder chứa file
		$directory = app_path(
			'Http/Services' .
				(!empty($subFolder) ? '/' . $subFolder : '')
		);

		// Đường dẫn đến file service
		$path = "{$directory}/{$name}Service.php";

		if (File::exists($path)) {
			$this->error("{$name}Service already exists.");

			return;
		}

		File::ensureDirectoryExists($directory);

		File::put($path, $this->getStub(
			$name,
			$subFolder
		));

		$this->info("Created: {$path}");
	}

	/**
	 * Get stub content.
	 */
	protected function getStub(
		string $name,
		string $subFolder
	): string {
		$namespace = 'App\\Http\\Services';
		$repository = 'App\\Http\\Repositories';

		if (!empty($subFolder)) {
			$namespace .= '\\' . str_replace('/', '\\', $subFolder);
			$repository .= '\\' . str_replace('/', '\\', $subFolder);
		}

		return <<<PHP
		<?php

		namespace {$namespace};

		use App\Base\BaseService;
		use App\Http\Helpers\LoggerHelper;
		use {$repository}\\{$name}Repository;

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
		}

		PHP;
	}
}

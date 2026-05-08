<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[Signature('make:repository {name}')]
#[Description('Generate Repository class')]
class MakeRepositoryCommand extends Command
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
			'Http/Repositories' .
				(!empty($subFolder) ? '/' . $subFolder : '')
		);

		// Đường dẫn đến file repository
		$path = "{$directory}/{$name}Repository.php";

		if (File::exists($path)) {
			$this->error("{$name}Repository already exists.");

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
		$namespace = 'App\\Http\\Repositories';
		$model = 'App\\Models';

		if (!empty($subFolder)) {
			$namespace .= '\\' . str_replace('/', '\\', $subFolder);
		}

		return <<<PHP
		<?php

		namespace {$namespace};

		use App\Base\BaseRepository;
		use {$model}\\{$name};

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
		}

		PHP;
	}
}

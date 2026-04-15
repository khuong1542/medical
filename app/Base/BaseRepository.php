<?php

namespace App\Base;

use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Base\Traits\ChunkTrait;
use App\Base\Traits\HasUpdateOrCreateTrait;
use App\Base\Traits\QueryBuilderTrait;
use App\Base\Traits\RelationTrait;
use App\Base\Traits\SearchTrait;
use App\Base\Traits\SoftDeleteTrait;

abstract class BaseRepository
{
	use QueryBuilderTrait;
	use SearchTrait;
	use HasUpdateOrCreateTrait;
	use SoftDeleteTrait;
	use RelationTrait;
	use ChunkTrait;
	/**
	 * @var App
	 */
	public $app;
	/**
	 * Cache columns trong request lifecycle
	 */
	protected array $tableColumns = [];
	/**
	 * Static cache theo class (optional pro)
	 */
	protected static array $columnsCache = [];
	/**
	 * @var Model
	 */
	protected Model $model;

	protected bool $strictMode = false;

	public function __construct()
	{
		$this->app = new App();
		$this->setModel();
	}

	/**
	 * Khai báo class model trong repository con.
	 *
	 * @return string
	 */
	abstract public function model();

	/**
	 * Bind model từ container.
	 *
	 * @throws Exception
	 * @return Model
	 */
	public function setModel()
	{
		$model = $this->app->make($this->model());
		if (!$model instanceof Model) {
			throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
		}

		return $this->model = $model;
	}

	/**
	 * Lấy danh sách columns của table từ Database.
	 *
	 * Có cache trong request lifecycle để tránh gọi Schema nhiều lần.
	 *
	 * Dùng cho:
	 * - Validate column tồn tại
	 * - Dynamic filter
	 * - Dynamic select
	 * - Sort safety
	 *
	 * Cache theo Repository class.
	 *
	 * @return array<int, string>
	 */
	protected function getTableColumns(): array
	{
		$class = static::class;

		if (!isset(self::$columnsCache[$class])) {
			self::$columnsCache[$class] = Schema::getColumnListing(
				$this->model->getTable()
			);
		}

		return self::$columnsCache[$class];
	}

	/**
	 * Tạo query builder mới.
	 *
	 * @return Builder
	 */
	public function query(): Builder
	{
		return $this->model->newQuery();
	}

	/**
	 * Lấy danh sách model (list / paginate).
	 *
	 * @param array $conditions Điều kiện
	 * @param array $relations Liên kết
	 * @param array $options Các điều kiện khác
	 * @param array $columns Cột cần select
	 *
	 * @return Collection|LengthAwarePaginator
	 */
	public function list(
		array $conditions = [],
		array $relations = [],
		array $options = [],
		array $columns = ['*'],
	): Collection|LengthAwarePaginator {
		$options = array_merge([
			'keyword'   => null,
			'search_field' => null,
			'limit'    => null,
			'orderBy'  => [],
		], $options);

		$query = $this->model->select($columns);

		if (!empty($relations)) {
			$query->with($relations);
		}

		$this->applyConditions($query, $conditions);
		$this->applySearch(
			$query,
			$options['keyword'],
			$options['search_field'],
		);
		$this->applyOrderBy($query, $options['orderBy'] ?? []);

		return $options['limit']
			? $query->paginate($options['limit'])
			: $query->get();
	}

	/**
	 * Find model theo ID.
	 *
	 * @param int|string $id
	 * @param array      $columns
	 * @param array|null $relations
	 * @return Model
	 */
	public function find(
		int|string $id,
		array $columns = ['*'],
		?array $relations = null
	): Model {
		$query = $this->model->select($columns);

		if ($relations) {
			$query->with($relations);
		}

		return $query->findOrFail($id);
	}

	/**
	 * Find model theo điều kiện.
	 *
	 * @param array      $conditions
	 * @param array      $columns
	 * @param array|null $relations
	 * @return Model|null
	 */
	public function findBy(
		array $conditions,
		array $columns = ['*'],
		?array $relations = null
	): ?Model {
		$query = $this->model->select($columns);

		if ($relations) {
			$query->with($relations);
		}

		$this->applyConditions($query, $conditions);

		return $query->first();
	}

	/**
	 * @return Collection<int, TModel>
	 */
	public function findMany(array $ids, ?array $relations = null, array $columns = ['*']): Collection
	{
		$query = $this->model
			->whereIn(
				$this->model->getKeyName(),
				$ids
			);
		if ($relations) {
			$query->with($relations);
		}
		return $query->get($columns);
	}

	/**
	 * Guard conditions khi strict mode bật.
	 *
	 * @param array $conditions
	 * @throws \LogicException
	 * @return void
	 */
	protected function guardConditions(array $conditions): void
	{
		if ($this->strictMode && empty($conditions)) {
			throw new \LogicException('Conditions cannot be empty in strict mode.');
		}
	}

	/**
	 * Lấy record đầu tiên theo conditions.
	 * Nếu không tồn tại → throw ModelNotFoundException.
	 *
	 * Ví dụ:
	 * $user = $repo->firstOrFailBy([
	 *     'email' => 'admin@gmail.com'
	 * ]);
	 *
	 * @param  array<string, mixed>  $conditions
	 * @return Model
	 *
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function firstOrFailBy(array $conditions): Model
	{
		$query = $this->query();
		$this->applyConditions($query, $conditions);

		return $query->firstOrFail();
	}

	/**
	 * Tạo mới model.
	 * Auto UUID nếu model không increment.
	 *
	 * @param array $data
	 * @return Model
	 */
	public function create($data = [])
	{
		if (!$this->model->getIncrementing()) {
			$id = $this->model->getKeyName();
			if (!isset($data[$id]) && $id) {
				$data[$id] = (string)Str::uuid();
			}
		}

		return $this->model->create($data);
	}

	/**
	 * First or create.
	 *
	 * @param array $conditions
	 * @param array $data
	 * @return Model
	 */
	public function firstOrCreate(array $conditions, array $data = []): Model
	{
		return $this->model->firstOrCreate($conditions, $data);
	}

	/**
	 * Update theo ID.
	 *
	 * @param int|string $id
	 * @param array      $data
	 * @return bool
	 */
	public function update(int|string $id, array $data): bool
	{
		$model = $this->find($id);
		return $model ? $model->update($data) : false;
	}

	/**
	 * Tăng giá trị field theo ID.
	 *
	 * @param int|string $id
	 * @param string     $field
	 * @param int        $value
	 *
	 * @return bool
	 */
	public function increment(int|string $id, string $field, int $value = 1): bool
	{
		$model = $this->find($id);
		return $model ? $model->increment($field, $value) : false;
	}

	/**
	 * Giảm giá trị field theo ID.
	 *
	 * @param int|string $id
	 * @param string     $field
	 * @param int        $value
	 *
	 * @return bool
	 */
	public function decrement(int|string $id, string $field, int $value = 1): bool
	{
		$model = $this->find($id);
		return $model ? $model->decrement($field, $value) : false;
	}

	/**
	 * Xoá mềm 1 record theo ID.
	 *
	 * @param int|string $id
	 *
	 * @return bool
	 */
	public function delete(int|string $id): bool
	{
		return (bool) $this->find($id)->delete();
	}

	/**
	 * Restore model đã soft delete theo ID.
	 *
	 * @param int|string $id
	 *
	 * @return bool
	 */
	public function restore(int|string $id): bool
	{
		if (!$this->hasSoftDeletes()) {
			return false;
		}

		return (bool) $this->model
			->withTrashed()
			->findOrFail($id)
			->restore();
	}

	/* ==================================================
     | RELATION / PIVOT
     ================================================== */

	/**
	 * Đồng bộ quan hệ many-to-many mà KHÔNG detach dữ liệu cũ.
	 *
	 * Khác với sync():
	 * - sync() → xoá những ID không nằm trong danh sách mới
	 * - syncWithoutDetaching() → chỉ thêm mới, không xoá cũ
	 *
	 * Ví dụ:
	 * $repo->syncWithoutDetaching($user, 'roles', [1, 2]);
	 *
	 * @param  Model   $model     Model cha
	 * @param  string  $relation  Tên quan hệ (belongsToMany)
	 * @param  array   $ids       Danh sách ID cần sync
	 *
	 * @return array  Kết quả sync (attached / updated / etc.)
	 */
	public function syncWithoutDetaching(
		Model $model,
		string $relation,
		array $ids
	): array {
		return $model->{$relation}()
			->syncWithoutDetaching($ids);
	}

	/**
	 * Cursor iterator.
	 *
	 * Dùng khi:
	 * - Export CSV 1 triệu dòng
	 * - Rebuild search index
	 * - Send email hàng loạt
	 * - Data migration
	 * - Queue processing
	 *
	 * @return \Illuminate\Support\LazyCollection
	 */
	public function cursor()
	{
		return $this->model
			->newQuery()
			->cursor();
	}

	/**
	 * Toggle pivot relation.
	 *
	 * Attach nếu chưa có, detach nếu đã có.
	 *
	 * @param Model  $model
	 * @param string $relation
	 * @param array  $ids
	 *
	 * @return array
	 */
	public function toggle(
		Model $model,
		string $relation,
		array $ids
	): array {
		return $model->{$relation}()
			->toggle($ids);
	}

	/**
	 * Detach pivot IDs.
	 *
	 * @param TModel $model
	 * @param string $relation
	 * @param array  $ids
	 *
	 * @return void
	 */
	public function detachMany(Model $model, string $relation, array $ids = []): void
	{
		$model->{$relation}()->detach($ids);
	}

	/* ==================================================
     | UTILITIES
     ================================================== */

	/**
	 * Kiểm tra tồn tại record theo điều kiện.
	 *
	 * @param array<string, mixed> $conditions
	 *
	 * @return bool
	 */
	public function exists(array $conditions): bool
	{
		$query = $this->query();
		$this->applyConditions($query, $conditions);

		return $query->exists();
	}

	public function existsBy(array $conditions): bool
	{
		return $this->query()
			->where($conditions)
			->exists();
	}

	/**
	 * Đếm record theo điều kiện.
	 *
	 * @param array<string, mixed> $conditions
	 *
	 * @return int
	 */
	public function count(array $conditions = []): int
	{
		$query = $this->query();
		$this->applyConditions($query, $conditions);
		return $query->count();
	}

	public function countBy(array $conditions = []): int
	{
		return $this->query()
			->where($conditions)
			->count();
	}

	/**
	 * Tính tổng theo field.
	 *
	 * @param string $field
	 *
	 * @return int|float
	 */
	public function sum($field)
	{
		return $this->model->sum($field);
	}

	/**
	 * Remember query result.
	 *
	 * @param string   $key
	 * @param int      $ttl
	 * @param callable $callback
	 *
	 * @return mixed
	 */
	public function remember(
		string $key,
		int $ttl,
		callable $callback
	) {
		return Cache::remember($key, $ttl, $callback);
	}
}

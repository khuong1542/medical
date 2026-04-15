<?php

namespace App\Base;

use Illuminate\Container\Container as App;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
	/**
	 * @var App
	 */
	private $app;

	/**
	 * @var Repository
	 */
	protected $repository;

	public function __construct()
	{
		$this->app = new App();
		$this->setRepository();
	}

	/**
	 * Khai báo repository class trong service con.
	 *
	 * @return class-string<TRepository>
	 */
	abstract public function repository();

	/**
	 * @return Repository
	 */
	public function setRepository(): void
	{
		$repository = $this->app->make($this->repository());
		if (!$repository instanceof BaseRepository) {
			throw new Exception("Class {$this->repository()} must be an instance of App\Base\BaseRepository");
		}

		$this->repository = $repository;
	}

	/**
	 * Lấy danh sách model (list / paginate).
	 *
	 * @param array $conditions
	 * @param array $relations
	 * @param array $options
	 * @param array $columns
	 *
	 * @return Collection<int, TModel>|LengthAwarePaginator<TModel>
	 */
	public function list(
		array $conditions = [],
		array $relations = [],
		array $options = [],
		array $columns = ['*'],
	): Collection|LengthAwarePaginator {
		return $this->repository->list(
			$conditions,
			$relations,
			$options,
			$columns,
		);
	}

	/**
	 * Find model theo ID.
	 *
	 * @param int|string $id
	 * @param array      $columns
	 * @param array|null $relations
	 *
	 * @return TModel
	 */
	public function find(
		int|string $id,
		array $columns = ['*'],
		?array $relations = null
	): Model {
		return $this->repository->find($id, $columns, $relations);
	}

	/**
	 * Find model theo điều kiện.
	 *
	 * @param array      $conditions
	 * @param array      $columns
	 * @param array|null $relations
	 *
	 * @return TModel|null
	 */
	public function findBy(
		array $conditions,
		array $columns = ['*'],
		?array $relations = null
	): ?Model {
		return $this->repository->findBy($conditions, $columns, $relations);
	}

	/**
	 * Kiểm tra tồn tại record.
	 *
	 * @param array $conditions
	 * @return bool
	 */
	public function exists(array $conditions): bool
	{
		return $this->repository->exists($conditions);
	}

	/**
	 * Đếm record.
	 *
	 * @param array $conditions
	 * @return int
	 */
	public function count(array $conditions = []): int
	{
		return $this->repository->count($conditions);
	}

	/**
	 * Tạo mới model.
	 *
	 * @param array $data
	 *
	 * @return TModel
	 */
	public function create(array $data): Model
	{
		return $this->repository->create($data);
	}

	/**
	 * Insert nhiều record.
	 *
	 * @param array $rows
	 * @return bool
	 */
	public function createMany(array $rows): bool
	{
		return $this->repository->createMany($rows);
	}

	/**
	 * Update theo ID.
	 *
	 * @param int|string $id
	 * @param array      $data
	 *
	 * @return bool
	 */
	public function update(int|string $id, array $data): bool
	{
		return $this->repository->update($id, $data);
	}

	/**
	 * Update theo điều kiện.
	 *
	 * @param array $conditions
	 * @param array $data
	 *
	 * @return int
	 */
	public function updateWhere(array $conditions, array $data): int
	{
		return $this->repository->updateWhere($conditions, $data);
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
		return $this->repository->increment($id, $field, $value);
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
		return $this->repository->decrement($id, $field, $value);
	}

	/**
	 * Xoá mềm theo ID.
	 *
	 * @param int|string $id
	 * @return bool
	 */
	public function delete(int|string $id): bool
	{
		return $this->repository->delete($id);
	}

	/**
	 * Xoá theo điều kiện.
	 *
	 * @param array $conditions
	 * @return int
	 */
	public function deleteWhere(array $conditions): int
	{
		return $this->repository->deleteWhere($conditions);
	}

	/**
	 * Xoá nhiều record.
	 *
	 * @param array $ids
	 * @return int
	 */
	public function deleteMany(array $ids): int
	{
		return $this->repository->deleteMany($ids);
	}

	/**
	 * Restore soft delete.
	 *
	 * @param int|string $id
	 * @return bool
	 */
	public function restore(int|string $id): bool
	{
		return $this->repository->restore($id);
	}

	/**
	 * Force delete.
	 *
	 * @param int|string $id
	 * @return bool
	 */
	public function forceDelete(int|string $id): bool
	{
		return $this->repository->forceDelete($id);
	}

	/**
	 * Sync pivot relation.
	 *
	 * @param TModel $model
	 * @param string $relation
	 * @param array  $ids
	 * @param bool   $detaching
	 *
	 * @return array
	 */
	public function syncRelation(
		Model $model,
		string $relation,
		array $ids,
		bool $detaching = true
	): array {
		return $this->repository->sync($model, $relation, $ids, $detaching);
	}

	/**
	 * Attach nhiều pivot IDs.
	 *
	 * @param TModel $model
	 * @param string $relation
	 * @param array  $ids
	 *
	 * @return void
	 */
	public function attachMany(Model $model, string $relation, array $ids): void
	{
		$this->repository->attachMany($model, $relation, $ids);
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
		$this->repository->detachMany($model, $relation, $ids);
	}
}

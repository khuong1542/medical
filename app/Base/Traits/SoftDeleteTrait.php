<?php

namespace App\Base\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

trait SoftDeleteTrait
{
	/**
	 * Kiểm tra Model có sử dụng SoftDeletes trait hay không.
	 *
	 * Dùng để quyết định:
	 * - Có thể forceDelete / restore không
	 * - Có thể withTrashed / onlyTrashed không
	 *
	 * @return bool
	 */
	protected function hasSoftDeletes(): bool
	{
		return in_array(
			SoftDeletes::class,
			class_uses_recursive($this->model)
		);
	}

	protected function applySoftDeleteScope(
		Builder $query,
		string $scope = 'default'
	): Builder {

		if (!$this->hasSoftDeletes()) {
			return $query;
		}

		return match ($scope) {
			'with' => $query->withTrashed(),
			'only' => $query->onlyTrashed(),
			default => $query->withoutTrashed(),
		};
	}

	/**
	 * Xoá record theo danh sách điều kiện.
	 *
	 * Ví dụ:
	 * [
	 *   'status' => 0,
	 *   'created_at' => ['<', '2026-01-01']
	 * ]
	 *
	 * ⚠ Nếu strictMode = true → bắt buộc phải có conditions
	 * để tránh xoá toàn bảng ngoài ý muốn.
	 *
	 * @param  array<string, mixed>  $conditions
	 * @return int  Số record bị xoá
	 */
	public function deleteWhere(array $conditions): int
	{
		$this->guardConditions($conditions);

		$query = $this->model->newQuery();
		$this->applyConditions($query, $conditions);

		return $query->delete();
	}

	/**
	 * Xoá nhiều record theo danh sách ID.
	 *
	 * @param array<int|string> $ids
	 *
	 * @return int
	 */
	public function deleteMany(array $ids): int
	{
		$key = $this->model->getKeyName();
		return $this->model->whereIn($key, $ids)->delete();
	}

	/**
	 * Force delete (xoá cứng).
	 *
	 * @param int|string $id
	 *
	 * @return bool
	 */
	public function forceDelete(int|string $id): bool
	{
		if (!$this->hasSoftDeletes()) {
			return false;
		}

		return (bool) $this->model
			->withTrashed()
			->findOrFail($id)
			->forceDelete();
	}

	/**
	 * Force delete nhiều record theo danh sách ID.
	 *
	 * @param  array<int|string>  $ids
	 * @return int  Số record bị xoá vĩnh viễn
	 */
	public function forceDeleteMany(array $ids): int
	{
		if (!$this->hasSoftDeletes()) {
			return 0;
		}

		return $this->model
			->withTrashed()
			->whereIn($this->model->getKeyName(), $ids)
			->forceDelete();
	}

	/**
	 * Bao gồm cả record đã soft delete trong query.
	 *
	 * Dùng khi cần:
	 * - Xem lịch sử xoá
	 * - Restore
	 * - Force delete
	 *
	 * @return Builder
	 */
	public function withTrashed(): Builder
	{
		if (!$this->hasSoftDeletes()) {
			return $this->query();
		}

		return $this->query()->withTrashed();
	}

	/**
	 * Chỉ lấy record đã soft delete.
	 *
	 * @return Builder
	 */
	public function onlyTrashed(): Builder
	{
		if (!$this->hasSoftDeletes()) {
			return $this->query();
		}
		return $this->model->onlyTrashed();
	}

	/**
	 * Loại bỏ record đã soft delete khỏi query.
	 *
	 * (Mặc định Laravel đã exclude, nhưng expose ra để chain rõ ràng)
	 *
	 * @return Builder
	 */
	public function withoutTrashed(): Builder
	{
		if (!$this->hasSoftDeletes()) {
			return $this->query();
		}
		return $this->model->withoutTrashed();
	}

	/**
	 * Restore theo điều kiện.
	 *
	 * ⚠ Nếu strictMode = true → bắt buộc có conditions.
	 *
	 * @param array<string, mixed> $conditions
	 *
	 * @return int  Số record được restore
	 */
	public function restoreWhere(array $conditions): int
	{
		$this->guardConditions($conditions);

		$query = $this->model->withTrashed();
		$this->applyConditions($query, $conditions);

		return $query->restore();
	}

	/**
	 * Khôi phục (restore) nhiều record đã soft delete theo danh sách ID.
	 *
	 * Chỉ hoạt động khi Model sử dụng SoftDeletes trait.
	 * Nếu Model không dùng SoftDeletes → return 0.
	 *
	 * Ví dụ:
	 * $repo->restoreMany([1, 2, 3]);
	 *
	 * @param  array<int|string>  $ids  Danh sách primary key
	 * @return int  Số record được restore thành công
	 */
	public function restoreMany(array $ids): int
	{
		if (!$this->hasSoftDeletes()) {
			return 0;
		}

		return $this->model
			->withTrashed()
			->whereIn($this->model->getKeyName(), $ids)
			->restore();
	}
}

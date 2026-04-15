<?php

namespace App\Base\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasUpdateOrCreateTrait
{
	/**
	 * Insert nhiều record.
	 *
	 * @param array $rows
	 * @return bool
	 */
	public function createMany(array $rows): bool
	{
		return $this->model->insert($rows);
	}

	/**
	 * Update model theo điều kiện.
	 *
	 * ⚠ Nếu strictMode = true → bắt buộc có conditions.
	 *
	 * @param array<string, mixed> $conditions
	 * @param array<string, mixed> $data
	 *
	 * @return int  Số record bị update
	 */
	public function updateWhere(array $conditions, array $data): int
	{
		$this->guardConditions($conditions);

		$query = $this->query();
		$this->applyConditions($query, $conditions);

		return $query->update($data);
	}

	/**
	 * Update hoặc create model.
	 *
	 * Dùng khi:
	 * - CRUD bình thường
	 * - Cần model trả về
	 * - Cần observer
	 * - Cần event
	 * - Cần fillable/cast
	 * - Logic business attach model
	 *
	 * @param array $conditions
	 * @param array $data
	 *
	 * @return TModel
	 */
	public function updateOrCreate(
		array $conditions,
		array $data
	): Model {
		return $this->model->updateOrCreate(
			$conditions,
			$data
		);
	}

	/**
	 * Cập nhật/Thêm bản ghi.
	 *
	 * Dùng khi:
	 * - Import Excel
	 * - Sync API ngoài
	 * - Seed data lớn
	 * - ETL pipeline
	 * - Batch job
	 * - Queue processing
	 *
	 * @param array        $rows
	 * @param array|string $uniqueBy
	 * @param array        $updateColumns
	 * @return int
	 */
	public function upsert(
		array $rows,
		array|string $uniqueBy,
		array $updateColumns = []
	): int {
		return $this->model->upsert($rows, $uniqueBy, $updateColumns);
	}
}

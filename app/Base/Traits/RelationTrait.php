<?php

namespace App\Base\Traits;

use Illuminate\Database\Eloquent\Model;

trait RelationTrait
{
	/**
	 * Đồng bộ pivot relation.
	 *
	 * @param TModel $model
	 * @param string $relation  Tên relation method
	 * @param array  $ids       Danh sách ID
	 * @param bool   $detaching Có detach ID cũ không
	 *
	 * @return array{
	 *   attached: array,
	 *   detached: array,
	 *   updated: array
	 * }
	 */
	public function sync(
		Model $model,
		string $relation,
		array $ids,
		bool $detaching = true
	): array {
		return $model->{$relation}()->sync($ids, $detaching);
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
	public function attachMany(
		Model $model,
		string $relation,
		array $ids
	): void {
		$model->{$relation}()->attach($ids);
	}
}

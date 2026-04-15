<?php

namespace App\Base\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchTrait
{
	/**
	 * Apply search keyword theo nhiều field.
	 *
	 * @param Builder     $query
	 * @param string|null $keyword
	 * @param array       $fields
	 * @return void
	 */
	protected function applySearch(
		Builder $query,
		?string $keyword,
		?array $fields = null
	): Builder {

		if (!$keyword) {
			return $query;
		}

		$fields ??= $this->getSearchableColumns();

		if (empty($fields)) {
			return $query;
		}

		$query->where(function (Builder $q) use ($keyword, $fields) {

			foreach ($fields as $field) {

				// =========================
				// RELATION SEARCH
				// =========================
				if (str_contains($field, '.')) {

					$parts = explode('.', $field);
					$column = array_pop($parts);
					$relationPath = implode('.', $parts);

					$q->orWhereHas($relationPath, function (Builder $rq) use ($column, $keyword) {
						$rq->where($column, 'LIKE', "%{$keyword}%");
					});

					continue;
				}

				// =========================
				// NORMAL COLUMN SEARCH
				// =========================
				$q->orWhere($field, 'LIKE', "%{$keyword}%");
			}
		});

		return $query;
	}

	/**
	 * Lấy danh sách cột cho phép search từ Model.
	 *
	 * Model cần khai báo:
	 * protected array $searchable = ['name', 'email'];
	 *
	 * Nếu không khai báo → return [].
	 *
	 * Dùng trong applySearch().
	 *
	 * @return array<int, string>
	 */
	protected function getSearchableColumns(): array
	{
		if (!property_exists($this->model, 'searchable')) {
			return [];
		}

		return $this->model->searchable ?? [];
	}
}

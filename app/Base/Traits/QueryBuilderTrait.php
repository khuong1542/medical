<?php

namespace App\Base\Traits;

use Illuminate\Database\Eloquent\Builder;

trait QueryBuilderTrait
{
	/**
	 * Apply where conditions động cho query.
	 *
	 * Supported formats:
	 *  ['status' => 1]
	 *  ['age' => ['>=', 18]]
	 *  ['id' => ['in', [1,2,3]]]
	 *
	 * @param Builder $query
	 * @param array   $conditions
	 * @return Builder
	 */
	protected function applyConditions(Builder $query, array $conditions = [], string $boolean = 'and'): Builder
	{
		foreach ($conditions as $field => $value) {
			if (in_array($field, ['and', 'or'], true) && is_array($value)) {
				$query->where(function ($q) use ($value, $field) {
					$this->applyConditions(
						$q,
						$value,
						$field === 'or' ? 'or' : 'and'
					);
				});
				continue;
			}

			if (is_int($field) && is_array($value)) {
				$this->applyConditions($query, $value, $boolean);
				continue;
			}
			if (is_array($value)) {
				[$operator, $val] = $value;

				$operator = strtolower($operator);

				$allowedOperators = [
					'=',
					'!=',
					'>',
					'>=',
					'<',
					'<=',
					'like',
					'in',
					'not_in',
					'between',
					'null',
					'not_null',
					'date',
					'month',
					'year',
					'day',
					'time',
				];

				if (!in_array($operator, $allowedOperators, true)) {
					continue;
				}

				$this->applyOperator(
					$query,
					$field,
					$operator,
					$val,
					$boolean
				);

				continue;
			}

			$boolean === 'or'
				? $query->orWhere($field, $value)
				: $query->where($field, $value);
		}
		return $query;
	}

	/**
	 * Áp dụng toán tử where động vào query.
	 *
	 * Support:
	 * - Basic: =, >, <, >=, <=, !=
	 * - like
	 * - in / not_in
	 * - between
	 * - null / not_null
	 * - date / month / year / day / time
	 *
	 * Có hỗ trợ boolean:
	 * - and (default)
	 * - or
	 *
	 * Ví dụ conditions:
	 * [
	 *   'status' => ['=', 1],
	 *   'id' => ['in', [1,2,3]],
	 *   'created_at' => ['date', '2026-01-01']
	 * ]
	 *
	 * @param  Builder  $query
	 * @param  string   $field
	 * @param  string   $operator
	 * @param  mixed    $val
	 * @param  string   $boolean   and|or
	 *
	 * @return void
	 */
	protected function applyOperator(
		Builder $query,
		string $field,
		string $operator,
		mixed $val,
		string $boolean = 'and'
	): void {

		$method = $boolean === 'or'
			? 'orWhere'
			: 'where';

		match (strtolower($operator)) {
			'in' => $query->{$boolean === 'or' ? 'orWhereIn' : 'whereIn'}($field, $val),
			'not_in' => $query->{$boolean === 'or' ? 'orWhereNotIn' : 'whereNotIn'}($field, $val),
			'between' => $query->{$boolean === 'or' ? 'orWhereBetween' : 'whereBetween'}($field, $val),
			'null' => $query->{$boolean === 'or' ? 'orWhereNull' : 'whereNull'}($field),
			'not_null' => $query->{$boolean === 'or' ? 'orWhereNotNull' : 'whereNotNull'}($field),
			'like' => $query->$method($field, 'LIKE', "%{$val}%"),
			'date'  => $query->{$boolean === 'or' ? 'orWhereDate' : 'whereDate'}($field, $val),
			'month' => $query->{$boolean === 'or' ? 'orWhereMonth' : 'whereMonth'}($field, $val),
			'year'  => $query->{$boolean === 'or' ? 'orWhereYear' : 'whereYear'}($field, $val),
			'day'   => $query->{$boolean === 'or' ? 'orWhereDay' : 'whereDay'}($field, $val),
			'time'  => $query->{$boolean === 'or' ? 'orWhereTime' : 'whereTime'}($field, $val),
			default => $query->$method($field, $operator, $val),
		};
	}

	/**
	 * Apply order by cho query.
	 * Chỉ cho phép sort các field khai báo trong $sortable của Model.
	 *
	 * @param Builder        $query
	 * @param array|string  $orderBy
	 * @return Builder
	 */
	protected function applyOrderBy(
		Builder $query,
		array|string $orderBy
	): Builder {
		// Nếu model không định nghĩa sortable → bỏ qua
		if (!property_exists($this->model, 'sortable')) {
			return $query;
		}

		$sortable = $this->model->sortable;
		$columns  = $this->getTableColumns();

		$applySort = function (
			Builder $query,
			string $field,
			string $direction = 'asc'
		) use ($sortable, $columns) {
			// Check sortable + tồn tại DB
			if (
				in_array($field, $sortable, true) &&
				in_array($field, $columns, true)
			) {
				$query->orderBy(
					$field,
					strtolower($direction) === 'desc' ? 'desc' : 'asc'
				);
			}
		};

		if (is_array($orderBy)) {
			foreach ($orderBy as $field => $direction) {
				$applySort($query, $field, $direction);
			}
		} else {
			$applySort($query, $orderBy);
		}

		return $query;
	}
}

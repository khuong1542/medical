<?php

// Trạng thái hoạt động
const INACTIVE = 0;
const ACTIVE = 1;
// Ca làm đêm
const IS_NIGHT_SHIFT_INACTIVE = 0;
const IS_NIGHT_SHIFT_ACTIVE = 1;
// Nghỉ hưởng lương/không lương
const IS_UNPAID_LEAVE = 0;
const IS_PAID_LEAVE = 1;
// Pagination
const OFFSET = 1;
const LIMIT = 15;

// Kiểm tra là không tồn tại hoặc null
if (!function_exists('isNullOrUnset')) {
	function isNullOrUnset($params, $column): bool
	{
		return !isset($params[$column]) || $params[$column] === null;
	}
}

// Kiểm tra là tồn tại và khác null, '', 0
if (!function_exists('hasValue')) {
	function hasValue($data, $column): bool
	{
		return isset($data[$column]) && !empty($data[$column]);
	}
}

// Cập nhật số thứ tự
if (!function_exists('updateOrder')) {
	function updateOrder($model, $data)
	{
		$query = $model->where('order', '>=', $data['order']);
		if (hasValue($data, 'id')) {
			$query = $query->where('id', '<>', $data['id']);
		}
		$query = $query->orderBy('order')->get();
		$i = $data['order'];
		foreach ($query as $key => $value) {
			$value->update(['order' => ++$i]);
		}
	}
}

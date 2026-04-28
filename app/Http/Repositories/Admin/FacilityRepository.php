<?php

namespace App\Http\Repositories\Admin;

use App\Base\BaseRepository;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FacilityRepository extends BaseRepository
{
	public function __construct()
	{
		parent::__construct();
	}

	public function model(): string
	{
		return Facility::class;
	}

	/**
	* Create or update
	* @param array $data Input data
	* @return Model
	* @throws ModelNotFoundException
	*/
	public function updateOrStore(array $data): Model
	{
		if (hasValue($data, 'order')) {
			updateOrder(new $this->model(), $data);
		}

		$id = $data['id'] ?? null;

		$columns = [
			'code',
			'name',
			'order',
		];

		$payload = [];

		foreach ($columns as $column) {
			$payload[$column] = !isNullOrUnset($data, $column) ? $data[$column] : null;
		}

		$payload['status'] = hasValue($data, 'status') && $data['status'] === 'on' ? 1 : 0;

		$modelClass = $this->model;

		return $modelClass::query()->updateOrCreate(
			['id' => $id ?? (string) \Str::uuid()],
			$payload
		);
	}
}
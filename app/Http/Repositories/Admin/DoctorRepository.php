<?php

namespace App\Http\Repositories\Admin;

use App\Base\BaseRepository;
use App\Models\Doctor;

class DoctorRepository extends BaseRepository
{
	public function __construct()
	{
		parent::__construct();
	}

	public function model(): string
	{
		return Doctor::class;
	}

	public function updateOrStore(array $data): \Illuminate\Database\Eloquent\Model
	{
		if (hasValue($data, 'order')) {
			updateOrder(new $this->model(), $data);
		}

		$id = $data['id'] ?? null;

		$columns = [
			'facility_id',
			'specialty_id',
			'code',
			'name',
			'email',
			'phone',
			'experience_years',
			'description',
			'order',
		];

		$payload = [];

		foreach ($columns as $column) {
			$payload[$column] = !isNullOrUnset($data, $column) ? $data[$column] : null;
		}

		$payload['status'] = hasValue($data, 'status') && $data['status'] === 'on' ? 1 : 0;

		if (hasValue($data, 'images')) {
			$payload['images'] = $data['images'];
		}

		$modelClass = $this->model;

		return $modelClass::query()->updateOrCreate(
			['id' => $id ?? (string) \Str::uuid()],
			$payload
		);
	}
}

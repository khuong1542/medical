<?php

namespace App\Http\Repositories;

use App\Base\BaseRepository;
use App\Models\Doctor;

class DoctorRepository extends BaseRepository
{
	public function __construct()
	{
		parent::__construct();
	}

	public function model()
	{
		return Doctor::class;
	}

	public function updateOrStore($data)
	{
		if (hasValue($data, 'order')) {
			updateOrder(new $this->model(), $data);
		}

		if (hasValue($data, 'id')) {
			$sql = $this->find($data['id']);
			$sql->updated_at = now();
		} else {
			$sql = new $this->model();
			$sql->id = (string)\Str::uuid();
			$sql->created_at = now();
		}

		$columns = ['facility_id', 'specialty_id', 'code', 'name', 'email', 'phone', 'experience_years', 'description', 'order'];
		foreach ($columns as $column) {
			$sql->{$column} = !isNullOrUnset($data, $column) ? $data[$column] : null;
		}
		$sql->status = hasValue($data, 'status') && $data['status'] === 'on' ? 1 : 0;
		$sql->images = hasValue($data, 'images') ? $data['images'] : $sql->images;
		$sql->save();
		return $sql;
	}
}

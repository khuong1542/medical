	<?php

	namespace App\Http\Repositories\Admin;

	use App\Base\BaseRepository;
	use App\Models\Facility;

	class FacilityRepository extends BaseRepository
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function model()
		{
			return Facility::class;
		}

		public function updateOrStore(Facility $data)
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

			$columns = ['code', 'name', 'order'];
			foreach ($columns as $column) {
				$sql->{$column} = !isNullOrUnset($data, $column) ? $data[$column] : null;
			}
			$sql->status = hasValue($data, 'status') && $data['status'] === 'on' ? 1 : 0;
			$sql->save();
			return $sql;
		}
	}
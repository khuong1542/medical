<?php

namespace App\Http\Repositories\Client;

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
}
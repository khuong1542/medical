<?php

namespace App\Http\Services\Client;

use App\Base\BaseService;
use App\Enums\FacilityEnum;
use App\Http\Repositories\Client\DoctorRepository;
use Throwable;

class DoctorService extends BaseService
{
	public function __construct()
	{
		parent::__construct();
	}

	public function repository(): string
	{
		return DoctorRepository::class;
	}

	public function index(array $payload)
	{
		$conditions = [];
		$payload['orderBy'] = [
			'order' => 'desc',
		];
		$options = $this->buildListOptions($payload, ['name', 'code']);
		return $this->repository->list($conditions, ['facilities', 'specialty'], $options);
	}

	public function getFacility() {
		return FacilityEnum::cases();
	}
}

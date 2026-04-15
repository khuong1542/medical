<?php

namespace App\Http\Services\Admin;

use App\Base\BaseService;
use App\Http\Helpers\FileHelper;
use App\Http\Helpers\LoggerHelper;
use App\Http\Repositories\Admin\DoctorRepository;

class DoctorService extends BaseService
{
	private $logger;
	public function __construct()
	{
		parent::__construct();
		$this->logger = new LoggerHelper;
		$this->logger->setFileName('DoctorService');
	}

	public function repository()
	{
		return DoctorRepository::class;
	}

	public function loadList(array $params)
	{
		$conditions = [];
		$options = [
			'page' => $params['page'] ?? OFFSET,
			'limit' => $params['limit'] ?? LIMIT,
			'orderBy' => [
				$params['sort'] ?? 'order' => isset($params['sortType']) && $params['sortType'] == 1 ? 'asc' : 'desc' ?? 'desc',
			],
			'keyword' => $params['keyword'] ?? '',
			'search_field' => ['name', 'code']
		];
		return $this->repository->list($conditions, ['facilities', 'specialty'], $options);
	}

	public function store($data, $id = null)
	{
		try {
			if (isset($_FILES['images']) && $_FILES['images']['name'] !== '') {
				$images = FileHelper::upload($_FILES['images'], 'attach-file/doctors');
				$data['images'] = json_encode($images);
			}
			$data['id'] = $id;
			$this->logger->log('Param', $data);
			$data = $this->repository->updateOrStore($data);
			return $data;
		} catch (\Exception $e) {
			$this->logger->log('Error', [$e->getMessage(), $e->getFile(), $e->getLine()]);
			throw $e->getMessage();
		}
	}

	/**
	 * Cập nhật trạng thái
	 * @param $data Dữ liệu truyền vào
	 * @return array
	 */
	public function changeStatus($data, $id): array
	{
		try {
			$this->logger->setChannel('ChangeStatus')->log('Params', $data);
			$this->repository->update($id, ['status' => $data['status']]);
			return array('status' => true, 'message' => 'Cập nhật thành công!');
		} catch (\Exception $e) {
			$this->logger->setChannel('ChangeStatus')->log('Message', ['Line:' => $e->getLine(), 'Message:' => $e->getMessage(), 'FileName:' => $e->getFile()]);
			return array('status' => false, 'message' => 'Cập nhật thất bại!');
		}
	}
}

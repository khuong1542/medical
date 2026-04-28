<?php

namespace App\Http\Services\Admin;

use App\Base\BaseService;
use App\Http\Helpers\FileHelper;
use App\Http\Helpers\LoggerHelper;
use App\Http\Repositories\Admin\DoctorRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class DoctorService extends BaseService
{
	private LoggerHelper $logger;
	public function __construct()
	{
		parent::__construct();
		$this->logger = new LoggerHelper;
		$this->logger->setFileName('DoctorService');
	}

	public function repository(): string
	{
		return DoctorRepository::class;
	}

	/**
	 * Get list
	 *
	 * @param array $payload
	 * @return mixed
	 */
	public function loadList(array $payload): mixed
	{
		$conditions = [];
		$options = $this->buildListOptions($payload, ['name', 'code']);
		return $this->repository->list($conditions, ['facilities', 'specialty'], $options);
	}

	/**
	 * Create or update record with transaction.
	 *
	 * @param array $data Dữ liệu truyền vào
	 * @param string|int|null $id Id cập nhật
	 * @return array
	 *
	 * @throws Throwable
	 */
	public function updateOrStore(array $data, string|int|null $id = null): array
	{
		DB::beginTransaction();
		try {
            if (isset($data['images']) && $data['images'] instanceof \Illuminate\Http\UploadedFile) {
				$images = FileHelper::upload((array)$data['images'], 'attach-file/doctors');
				$data['images'] = json_encode($images);
			}
			$data['id'] = $id;
			$this->logger->setChannel('DoctorUpdate')->log('Param', $data);
			$data = $this->repository->updateOrStore($data);
			DB::commit();
			return $data;
		} catch (\Exception $e) {
			DB::rollback();
			$this->logger->setChannel('DoctorUpdate')->log('Error', [$e->getMessage(), $e->getFile(), $e->getLine()]);
			throw $e;
		}
	}

	/**
	 * Delete multiple records by IDs.
	 *
	 * @param array $payload Input data
	 * @return array
	 *
	 * @throws Throwable
	 */
	public function destroy(array $payload): array
	{
		$arrIds = explode(',', $payload['ids']);
		DB::beginTransaction();
		try {
			$this->logger->setChannel('Delete')->log('Params', $arrIds);
			foreach ($arrIds as $id) {
				$this->repository->deleteChild($id);
			}
			DB::commit();
			return array('status' => true, 'message' => 'Deleted Successfully.');
		} catch (\Exception $e) {
			DB::rollback();
			$this->logger->setChannel('Delete')->log('Messages', ['Line:' => $e->getLine(), 'Message:' => $e->getMessage(), 'FileName:' => $e->getFile()]);
			return array('status' => false, 'message' => 'Deleted Failed.!');
		}
	}

	/**
	 * Normalize order field for all records (1 → N).
	 *
	 * @param array $payload Input data
	 * @return array
	 *
	 * @throws Throwable
	 */
	public function updateOrder(array $payload): array
	{
		DB::beginTransaction();
		try {
			$this->logger->setChannel('UpdateOrder')->log('Params', $payload);
			$data = $this->repository->select('*')->orderBy('order')->get();
			$i = 1;
			foreach ($data as $key => $value) {
				$value->update(['order' => $i++]);
			}
			DB::commit();
			return array('status' => true, 'message' => 'Updated Successfully.!');
		} catch (\Exception $e) {
			DB::rollback();
			$this->logger->setChannel('UpdateOrder')->log('Message', ['Line:' => $e->getLine(), 'Message:' => $e->getMessage(), 'FileName:' => $e->getFile()]);
			return array('status' => false, 'message' => 'Updated Failed.!');
		}
	}

	/**
	 * Update record status by ID.
	 *
	 * @param array $data Input data
	 * @param string|int $id
	 * @return array
	 *
	 * @throws Throwable
	 */
	public function changeStatus(array $data, string|int $id): array
	{
		DB::beginTransaction();
		try {
			$this->logger->setChannel('ChangeStatus')->log('Params', $data);
			$this->repository->update($id, ['status' => $data['status']]);
			DB::commit();
			return array('status' => true, 'message' => 'Updated Successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$this->logger->setChannel('ChangeStatus')->log('Message', ['Line:' => $e->getLine(), 'Message:' => $e->getMessage(), 'FileName:' => $e->getFile()]);
			return array('status' => false, 'message' => 'Update Failed!');
		}
	}
}

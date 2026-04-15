	<?php

	namespace App\Http\Services\Admin;

	use App\Base\BaseService;
	use App\Http\Helpers\LoggerHelper;
	use App\Http\Repositories\Admin\FacilityRepository;
	use Illuminate\Support\Facades\DB;

	class FacilityService extends BaseService
	{
		private $logger;
		public function __construct()
		{
			parent::__construct();
			$this->logger = new LoggerHelper;
			$this->logger->setFileName('FacilityService');
		}

		public function repository()
		{
			return FacilityRepository::class;
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

		public function updateOrStore(array $data, int|string|null $id = null)
		{
			DB::beginTransaction();
			try {
				$data['id'] = $id;
				$this->logger->log('Param', $data);
				$data = $this->repository->updateOrStore($data);
				DB::commit();
				return $data;
			} catch (\Exception $e) {
				DB::rollback();
				$this->logger->log('Error', [$e->getMessage(), $e->getFile(), $e->getLine()]);
				throw $e->getMessage();
			}
		}

		public function destroy(array $params): array
		{
			$arrIds = explode(',', $params['ids']);
			DB::beginTransaction();
			try {
				$this->logger->setChannel('Delete')->log('Params', $arrIds);
				foreach($arrIds as $id){
					$this->repository->deleteChild($id);
				}
				DB::commit();
				return array('status' => true, 'message' => 'Xóa thành công.');
			} catch (\Exception $e) {
				DB::rollback();
				$this->logger->setChannel('Delete')->log('Messages', ['Line:' => $e->getLine(), 'Message:' => $e->getMessage(), 'FileName:' => $e->getFile()]);
				return array('status' => false, 'message' => 'Xóa thất bại!');
			}
		}

		public function updateOrder(array $params): array
		{
			DB::beginTransaction();
			try {
				$this->logger->setChannel('UpdateOrder')->log('Params', $params);
				$data = $this->repository->select('*')->orderBy('order')->get();
				$i = 1;
				foreach ($data as $key => $value) {
					$value->update(['order' => $i++]);
				}
				DB::commit();
				return array('status' => true, 'message' => 'Cập nhật thành công!');
			} catch (\Exception $e) {
				DB::rollback();
				$this->logger->setChannel('UpdateOrder')->log('Message', ['Line:' => $e->getLine(), 'Message:' => $e->getMessage(), 'FileName:' => $e->getFile()]);
				return array('status' => false, 'message' => 'Cập nhật thất bại!');
			}
		}

		public function changeStatus(array $params): array
		{
			DB::beginTransaction();
			try {
				$this->logger->setChannel('ChangeStatus')->log('Params', $params);
				$this->repository->update($params['id'], ['status' => $params['status']]);
				DB::commit();
				return array('status' => true, 'message' => 'Cập nhật thành công!');
			} catch (\Exception $e) {
				DB::rollback();
				$this->logger->setChannel('ChangeStatus')->log('Message', ['Line:' => $e->getLine(), 'Message:' => $e->getMessage(), 'FileName:' => $e->getFile()]);
				return array('status' => false, 'message' => 'Cập nhật thất bại!');
			}
		}
	}
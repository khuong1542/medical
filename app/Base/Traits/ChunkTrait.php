<?php

namespace App\Base\Traits;

trait ChunkTrait
{
    /**
	 * Xử lý dữ liệu theo từng khối (chunk) để tối ưu memory.
	 *
	 * Phù hợp khi:
	 * - Import / Export dữ liệu lớn
	 * - Cron job
	 * - Queue processing
	 *
	 * Ví dụ:
	 * $repo->chunk(100, function ($rows) {
	 *     foreach ($rows as $row) {
	 *         //
	 *     }
	 * });
	 *
	 * @param  int       $count     Số record mỗi chunk
	 * @param  callable  $callback  Hàm xử lý
	 *
	 * @return bool
	 */
	public function chunk(
		int $count,
		callable $callback
	): bool {
		return $this->model
			->newQuery()
			->chunk($count, $callback);
	}
}

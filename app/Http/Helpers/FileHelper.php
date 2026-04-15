<?php

namespace App\Http\Helpers;

class FileHelper
{
	/**
	 * Upload 1 file duy nhất
	 *
	 * @param array $file   Mảng chứa thông tin file (name, tmp_name, size, v.v.)
	 * @param string $public   Thư mục lưu trong public (mặc định: attach-file)
	 * @param string $key	  Key định danh file (nếu upload nhiều input khác nhau)
	 * @return array		   Thông tin file sau khi upload (url, name, size,...)
	 */
	public static function upload(array $file = [], string $public = 'attach-file', string $key = 'file')
	{
		if (empty($file)) {
			return [];
		}
		$sDir = base_path('public') . chr(92) . $public . chr(92);
		$folder = FunctionHelper::createFolder($sDir, date('Y'), date('m'), date('d'));
		$result = [];
		if ($file != []) {
			$filename = $file['name'];
			$filename = FunctionHelper::replaceBadChar($filename);
			$filename = FunctionHelper::convertVNtoEN($filename);
			$filename = date('YmdHis') . '_' . uniqid() . '!~!' . $filename;
			$fullname = $folder . $filename;
			copy($file['tmp_name'], $fullname);
			$result = [
				'key' => $key,
				'name' => $file['name'],
				'filename' => $filename,
				'url' => url($public) . '/' . date('Y/m/d') . '/' . $filename,
				'base_path' => $fullname,
				'size' => $file['size'],
			];
		}
		return $result;
	}
	/**
	 * Upload nhiều file (trường hợp input nhiều file hoặc nhiều input khác nhau)
	 *
	 * @param array $data	Mảng file (thường là $_FILES hoặc mảng xử lý lại)
	 * @param string $public Thư mục lưu trong public
	 * @return array		 Danh sách file sau khi upload
	 */
	public static function uploadMultiple(array $data = [], string $public = 'attach-file')
	{
		$files = $data != [] ? $data : ($_FILES != [] ? $_FILES : []);
		$sDir = base_path('public') . chr(92) . $public . chr(92);
		$folder = FunctionHelper::createFolder($sDir, date('Y'), date('m'), date('d'));
		$result = [];
		if ($files != []) {
			$i = 0;
			foreach ($files as $key => $file) {
				$filename = $file['name'];
				$filename = FunctionHelper::replaceBadChar($filename);
				$filename = FunctionHelper::convertVNtoEN($filename);
				$filename = date('YmdHis') . '_' . uniqid() . '!~!' . $filename;
				$fullname = $folder . $filename;
				copy($file['tmp_name'], $fullname);
				$result[$i] = [
					'key' => $key,
					'name' => $file['name'],
					'filename' => $filename,
					'url' => url($public) . '/' . date('Y/m/d') . '/' . $filename,
					'base_path' => $fullname,
					'size' => $file['size'],
				];
				$i++;
			}
		}
		return $result;
	}
	/**
	 * Upload file dạng mảng
	 * $files = [
	 *		"name"	  => ["2" => "iphone_16_pro_white_titan_4f21b4f56e.png","3" => "iphone_16_pro_black_titan_1f65ba95c7.png"],
	 *		"full_path" => ["2" => "iphone_16_pro_white_titan_4f21b4f56e.png","3" => "iphone_16_pro_black_titan_1f65ba95c7.png"],
	 *		"type"	  => ["2" => "image/png","3" => "image/png"],
	 *		"tmp_name"  => ["2" => "C:\\xampp82\\tmp\\phpB434.tmp","3" => "C:\\xampp82\\tmp\\phpB435.tmp"],
	 *		"error"	 => ["2" => 0,"3" => 0],
	 *		"size"	  => ["2" => 114428,"3" => 114572]
	 *	]
	 * @param array $files   Mảng file kiểu $_FILES["input_name"]
	 * @param string $public Thư mục lưu trữ file trong public
	 * @return array		 Danh sách file sau khi upload
	 */
	public static function handleUploadArray(array $files, string $public = 'attach-file'): array
	{
		$result = [];
		// Chuẩn hóa mảng nếu là dạng multiple
		if (isset($files['name']) && is_array($files['name'])) {
			$files = FileHelper::normalizeUploadedFiles($files); // dùng function ở trên
		}
		foreach ($files as $file) {
			$result[] = FileHelper::upload($file, $public);
		}

		return $result;
	}
	/**
	 * Chuẩn hóa lại cấu trúc $_FILES multiple
	 *
	 * @param array $files Mảng $_FILES["input_name"]
	 * @return array	   Danh sách file chuẩn để xử lý dễ dàng
	 *
	 * @example
	 * Input:
	 *  $_FILES = [
	 *	'name' => ['a.png', 'b.png'],
	 *	'tmp_name' => ['C:/tmp/a', 'C:/tmp/b'],
	 *	...
	 *  ]
	 * Output:
	 *  [
	 *	['name' => 'a.png', 'tmp_name' => 'C:/tmp/a', ...],
	 *	['name' => 'b.png', 'tmp_name' => 'C:/tmp/b', ...],
	 *  ]
	 */
	private static function normalizeUploadedFiles(array $files): array
	{
		$normalized = [];
		foreach ($files['name'] as $index => $name) {
			$normalized[] = [
				'name' => $name,
				'type' => $files['type'][$index],
				'tmp_name' => $files['tmp_name'][$index],
				'error' => $files['error'][$index],
				'size' => $files['size'][$index],
			];
		}
		return $normalized;
	}
	/**
	 * Xóa file khỏi hệ thống
	 *
	 * @param mixed $data - Dữ liệu file (có thể là chuỗi json hoặc mảng)
	 * @return bool - Trả về true nếu xóa thành công, false nếu file không tồn tại
	 */
	public static function deleteFile($data): bool
	{
		$file = is_array($data) ? $data : json_decode($data, true);
		$path = $file['base_path'] ?? '';
		return file_exists($path) ? unlink($path) : false;
	}
}

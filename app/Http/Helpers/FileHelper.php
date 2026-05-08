<?php

namespace App\Http\Helpers;

use Illuminate\Http\UploadedFile;

class FileHelper
{
	/**
	 * Upload 1 file duy nhất
	 *
	 * @param UploadedFile $file   Mảng chứa thông tin file (name, tmp_name, size, v.v.)
	 * @param string $public   Thư mục lưu trong public (mặc định: attach-file)
	 * @param string $key	  Key định danh file (nếu upload nhiều input khác nhau)
	 * @return array		   Thông tin file sau khi upload (url, name, size,...)
	 */

	public static function upload(UploadedFile $file, string $public = 'attach-file', string $key = 'file')
	{
		if (!$file) {
			return [];
		}

		$sDir = base_path('public') . DIRECTORY_SEPARATOR . $public . DIRECTORY_SEPARATOR;

		$folder = FunctionHelper::createFolder(
			$sDir,
			date('Y'),
			date('m'),
			date('d')
		);

		$originalName = $file->getClientOriginalName();
		$originalSize = $file->getSize();

		$filename = FunctionHelper::replaceBadChar($originalName);
		$filename = FunctionHelper::convertVNtoEN($filename);
		$filename = date('YmdHis') . '_' . uniqid() . '!~!' . $filename;

		$fullname = $folder . $filename;

		$file->move($folder, $filename);

		return [
			'key' => $key,
			'name' => $originalName,
			'filename' => $filename,
			'url' => url($public) . '/' . date('Y/m/d') . '/' . $filename,
			'base_path' => $fullname,
			'size' => $originalSize,
		];
	}
	/**
	 * Upload nhiều file (trường hợp input nhiều file hoặc nhiều input khác nhau)
	 *
	 * @param array $files	Mảng files
	 * @param string $public Thư mục lưu trong public
	 * @return array		 Danh sách file sau khi upload
	 */
	public static function uploadMultiple(array $files, string $public = 'attach-file'): array
	{
		$sDir = base_path('public') . DIRECTORY_SEPARATOR . $public . DIRECTORY_SEPARATOR;

		$folder = FunctionHelper::createFolder(
			$sDir,
			date('Y'),
			date('m'),
			date('d')
		);

		$result = [];

		foreach ($files as $key => $file) {

			if (!$file instanceof UploadedFile) {
				continue;
			}

			$originalName = $file->getClientOriginalName();
			$originalSize = $file->getSize();

			$filename = FunctionHelper::replaceBadChar($originalName);
			$filename = FunctionHelper::convertVNtoEN($filename);
			$filename = date('YmdHis') . '_' . uniqid() . '!~!' . $filename;

			$file->move($folder, $filename);

			$result[] = [
				'key' => $key,
				'name' => $originalName,
				'filename' => $filename,
				'url' => url($public) . '/' . date('Y/m/d') . '/' . $filename,
				'base_path' => $folder . $filename,
				'size' => $originalSize,
			];
		}

		return $result;
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

<?php

namespace App\Http\Helpers;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

/**
 *  Thực hiện lưu file log
 *
 * Lưu log
 * Đường dẫn: Y/m/d/ [Tên file]
 * @Tên file: Mã đơn vị
 * Cấu trúc: [%datetime%] [%channel%] [%message%] [%context%] trong đó:
 * @datetime: là thời gian lưu log
 * @channel: Mã hồ sơ (mã định danh)
 * @message: trạng thái request; response
 * @context: dữ liệu trả về
 */
class LoggerHelper
{

	public $folderPath;
	private $channel = "infor";
	private $fileName = "log";

	function __construct()
	{
		$pathLink = storage_path('logs') . '\\';
		$this->folderPath = $this->_createFolder($pathLink, date('Y'), date('m'), date('d'));
	}

	public function setChannel($channel)
	{
		$this->channel = $channel;
		return $this;
	}

	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
		return $this;
	}

	public function log($message, $data = [])
	{
		$path = $this->folderPath . "/" . $this->fileName . ".log";
		$stream = new StreamHandler($path, Logger::DEBUG);
		$stream->setFormatter($this->setFormat());
		$securityLogger = new Logger($this->channel);
		$securityLogger->pushHandler($stream);
		$securityLogger->info($message, ['data' => $data]);
	}

	private function setFormat()
	{
		$dateFormat = "H:i:s d/m/Y";
		$output = "[%datetime%] [%channel%] [%message%] [%context%] \n";
		return new LineFormatter($output, $dateFormat);
	}

	private function _createFolder($pathLink, $folderYear, $folderMonth, $sCurrentDay = "")
	{
		$sPath = str_replace("/", "\\", $pathLink);
		if (!file_exists($sPath . $folderYear)) {
			mkdir($sPath . $folderYear, 0777);
			$sPath = $sPath . $folderYear;
			if (!file_exists($sPath . chr(92) . $folderMonth)) {
				mkdir($sPath . chr(92) . $folderMonth, 0777);
			}
		} else {
			$sPath = $sPath . $folderYear;
			if (!file_exists($sPath . chr(92) . $folderMonth)) {
				mkdir($sPath . chr(92) . $folderMonth, 0777);
			}
		}
		if (!file_exists($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay)) {
			mkdir($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay, 0777);
		}
		$strReturn = $pathLink . $folderYear . '/' . $folderMonth . '/' . $sCurrentDay . '/';
		return str_replace("/", "\\", $strReturn);
	}
}

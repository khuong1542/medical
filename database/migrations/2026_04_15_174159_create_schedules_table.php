<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Bảng Lịch làm việc
	 */
	public function up(): void
	{
		/**
		 * Lịch làm việc
		 */
		if (!Schema::hasTable('schedules')) {
			Schema::create('schedules', function (Blueprint $table) {
				$table->uuid('id')->primary();
				$table->string('code')->nullable()->comment('Mã');
				$table->string('name')->nullable()->comment('Tên');
				$table->text('description')->nullable()->comment('Mô tả');
				$table->integer('order')->nullable()->comment('Thứ tự hiển thị');
				$table->tinyInteger('status')->nullable()->comment('Trạng thái');
				$table->uuid('created_by')->nullable()->comment('Người tạo');
				$table->uuid('updated_by')->nullable()->comment('Người cập nhật');
				$table->ulid('deleted_by')->nullable()->comment('Người xóa');
				$table->timestamps();
				$table->softDeletes();
			});
		}

		/**
		 * Bảng Phiên bản lịch trình
		 */
		if (!Schema::hasTable('schedule_versions')) {
			Schema::create('schedule_versions', function (Blueprint $table) {
				$table->uuid('id')->primary();
				$table->uuid('schedule_id')->comment('Thuộc schedule nào');
				$table->date('effective_from')->comment('Áp dụng từ ngày');
				$table->date('effective_to')->nullable()->comment('Kết thúc');
				$table->tinyInteger('status')->nullable()->comment('Trạng thái');
				$table->uuid('created_by')->nullable()->comment('Người tạo');
				$table->uuid('updated_by')->nullable()->comment('Người cập nhật');
				$table->ulid('deleted_by')->nullable()->comment('Người xóa');
				$table->timestamps();
				$table->softDeletes();
			});
		}

		/**
		 * Bảng Thời gian làm việc
		 */
		if (!Schema::hasTable('shifts')) {
			Schema::create('shifts', function (Blueprint $table) {
				$table->uuid('id')->primary();
				$table->uuid('schedule_version_id')->comment('Lịch làm việc');
				$table->time('start_time')->nullable()->comment('Giờ bắt đầu');
				$table->time('end_time')->nullable()->comment('Giờ kết thúc');
				$table->integer('order')->nullable()->comment('Thứ tự hiển thị');
				$table->tinyInteger('status')->nullable()->comment('Trạng thái');
				$table->uuid('created_by')->nullable()->comment('Người tạo');
				$table->uuid('updated_by')->nullable()->comment('Người cập nhật');
				$table->ulid('deleted_by')->nullable()->comment('Người xóa');
				$table->timestamps();
				$table->softDeletes();
			});
		}

		/**
		 * Bảng Phân ca làm việc
		 */
		if (!Schema::hasTable('schedule_assignments')) {
			Schema::create('schedule_assignments', function (Blueprint $table) {
				$table->uuid('id')->primary();
				$table->uuid('doctor_id')->nullable()->comment('Bác sĩ');
				$table->uuid('schedule_id')->nullable()->comment('Lịch làm việc');
				$table->date('work_date')->nullable()->comment('Ngày làm việc');
				$table->text('description')->nullable()->comment('Mô tả');
				$table->integer('order')->nullable()->comment('Thứ tự hiển thị');
				$table->tinyInteger('status')->nullable()->comment('Trạng thái');
				$table->uuid('created_by')->nullable()->comment('Người tạo');
				$table->uuid('updated_by')->nullable()->comment('Người cập nhật');
				$table->ulid('deleted_by')->nullable()->comment('Người xóa');
				$table->timestamps();
				$table->softDeletes();
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('schedule_assignments');
		Schema::dropIfExists('shifts');
		Schema::dropIfExists('schedule_versions');
		Schema::dropIfExists('schedules');
	}
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Bảng Bác sĩ
	 */
	public function up(): void
	{
		Schema::create('doctors', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('facility_id')->nullable()->comment('ID Bệnh viện/Phòng khám');
			$table->uuid('specialty_id')->nullable()->comment('ID chuyên ngành');
			$table->string('code')->nullable()->comment('Mã');
			$table->string('name')->nullable()->comment('Tên');
			$table->json('images')->nullable()->comment('Ảnh');
			$table->string('email')->unique()->nullable()->comment('Email');
			$table->string('phone')->unique()->nullable()->comment('Số điện thoại');
			$table->integer('experience_years')->nullable()->comment('Số năm kinh nghiệm');
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
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('doctors');
	}
};

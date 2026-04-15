<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Bảng Chi nhánh Bệnh viện / Phòng khám
	 */
	public function up(): void
	{
		Schema::create('facilities', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('code')->nullable()->comment('Mã');
			$table->string('name')->nullable()->comment('Tên');
			$table->tinyInteger('type')->nullable()->comment('1: Bệnh viện, 2: Phòng khám');
			$table->json('images')->nullable()->comment('Ảnh');
			$table->string('tax_code')->nullable()->comment('Mã số thuế');
			$table->text('address')->nullable()->comment('Địa chỉ');
			$table->string('tel')->nullable()->comment('Số điện thoại');
			$table->string('email')->nullable()->comment('Email');
			$table->text('website')->nullable()->comment('Website');
			$table->text('map_url')->nullable()->comment('Map');
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
		Schema::dropIfExists('facilities');
	}
};

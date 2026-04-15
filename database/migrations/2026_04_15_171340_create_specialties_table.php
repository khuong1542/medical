<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Bảng Chuyên ngành / Chuyên khoa
	 */
	public function up(): void
	{
		Schema::create('specialties', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('parent_id')->nullable()->comment('ID chuyên ngành cha');
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
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('specialties');
	}
};

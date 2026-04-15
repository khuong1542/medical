<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		/**
		 * Bảng Quyền
		 */
		Schema::create('roles', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('code')->comment('Mã');
			$table->string('name')->comment('Tên');
			$table->uuid('created_by')->nullable()->comment('Người tạo');
			$table->uuid('updated_by')->nullable()->comment('Người cập nhật');
			$table->ulid('deleted_by')->nullable()->comment('Người xóa');
			$table->timestamps();
		});

		/**
		 * Bảng Người dùng
		 */
		Schema::create('users', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('code')->nullable()->comment('Mã');
			$table->string('name')->comment('Tên');
			$table->string('email')->unique()->comment('Email');
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password')->nullable();
			$table->date('date_of_birth')->nullable()->comment('Ngày sinh');
			$table->string('phone')->unique()->nullable()->comment('Số điện thoại');
			$table->string('gender')->nullable()->comment('Giới tính');
			$table->text('address')->nullable()->comment('Địa chỉ');
			$table->string('role')->nullable()->comment('Quyền');
			$table->integer('order')->nullable()->comment('Thứ tự hiển thị');
			$table->tinyInteger('status')->nullable()->comment('Trạng thái');
			$table->rememberToken();
			$table->uuid('created_by')->nullable()->comment('Người tạo');
			$table->uuid('updated_by')->nullable()->comment('Người cập nhật');
			$table->ulid('deleted_by')->nullable()->comment('Người xóa');
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('password_reset_tokens', function (Blueprint $table) {
			$table->string('email')->primary();
			$table->string('token');
			$table->timestamp('created_at')->nullable();
		});

		Schema::create('sessions', function (Blueprint $table) {
			$table->string('id')->primary();
			$table->foreignId('user_id')->nullable()->index()->comment('Người dùng');
			$table->string('ip_address', 45)->nullable()->comment('IP');
			$table->text('user_agent')->nullable()->comment('User agent');
			$table->longText('payload');
			$table->integer('last_activity')->index();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('users');
		Schema::dropIfExists('roles');
		Schema::dropIfExists('password_reset_tokens');
		Schema::dropIfExists('sessions');
	}
};

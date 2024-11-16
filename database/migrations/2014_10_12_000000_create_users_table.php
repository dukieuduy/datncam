<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Tạo trường `id` là khóa chính với kiểu auto-increment.
            $table->string('name'); // Trường lưu tên người dùng
            $table->string('email')->unique(); // Trường lưu email, đảm bảo không trùng
            $table->timestamp('email_verified_at')->nullable(); // Trường xác nhận email, có thể null
            $table->string('type')->default(User::TYPE_MEMBER);
            $table->string('password'); // Trường lưu mật khẩu (đã mã hóa)
            $table->rememberToken(); // Trường lưu token cho tính năng "remember me"
            $table->timestamps(); // Trường `created_at` và `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->comment('کد اشتراک');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->string('name')->comment('نام');
            $table->string('family')->comment('نام خانوادگی');
            $table->string('mobile')->unique()->comment('موبایل');
            $table->string('username')->unique()->comment('نام کاربری');
            $table->string('password')->comment('رمز ورود');
            $table->string('status')->default('active')->comment('وضعیت');
            $table->string('description')->comment('توضیحات وضعیت')->nullable();
            $table->string('otp_code')->comment('کد احراز')->nullable();
            $table->tinyInteger('access')->comment('دسترسی');
            $table->text('user_key')->nullable()->comment('کلید ارتباطی');
            $table->integer('group_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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

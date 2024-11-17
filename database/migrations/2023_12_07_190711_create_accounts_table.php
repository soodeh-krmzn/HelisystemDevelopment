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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('نام');
            $table->string('family')->comment('نام خانوادگی');
            $table->string('center')->comment('نام مرکز');
            $table->string('phone')->comment('تلفن ثابت');
            $table->string('mobile')->unique()->comment('موبایل');
            $table->string('city')->comment('استان');
            $table->string('town')->comment('شهر');
            $table->text('address')->nullable()->comment('آدرس');
            $table->integer('days')->comment('روزها');
            $table->text('db_name')->nullable()->comment('نام پایگاه داده');
            $table->text('db_user')->nullable()->comment('نام کاربری پایگاه داده');
            $table->text('db_pass')->nullable()->comment('رمز پایگاه داده');
            $table->dateTime('charge_date')->nullable()->comment('تاریخ شارژ');
            $table->string('status')->default('suspend')->comment('وضعیت');
            $table->string('status_detail')->nullable()->comment('توضیح وضعیت');
            $table->integer('sms_charge')->nullable()->comment('شارژ پیامک');
            $table->integer('reserve_charge')->nullable()->comment('شارژ رزرو');
            $table->text('pc_token')->nullable()->comment('کلید ارتباطی با ویندوز');
            $table->text('license_key')->nullable()->comment('لایسنس');
            $table->unsignedBigInteger('package_id')->nullable()->comment('کد بسته');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->string('slug')->nullable()->comment('نامک');
            $table->string('photo')->nullable()->comment('تصویر');
            $table->string('zarinpal')->nullable()->comment('کد زرین پال');
            $table->string('sms_username')->nullable()->comment('نام کاربری پنل پیامک');
            $table->string('sms_password')->nullable()->comment('رمز پنل پیامک');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};

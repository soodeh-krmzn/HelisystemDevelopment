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
        Schema::create('menus', function (Blueprint $table) {
            $table->id()->comment('کد ردیف');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('مورد والد');
            $table->string('name')->comment('برچسب');
            $table->string('icon')->comment('آیکن');
            $table->string('url')->comment('آدرس');
            $table->string('learn_url')->nullable()->comment('آدرس ویدئو آموزشی');
            $table->text('details')->nullable()->comment('توضیحات');
            $table->integer('display_order')->default(0)->comment('ترتیب نمایش');
            $table->boolean('display_nav')->comment('نمایش در نویگیشن');
            $table->string('lang');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

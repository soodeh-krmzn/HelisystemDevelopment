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
        Schema::create('sms_patterns', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->default(0);
            $table->string('name')->unique();
            $table->text('text');
            $table->integer('page');
            $table->integer('cost');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_patterns');
    }
};

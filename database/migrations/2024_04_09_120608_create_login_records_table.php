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
        Schema::create('login_records', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('username');
            $table->string('password');
            $table->string('ip');
            $table->string('browser');
            $table->string('device');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_records');
    }
};

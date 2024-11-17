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
        Schema::create('ticket_bodies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->bigInteger('user_id');
            $table->bigInteger('account_id')->default(0);
            $table->text('body')->nullable();
            $table->string('file')->nullable();
            $table->integer('seen')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_bodies');
    }
};

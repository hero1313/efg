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
        Schema::create('project_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->index();
            $table->decimal('price', 10, 2);
            $table->decimal('pay', 10, 2)->nullable();
            $table->string('name');
            $table->date('pay_date')->nullable();
            $table->date('deadline_date')->nullable();
            $table->string('description')->nullable();
            $table->integer('avance_percent')->nullable();
            $table->decimal('avance_price', 10, 2)->nullable();
            $table->integer('reserve_percent')->nullable();
            $table->decimal('reserve_price', 10, 2)->nullable();
            $table->integer('status')->default('1')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project__plans');
    }
};

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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->integer('guarantee_id')->nullable()->index();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('start_avance', 10, 2)->nullable();
            $table->decimal('avance', 10, 2)->nullable();
            $table->integer('avance_percent')->nullable();
            $table->decimal('start_reserve', 10, 2)->nullable();
            $table->decimal('reserve', 10, 2)->nullable();
            $table->integer('reserve_percent')->nullable();
            $table->decimal('remaining_tax', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('status')->default('1');
            $table->decimal('forecast_plane', 10, 2)->nullable();
            $table->decimal('forecast_calculate', 10, 2)->nullable();
            $table->decimal('forecast_gamontavisuflebuli', 10, 2)->nullable();
            $table->string('image')->default('project.png')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

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
        Schema::create('guarantees', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->string('bank')->nullable();
            $table->decimal('start_price', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('start_reserve_price', 10, 2);
            $table->decimal('reserve_price', 10, 2);
            $table->date('release_date')->nullable();
            $table->date('deadline')->nullable();
            $table->integer('type')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantees');
    }
};

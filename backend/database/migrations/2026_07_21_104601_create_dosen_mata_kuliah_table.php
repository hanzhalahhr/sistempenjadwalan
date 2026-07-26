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
        Schema::create('dosen_mata_kuliah', function (Blueprint $table) {

        $table->bigInteger('dosen_id');

        $table->bigInteger('mata_kuliah_id');


        $table->foreign('dosen_id')
            ->references('id')
            ->on('dosen')
            ->cascadeOnDelete();


        $table->foreign('mata_kuliah_id')
            ->references('id')
            ->on('mata_kuliah')
            ->cascadeOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_mata_kuliah');
    }
};

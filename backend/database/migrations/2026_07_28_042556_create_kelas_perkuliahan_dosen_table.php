<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_perkuliahan_dosen', function (Blueprint $table) {

            $table->bigInteger('id')->autoIncrement();

            $table->bigInteger('kelas_perkuliahan_id');

            $table->bigInteger('dosen_id');

            $table->timestamps();


            $table->foreign('kelas_perkuliahan_id')
                ->references('id')
                ->on('kelas_perkuliahan')
                ->cascadeOnDelete();


            $table->foreign('dosen_id')
                ->references('id')
                ->on('dosen')
                ->cascadeOnDelete();


            $table->unique([
                'kelas_perkuliahan_id',
                'dosen_id'
            ]);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('kelas_perkuliahan_dosen');
    }
};
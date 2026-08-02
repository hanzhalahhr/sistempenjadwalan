<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_dosen', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('jadwal_id');

            $table->bigInteger('dosen_id');

            $table->timestamps();


            $table->foreign('jadwal_id')
                ->references('id')
                ->on('jadwal')
                ->cascadeOnDelete();


            $table->foreign('dosen_id')
                ->references('id')
                ->on('dosen')
                ->cascadeOnDelete();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('jadwal_dosen');
    }
};
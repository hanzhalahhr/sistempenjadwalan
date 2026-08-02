<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_ketersediaan', function (Blueprint $table) {

            $table->id();


            $table->foreignId('dosen_id')
                ->constrained('dosen')
                ->cascadeOnDelete();


            $table->enum('hari', [
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu'
            ]);


            $table->time('jam_mulai');


            $table->time('jam_selesai');


            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('dosen_ketersediaan');
    }
};
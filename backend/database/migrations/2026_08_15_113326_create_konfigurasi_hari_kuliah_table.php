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
        Schema::create('konfigurasi_hari_kuliah', function (Blueprint $table) {

            $table->id();

            // Harus BIGINT signed karena
            // semester_akademik.id adalah BIGINT signed
            $table->bigInteger('semester_akademik_id');

            $table->enum('hari', [
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu'
            ]);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('semester_akademik_id')
                ->references('id')
                ->on('semester_akademik')
                ->onDelete('cascade');


            /*
            |--------------------------------------------------------------------------
            | UNIQUE
            |--------------------------------------------------------------------------
            |
            | Satu semester tidak boleh punya hari yang sama dua kali.
            |
            */

            $table->unique([
                'semester_akademik_id',
                'hari'
            ]);

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_hari_kuliah');
    }
};
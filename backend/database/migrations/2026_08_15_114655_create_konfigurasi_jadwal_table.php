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
        Schema::create('konfigurasi_jadwal', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | SEMESTER AKADEMIK
            |--------------------------------------------------------------------------
            |
            | semester_akademik.id menggunakan BIGINT signed,
            | jadi foreign key juga harus BIGINT signed.
            |
            */

            $table->bigInteger('semester_akademik_id');

            /*
            |--------------------------------------------------------------------------
            | STATUS KONFIGURASI
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(false);

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('semester_akademik_id')
                ->references('id')
                ->on('semester_akademik')
                ->onDelete('restrict')
                ->onUpdate('cascade');


            /*
            |--------------------------------------------------------------------------
            | SATU SEMESTER = SATU KONFIGURASI
            |--------------------------------------------------------------------------
            */

            $table->unique('semester_akademik_id');

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_jadwal');
    }
};
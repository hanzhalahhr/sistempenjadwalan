<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ruangan', function (Blueprint $table) {

            $table->enum('tipe_ruangan', [
                'Kelas',
                'Lab'
            ])->after('kapasitas');

        });
    }


    public function down(): void
    {
        Schema::table('ruangan', function (Blueprint $table) {

            $table->dropColumn('tipe_ruangan');

        });
    }
};
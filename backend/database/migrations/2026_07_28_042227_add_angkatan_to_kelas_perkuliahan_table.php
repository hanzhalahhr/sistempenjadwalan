<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas_perkuliahan', function (Blueprint $table) {
            $table->integer('angkatan')->after('semester_akademik_id');
        });
    }

    public function down(): void
    {
        Schema::table('kelas_perkuliahan', function (Blueprint $table) {
            $table->dropColumn('angkatan');
        });
    }
};
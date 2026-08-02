<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('slot_waktu_kuliah', function (Blueprint $table) {
            $table->unsignedTinyInteger('urutan')->after('hari');
        });
    }

    public function down(): void
    {
        Schema::table('slot_waktu_kuliah', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};

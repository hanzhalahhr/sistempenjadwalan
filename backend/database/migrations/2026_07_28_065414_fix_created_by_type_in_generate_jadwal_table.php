<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('generate_jadwal', function (Blueprint $table) {

            if (Schema::hasColumn('generate_jadwal', 'created_by')) {
                $table->dropColumn('created_by');
            }

        });
    }


    public function down(): void
    {
        Schema::table('generate_jadwal', function (Blueprint $table) {

            if (!Schema::hasColumn('generate_jadwal', 'created_by')) {

                $table->unsignedBigInteger('created_by')
                    ->nullable();

            }

        });
    }

};
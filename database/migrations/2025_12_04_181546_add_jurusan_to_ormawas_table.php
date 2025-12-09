<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ormawas', function (Blueprint $table) {
            // jika kolom belum ada
            if (!Schema::hasColumn('ormawas', 'jurusan')) {
                $table->string('jurusan', 100)->nullable()->after('nama_ormawa');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ormawas', function (Blueprint $table) {
            if (Schema::hasColumn('ormawas', 'jurusan')) {
                $table->dropColumn('jurusan');
            }
        });
    }
};

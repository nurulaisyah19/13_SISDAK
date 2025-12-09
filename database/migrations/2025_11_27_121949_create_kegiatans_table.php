<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->uuid('id_kegiatan')->primary();

            $table->string('nama_kegiatan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->uuid('id_ormawa');
            $table->uuid('id_jenis');

            $table->timestamps();

            $table->foreign('id_ormawa')
                ->references('id_ormawa')
                ->on('ormawas')
                ->cascadeOnDelete();

            $table->foreign('id_jenis')
                ->references('id_jenis')
                ->on('jenis_kegiatans')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};

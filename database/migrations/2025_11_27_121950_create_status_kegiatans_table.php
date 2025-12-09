<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('status_kegiatans', function (Blueprint $table) {
            $table->uuid('id_status')->primary();

            $table->string('status'); // menunggu / disetujui / ditolak
            $table->text('catatan')->nullable();

            $table->uuid('id_kegiatan');
            $table->uuid('id_user')->nullable(); // admin yang verifikasi

            $table->timestamps();

            $table->foreign('id_kegiatan')
                ->references('id_kegiatan')
                ->on('kegiatans')
                ->cascadeOnDelete();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_kegiatans');
    }
};

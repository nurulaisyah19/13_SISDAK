<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->uuid('id_dokumen')->primary();

            $table->string('nama_dokumen');
            $table->string('file_path');

            $table->uuid('id_kegiatan');

            $table->timestamps();

            $table->foreign('id_kegiatan')
                ->references('id_kegiatan')
                ->on('kegiatans')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};

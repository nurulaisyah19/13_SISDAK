<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ormawas', function (Blueprint $table) {
            $table->uuid('id_ormawa')->primary();
            $table->string('nama_ormawa');
            $table->text('deskripsi')->nullable();
            $table->string('kontak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ormawas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id_user')->primary();

            $table->string('username')->unique();
            $table->string('password');

            $table->string('role')->default('ormawa'); // admin / ormawa

            $table->uuid('id_ormawa')->nullable();
            $table->timestamps();

            $table->foreign('id_ormawa')
                ->references('id_ormawa')
                ->on('ormawas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

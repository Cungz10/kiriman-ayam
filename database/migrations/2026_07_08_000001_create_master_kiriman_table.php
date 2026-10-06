<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_kiriman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kiriman', 100);
            $table->timestamp('created_at')->useCurrent();

            $table->unique('nama_kiriman');
            $table->index('nama_kiriman', 'idx_nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_kiriman');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_input', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kiriman', 100);
            $table->string('nomer_po', 50);
            $table->text('data_input'); // JSON array of nilai timbangan, mis: [4.1,4.5,5.0]
            $table->integer('total_data');
            $table->decimal('rata_rata', 5, 2);
            // Dilebarin dari decimal(3,1) -> decimal(4,2) supaya mode presisi 2 desimal
            // (mis. 5.75) juga bisa tersimpan tanpa terpotong.
            $table->decimal('nilai_max', 4, 2);
            $table->decimal('nilai_min', 4, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->index('nomer_po', 'idx_po');
            $table->index('created_at', 'idx_tanggal');
            $table->index('nama_kiriman', 'idx_kiriman');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_input');
    }
};

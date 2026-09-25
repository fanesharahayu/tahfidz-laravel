<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setoran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('musyrif_id')->constrained('users')->cascadeOnDelete();
            $table->integer('juz');
            $table->string('surah', 50)->nullable();
            $table->integer('ayat_awal')->nullable()->default(0);
            $table->integer('ayat_akhir')->nullable()->default(0);
            $table->enum('jenis', ['hafalan_baru', 'tambahan', 'murajaah'])->default('hafalan_baru');
            $table->enum('nilai', ['lancar', 'cukup_lancar', 'perlu_ulang'])->default('lancar');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['santri_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran');
    }
};

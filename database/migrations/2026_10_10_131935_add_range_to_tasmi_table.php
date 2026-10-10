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
        Schema::table('tasmi', function (Blueprint $table) {
            $table->integer('juz_akhir')->nullable()->after('juz');
            $table->string('surah_akhir', 50)->nullable()->after('surah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasmi', function (Blueprint $table) {
            $table->dropColumn(['juz_akhir', 'surah_akhir']);
        });
    }
};

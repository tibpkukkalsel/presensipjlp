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
        Schema::create('penerimaan', function (Blueprint $table) {
            $table->char('kode_penerimaan', 15)->primary();
            $table->string('nik', 20)->index();
            $table->date('tgl_penerimaan');
            $table->string('kode_cabang', 10)->nullable();
            $table->string('status', 20)->default('ada');
            $table->char('status_approved', 1)->default('0');
            $table->string('notice_keluar', 50)->nullable();
            $table->bigInteger('r2')->default(0);
            $table->bigInteger('r4')->default(0);
            $table->bigInteger('jumlah')->default(0);
            $table->string('doc_sid', 255)->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan');
    }
};

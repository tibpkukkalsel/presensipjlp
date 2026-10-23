<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('konfigurasi_laporan')) {
            Schema::create('konfigurasi_laporan', function (Blueprint $table) {
                $table->id();
                $table->string('pejabat1_jabatan', 150)->default('KEPALA BPKUK PROV. KALSEL');
                $table->string('pejabat1_nama', 150)->default('FEBRY RAHARJO, S.IP, MM');
                $table->string('pejabat1_nip', 50)->default('19870222 200701 1 002');
                $table->string('pejabat2_jabatan', 150)->default('KASUBBAG TATA USAHA');
                $table->string('pejabat2_nama', 150)->default('ADIMAS FITRIANDI, ST');
                $table->string('pejabat2_nip', 50)->default('19800813 200712 1 001');
                $table->string('kota_cetak', 100)->default('Pelaihari');
                $table->timestamps();
            });

            DB::table('konfigurasi_laporan')->insert([
                'id' => 1,
                'pejabat1_jabatan' => 'KEPALA BPKUK PROV. KALSEL',
                'pejabat1_nama' => 'FEBRY RAHARJO, S.IP, MM',
                'pejabat1_nip' => '19870222 200701 1 002',
                'pejabat2_jabatan' => 'KASUBBAG TATA USAHA',
                'pejabat2_nama' => 'ADIMAS FITRIANDI, ST',
                'pejabat2_nip' => '19800813 200712 1 001',
                'kota_cetak' => 'Pelaihari',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_laporan');
    }
};

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
    Schema::create('karyawan', function (Blueprint $table) {
        $table->id();
        $table->string('nik', 50)->unique();
        $table->string('nama_lengkap');
        $table->string('tempat_lahir', 100)->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->text('alamat')->nullable();
        $table->string('no_telepon', 20)->nullable();
        $table->string('email')->nullable();
        $table->string('foto')->nullable();
        $table->enum('status', ['aktif', 'cuti', 'resign', 'nonaktif'])->default('aktif');
        $table->date('tanggal_masuk');
        $table->date('tanggal_keluar')->nullable();
        $table->string('posisi', 100)->default('Cleaning Service');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};

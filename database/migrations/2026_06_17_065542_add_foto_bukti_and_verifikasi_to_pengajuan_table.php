<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            // Path foto bukti instalasi yang sudah dikompres, disimpan di storage
            $table->string('foto_bukti')->nullable()->after('dokumentasi');

            // Status verifikasi foto oleh supervisor:
            // null          = belum ada foto dikirim
            // 'menunggu'    = foto sudah dikirim admin, menunggu review supervisor
            // 'disetujui'   = supervisor menyetujui foto, status_progress → terinstal
            // 'ditolak'     = supervisor menolak foto, admin harus upload ulang
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->nullable()->after('foto_bukti');

            // Catatan dari supervisor saat menolak foto
            $table->text('catatan_penolakan_foto')->nullable()->after('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn(['foto_bukti', 'status_verifikasi', 'catatan_penolakan_foto']);
        });
    }
};

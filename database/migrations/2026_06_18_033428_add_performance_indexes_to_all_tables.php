<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration: Performance Indexes
 *
 * Kolom yang di-index dipilih berdasarkan kolom yang sering muncul di:
 * - WHERE clause (filter/query)
 * - JOIN / belongsTo / hasMany
 * - ORDER BY (latest(), created_at)
 *
 * Tabel pengajuan:
 *   - user_id         → WHERE user_id = ? (riwayat dosen)
 *   - tugaskan_admin  → WHERE tugaskan_admin = ? (tugas admin)
 *   - status_persetujuan → WHERE status_persetujuan = ?
 *   - status_progress → WHERE status_progress IN (...)
 *   - status_verifikasi → WHERE status_verifikasi = ?
 *   - created_at / tgl_penugasan → ORDER BY latest()
 *   - (status_persetujuan, status_progress) → composite untuk query gabungan
 *   - (tugaskan_admin, status_progress) → composite untuk indexAdmin query
 *
 * Tabel laboratoriums:
 *   - user_id  → WHERE user_id = ? (lab milik admin)
 *   - status   → WHERE status = ?
 *   - no_lab   → WHERE no_lab = ? (sudah unique, tapi perlu index eksplisit)
 *
 * Tabel users:
 *   - role     → WHERE role = ? (filter supervisor/admin/dosen)
 *   - email    → sudah unique, index otomatis — skip
 *   - no_induk → sudah primary key — skip
 */
return new class extends Migration
{
    public function up(): void
    {
        // ------------------------------------------------------------------
        // TABEL: pengajuan
        // ------------------------------------------------------------------
        Schema::table('pengajuan', function (Blueprint $table) {
            // Index tunggal untuk filter umum
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_user_id')) {
                $table->index('user_id', 'idx_pengajuan_user_id');
            }
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_tugaskan_admin')) {
                $table->index('tugaskan_admin', 'idx_pengajuan_tugaskan_admin');
            }
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_status_persetujuan')) {
                $table->index('status_persetujuan', 'idx_pengajuan_status_persetujuan');
            }
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_status_progress')) {
                $table->index('status_progress', 'idx_pengajuan_status_progress');
            }
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_status_verifikasi')) {
                $table->index('status_verifikasi', 'idx_pengajuan_status_verifikasi');
            }
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_created_at')) {
                $table->index('created_at', 'idx_pengajuan_created_at');
            }
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_tgl_penugasan')) {
                $table->index('tgl_penugasan', 'idx_pengajuan_tgl_penugasan');
            }

            // Composite indexes — paling sering dipakai di controller
            // indexAdmin: WHERE status_persetujuan='disetujui' AND tugaskan_admin=? AND status_progress='progress'
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_admin_progress')) {
                $table->index(['tugaskan_admin', 'status_progress'], 'idx_pengajuan_admin_progress');
            }
            // indexSupervisor: WHERE status_persetujuan='pending'
            // indexPenyelesaian: WHERE status_persetujuan='disetujui' AND status_progress IN (...)
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_persetujuan_progress')) {
                $table->index(['status_persetujuan', 'status_progress'], 'idx_pengajuan_persetujuan_progress');
            }
            // Verifikasi foto: WHERE status_verifikasi='menunggu'
            if (!$this->indexExists('pengajuan', 'idx_pengajuan_verifikasi_persetujuan')) {
                $table->index(['status_verifikasi', 'status_persetujuan'], 'idx_pengajuan_verifikasi_persetujuan');
            }
        });

        // ------------------------------------------------------------------
        // TABEL: laboratoriums
        // ------------------------------------------------------------------
        Schema::table('laboratoriums', function (Blueprint $table) {
            if (!$this->indexExists('laboratoriums', 'idx_lab_user_id')) {
                $table->index('user_id', 'idx_lab_user_id');
            }
            if (!$this->indexExists('laboratoriums', 'idx_lab_status')) {
                $table->index('status', 'idx_lab_status');
            }
        });

        // ------------------------------------------------------------------
        // TABEL: users
        // ------------------------------------------------------------------
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'idx_users_role')) {
                $table->index('role', 'idx_users_role');
            }
        });

        // ------------------------------------------------------------------
        // TABEL: sessions — index untuk pembersihan session lama
        // ------------------------------------------------------------------
        Schema::table('sessions', function (Blueprint $table) {
            if (!$this->indexExists('sessions', 'idx_sessions_last_activity')) {
                $table->index('last_activity', 'idx_sessions_last_activity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_pengajuan_user_id');
            $table->dropIndexIfExists('idx_pengajuan_tugaskan_admin');
            $table->dropIndexIfExists('idx_pengajuan_status_persetujuan');
            $table->dropIndexIfExists('idx_pengajuan_status_progress');
            $table->dropIndexIfExists('idx_pengajuan_status_verifikasi');
            $table->dropIndexIfExists('idx_pengajuan_created_at');
            $table->dropIndexIfExists('idx_pengajuan_tgl_penugasan');
            $table->dropIndexIfExists('idx_pengajuan_admin_progress');
            $table->dropIndexIfExists('idx_pengajuan_persetujuan_progress');
            $table->dropIndexIfExists('idx_pengajuan_verifikasi_persetujuan');
        });

        Schema::table('laboratoriums', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_lab_user_id');
            $table->dropIndexIfExists('idx_lab_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_users_role');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_sessions_last_activity');
        });
    }

    /** Cek apakah index sudah ada untuk menghindari duplikat error */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};

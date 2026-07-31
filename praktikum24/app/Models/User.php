<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    protected $table    = 'users';
    protected $fillable = ['nama', 'email', 'password', 'role', 'nim', 'mahasiswa_id'];
    protected $hidden   = ['password'];

    public function hakAkses()
    {
        return $this->belongsTo(HakAkses::class, 'role', 'nama_role');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // ── Ambil permission dari database (cache pakai array, bukan object) ──
    private function getPermission(): array
    {
        return Cache::remember("hak_akses_{$this->role}", 300, function () {
            $data = HakAkses::where('nama_role', $this->role)
                            ->where('is_active', true)
                            ->first();

            // Simpan sebagai array biasa, bukan object Eloquent
            return $data ? [
                'level'      => $data->level,
                'can_create' => $data->can_create,
                'can_read'   => $data->can_read,
                'can_update' => $data->can_update,
                'can_delete' => $data->can_delete,
                'can_export' => $data->can_export,
            ] : [];
        });
    }

    // ── Cek role ──────────────────────────────────────────────────────────
    public function isAdmin()     { return $this->role === 'admin'; }
    public function isPetugas()   { return $this->role === 'petugas'; }
    public function isMahasiswa()   { return $this->role === 'user'; }
    public function isGuest()     { return $this->role === 'guest'; }

    // ── Cek level ─────────────────────────────────────────────────────────
    public function getLevel(): int
    {
        return $this->getPermission()['level'] ?? 4;
    }

    public function hasMinLevel(int $minLevel): bool
    {
        return $this->getLevel() <= $minLevel;
    }

    // ── Cek permission dari DATABASE ──────────────────────────────────────
    public function can_create(): bool { return $this->getPermission()['can_create'] ?? false; }
    public function can_read():   bool { return $this->getPermission()['can_read']   ?? true;  }
    public function can_update(): bool { return $this->getPermission()['can_update'] ?? false; }
    public function can_delete(): bool { return $this->getPermission()['can_delete'] ?? false; }
    public function can_export(): bool { return $this->getPermission()['can_export'] ?? false; }

    // ── Hapus cache saat permission diubah ───────────────────────────────
    public function clearPermissionCache(): void
    {
        Cache::forget("hak_akses_{$this->role}");
    }

    // ── Notifikasi ──────────────────────────────────────────────────────
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'user_id');
    }

    public function unreadNotifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'user_id')->where('is_read', false);
    }

    public function latestNotifications(int $limit = 10)
    {
        return $this->hasMany(\App\Models\Notification::class, 'user_id')->latest()->limit($limit);
    }
}
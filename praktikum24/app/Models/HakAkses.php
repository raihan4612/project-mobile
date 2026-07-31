<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HakAkses extends Model
{
    protected $table      = 'hak_akses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_role',
        'level',
        'deskripsi',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
        'can_export',
        'is_active',
    ];

    protected $casts = [
        'level'      => 'integer',
        'can_create' => 'boolean',
        'can_read'   => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
        'can_export' => 'boolean',
        'is_active'  => 'boolean',
    ];

    // ── Label & badge ─────────────────────────────────────────────────────
    public const LEVEL_BADGES = [
        1 => 'danger',
        2 => 'warning',
        3 => 'primary',
        4 => 'secondary',
    ];

    public function getLevelBadgeAttribute(): string
    {
        return self::LEVEL_BADGES[$this->level] ?? 'secondary';
    }

    // ── Scope ─────────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLevel($query)
    {
        return $query->orderBy('level');
    }

    // ── Hapus cache otomatis setiap kali data hak_akses di-update ────────
    // Sehingga perubahan permission di halaman Hak Akses langsung berlaku
    protected static function booted(): void
    {
        static::saved(function (HakAkses $hakAkses) {
            Cache::forget("hak_akses_{$hakAkses->nama_role}");
        });

        static::deleted(function (HakAkses $hakAkses) {
            Cache::forget("hak_akses_{$hakAkses->nama_role}");
        });
    }
}
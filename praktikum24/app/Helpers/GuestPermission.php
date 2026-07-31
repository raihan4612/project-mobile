<?php

namespace App\Helpers;

use App\Models\HakAkses;
use Illuminate\Support\Facades\Cache;

class GuestPermission
{
    private static function getPermission(): array
    {
        return Cache::remember('hak_akses_guest', 300, function () {
            $data = HakAkses::where('nama_role', 'Guest')
                            ->where('is_active', true)
                            ->first();

            return $data ? [
                'can_create' => $data->can_create,
                'can_read'   => $data->can_read,
                'can_update' => $data->can_update,
                'can_delete' => $data->can_delete,
                'can_export' => $data->can_export,
            ] : [];
        });
    }

    public static function can_create(): bool { return self::getPermission()['can_create'] ?? false; }
    public static function can_read():   bool { return self::getPermission()['can_read']   ?? true;  }
    public static function can_update(): bool { return self::getPermission()['can_update'] ?? false; }
    public static function can_delete(): bool { return self::getPermission()['can_delete'] ?? false; }
    public static function can_export(): bool { return self::getPermission()['can_export'] ?? false; }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nim')) {
                $table->string('nim', 20)->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'mahasiswa_id')) {
                $table->unsignedBigInteger('mahasiswa_id')->nullable()->after('role');
                $table->foreign('mahasiswa_id')->references('id')->on('mhs')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'mahasiswa_id')) {
                $table->dropForeign(['mahasiswa_id']);
                $table->dropColumn('mahasiswa_id');
            }
            if (Schema::hasColumn('users', 'nim')) {
                $table->dropColumn('nim');
            }
        });
    }
};

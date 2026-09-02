<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('qr_token')->nullable()->after('avatar');
            $table->string('biometric_id')->nullable()->after('qr_token');
            $table->foreignId('role_id')->nullable()->after('biometric_id')->constrained('roles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['qr_token', 'biometric_id', 'role_id']);
        });
    }
};

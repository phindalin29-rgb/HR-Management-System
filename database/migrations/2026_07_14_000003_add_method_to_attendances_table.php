<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('method')->default('manual')->after('status');
            $table->decimal('latitude', 10, 7)->nullable()->after('method');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('biometric_id')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['method', 'latitude', 'longitude', 'biometric_id']);
        });
    }
};

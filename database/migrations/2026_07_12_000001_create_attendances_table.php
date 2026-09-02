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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id');            // references users.user_id (e.g. KH-0001)
            $table->string('employee_name')->nullable();
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('work_hours')->nullable();   // computed, e.g. "8h 12m"
            $table->string('status')->default('Present'); // Present, Late, Half Day, Absent, Leave
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['staff_id', 'date']); // one attendance record per employee per day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

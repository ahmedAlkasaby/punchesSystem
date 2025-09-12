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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->date('date')->index();
            $table->timestamp('first_in')->nullable();
            $table->timestamp('last_out')->nullable();
            $table->integer('total_seconds')->default(0);
            $table->decimal('total_hours', 8, 2)->default(0);

            // الحالة العامة لليوم
            $table->enum('day_status', ['present', 'absent', 'holiday', 'exception'])->default('present');
            $table->string('exception_type')->nullable();

            // flags
            $table->boolean('is_late')->default(false);
            $table->integer('late_seconds')->default(0);
            $table->boolean('is_early_leave')->default(false);
            $table->boolean('is_under_hours')->default(false);

            // مشاكل البصمات
            $table->boolean('has_missing_in')->default(false);
            $table->boolean('has_missing_out')->default(false);
            $table->boolean('flagged_in')->default(false);
            $table->boolean('flagged_out')->default(false);
            $table->json('notes')->nullable();
            $table->dateTime('computed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            
            $table->unique(['employee_id','date']);
            $table->index('employee_id');
            $table->index('day_status');
            $table->index('exception_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};

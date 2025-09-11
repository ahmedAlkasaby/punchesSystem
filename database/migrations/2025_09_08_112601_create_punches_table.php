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
        Schema::create('punches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['in', 'out']);
            $table->boolean('is_late')->nullable();
            $table->boolean('is_early_leave')->nullable();
            $table->boolean('is_out_of_radius')->default(false);
            $table->boolean('approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->double('latitude');
            $table->double('longitude');
            $table->string('address');
            $table->string('device_info')->nullable();
            $table->double('distance_from_location')->nullable()->comment('Distance in meters from the assigned location');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punches');
    }
};

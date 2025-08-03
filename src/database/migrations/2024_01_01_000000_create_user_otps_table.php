<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('send_to_phone');
            $table->tinyInteger('otp_type')->comment('0: signup, 1: password reset');
            $table->string('otp_code', 10);
            $table->boolean('is_otp_verified')->default(false);
            $table->integer('failed_attempts_count')->default(0);
            $table->timestamp('last_failed_attempt_date')->nullable();
            $table->timestamp('last_resend_date')->nullable();
            $table->integer('resend_count')->default(1);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'otp_type']);
            $table->index(['send_to_phone', 'otp_type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user_otps');
    }
}; 
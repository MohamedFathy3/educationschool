<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('job_title')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_ext')->nullable();
            $table->string('cell')->nullable();
            $table->boolean('active')->default(true);
            $table->string('role')->default(App\Enums\UserRole::EMPLOYEE->value);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('first_login_at')->nullable();
            $table->string('password')->nullable();
            $table->string('code_verify')->nullable();
            $table->dateTime('expiry_time_code_verify')->nullable();
            $table->string('avatar')->nullable();
            $table->string('title')->nullable();

            $table->foreignId('phone_key_id')->nullable(); // ← تم تعديلها هنا
            $table->foreign('phone_key_id')->references('id')->on('countries')->cascadeOnDelete();



            $table->boolean('sale_man')->default(false);
            $table->boolean('access_all_charges')->default(false);
            $table->boolean('hide_other_records')->default(false);
            $table->string('email_password')->nullable();
            $table->string('email_display_name')->nullable();
            $table->string('email_host')->nullable();
            $table->string('email_port')->nullable();
            $table->string('email_ssl')->nullable();
            $table->string('local_name')->nullable();
            $table->text('note')->nullable();
            $table->string('username')->nullable();
            $table->string('address')->nullable();


            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

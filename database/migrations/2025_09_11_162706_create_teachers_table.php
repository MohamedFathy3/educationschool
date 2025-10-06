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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('total_rate')->default(5);
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('teacher_type')->nullable();
            $table->string('national_id')->nullable();
            $table->string('image')->nullable();
            $table->string('certificate_image')->nullable();
            $table->string('experience_image')->nullable();
            $table->string('id_card_front')->nullable();
            $table->string('id_card_back')->nullable();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stage_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('active')->default(0);
            $table->string('password')->nullable();

/////bank_accounts

            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('branch_name')->nullable();

/////wallets
            $table->string('wallets_name')->nullable();
            $table->string('wallets_number')->nullable();

            $table->decimal('commission', 5, 2)->default(50);
            $table->decimal('amount', 10, 2)->default(0); // رصيد المدرس

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};

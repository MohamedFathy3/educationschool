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
        Schema::create('course_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable(); // اسم الحصة أو الملف
            $table->text('description')->nullable(); // وصف قصير
            $table->enum('content_type', ['video', 'pdf', 'file', 'zoom'])->default('video');
            $table->string('content_link')->nullable(); // لو فيديو يوتيوب أو لينك زووم
            $table->string('file_path')->nullable(); // لو PDF أو ملف مرفوع
            $table->date('session_date')->nullable(); // يوم الحصة
            $table->time('session_time')->nullable(); // وقت الحصة
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_details');
    }
};

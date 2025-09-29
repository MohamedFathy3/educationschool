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
        Schema::create('course_detail_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_detail_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at')->nullable(); // وقت الدخول (هيبعت من الفرونت)
            $table->integer('watched_duration')->default(0); // وقت المشاهدة بالثواني (من الفرونت)
            $table->boolean('view')->default(false); // خلص ولا لأ (من الفرونت)
            $table->text('extra_data')->nullable(); // أي داتا إضافية يبعتهالك الفرونت
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_detail_student');
    }
};

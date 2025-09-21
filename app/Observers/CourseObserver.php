<?php

namespace App\Observers;

use App\Models\Course;

class CourseObserver
{
     public function created(Course $course)
    {
        $teacher = $course->teacher;
        $commission = $teacher->commission; // النسبة %
        $share = $course->price * ($commission / 100);

        $teacher->increment('amount', $share);
    }

    // عند تحديث الكورس
    public function updated(Course $course)
    {
        if ($course->wasChanged('price')) {
            $teacher = $course->teacher;
            $commission = $teacher->commission;

            $oldPrice = $course->getOriginal('price');
            $newPrice = $course->price;

            $oldShare = $oldPrice * ($commission / 100);
            $newShare = $newPrice * ($commission / 100);

            // فرق المبلغ يتحسب
            $difference = $newShare - $oldShare;

            $teacher->increment('amount', $difference);
        }
    }

    // عند حذف الكورس (لو عايز تنقص فلوسه)
    public function deleted(Course $course)
    {
        $teacher = $course->teacher;
        $commission = $teacher->commission;
        $share = $course->price * ($commission / 100);

        $teacher->decrement('amount', $share);
    }
}

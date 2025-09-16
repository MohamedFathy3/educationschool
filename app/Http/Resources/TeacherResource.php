<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request)
    {
        $coursesCount = $this->courses->count();
        $studentsCount = $this->courses->flatMap->students->unique('id')->count();

        $totalIncome = 0;
        $coursesData = [];

        foreach ($this->courses as $course) {
            $studentsInCourse = $course->students->count();
            $courseIncome = $course->price * $studentsInCourse;
            $teacherShare = ($courseIncome * $this->commission) / 100;

            $totalIncome += $teacherShare;

            $coursesData[] = [
                'course_name' => $course->title,
                'students_count' => $studentsInCourse,
                'course_income' => $courseIncome,
                'teacher_share' => $teacherShare,
            ];
        }
        return [
            'id' => $this->id,
            'name' => $this->name   ?? null,
            'email' => $this->email ?? null,
            'active' => $this->active ?? null,
            'type' =>"teacher",
            'phone' => $this->phone ?? null,
            'national_id' => $this->national_id ?? null,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'certificate_image' => $this->certificate_image ? asset('storage/' . $this->certificate_image) : null,
            'experience_image' => $this->experience_image ? asset('storage/' . $this->experience_image) : null,
            'country' => new CountryResource($this->country),
            'stage' => new StageResource($this->stage),
            'subject' => new SubjectResource($this->subject),
            'account_holder_name' => $this->account_holder_name ?? null,
            'account_number' => $this->account_number ?? null,
            'iban' => $this->iban ?? null,
            'swift_code' => $this->swift_code ?? null,
            'branch_name' => $this->branch_name ?? null,
            'wallets_name' => $this->wallets_name ?? null,
            'wallets_number' => $this->wallets_number ?? null,
             // التقرير
            'commission' => $this->commission . '%',
            'courses_count' => $coursesCount,
            'students_count' => $studentsCount,
            'total_income' => $totalIncome,
            'courses' => $coursesData,
        ];
    }
}

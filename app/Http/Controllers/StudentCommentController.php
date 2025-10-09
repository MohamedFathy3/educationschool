<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Resources\StudentCommentResource;
use App\Models\Student;
use App\Models\StudentComment;
use Illuminate\Http\Request;

class StudentCommentController extends Controller
{
    public function store(Request $request, $studentId)
    {
        try {
            $request->validate([
                'comment' => 'nullable|string',
                'rating'  => 'required|integer|min:1|max:5',
            ]);

            $teacher = auth()->user();

            if (!$teacher) {
                return JsonResponse::respondError('Unauthenticated', 401);
            }

            $student = Student::findOrFail($studentId);

            $existing = StudentComment::where('student_id', $studentId)
                ->where('teacher_id', $teacher->id)
                ->first();

            if ($existing) {
                return JsonResponse::respondError('You already rated this student');
            }

            $comment = StudentComment::create([
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'comment'    => $request->comment,
                'rating'     => $request->rating,
            ]);

            return JsonResponse::respondSuccess(
                'Comment Added Successfully',
                new StudentCommentResource($comment->load('teacher'))
            );
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

}

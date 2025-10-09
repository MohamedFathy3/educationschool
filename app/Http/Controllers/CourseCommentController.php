<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Resources\CourseCommentResource;
use App\Models\Course;
use App\Models\CourseComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseCommentController extends Controller
{
    public function store(Request $request, $courseId)
    {
        try {
            $request->validate([
                'comment' => 'nullable|string',
                'rating'  => 'required|integer|min:1|max:5',
            ]);

            $student = Auth::user();

            if (!$student) {
                return JsonResponse::respondError('Unauthenticated', 401);
            }

            $course = Course::findOrFail($courseId);

            $comment = CourseComment::create([
                'course_id'  => $course->id,
                'student_id' => $student->id,
                'comment'    => $request->comment,
                'rating'     => $request->rating,
            ]);

            return JsonResponse::respondSuccess(
                'Comment added successfully',
                new CourseCommentResource($comment->load('student'))
            );
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}

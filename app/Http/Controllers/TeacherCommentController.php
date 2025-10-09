<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Resources\TeacherCommentResource;
use App\Models\Teacher;
use App\Models\TeacherComment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherCommentController extends Controller
{
    public function store(Request $request, $teacherId)
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

            $teacher = Teacher::findOrFail($teacherId);

            $comment = TeacherComment::create([
                'teacher_id' => $teacher->id,
                'student_id' => $student->id,
                'comment'    => $request->comment,
                'rating'     => $request->rating,
            ]);

            return JsonResponse::respondSuccess(
                'Comment added successfully',
                new TeacherCommentResource($comment->load('student'))
            );
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    

}

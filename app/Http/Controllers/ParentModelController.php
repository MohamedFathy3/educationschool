<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ParentAuthController extends Controller
{
    // Register with qr_code of student
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string',
            'phone'    => 'required|string|unique:parents,phone',
            'email'    => 'required|email|unique:parents,email',
            'password' => 'required|min:6',
            'qr_code'  => 'required|exists:students,qr_code',
        ]);

        $data['password'] = Hash::make($data['password']);

        $parent = ParentModel::create($data);

        $student = Student::where('qr_code', $request->qr_code)->first();
        $student->update(['parent_id' => $parent->id]);

        return response()->json([
            'message' => 'Parent registered & linked with student successfully',
            'parent'  => $parent,
            'student' => $student,
            'token'   => $parent->createToken('parent-token')->plainTextToken,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'qr_code'  => 'required|string|exists:students,qr_code',
            'password' => 'required',
        ]);

        $student = Student::where('qr_code', $request->qr_code)->first();

        if (!$student || !$student->parent) {
            return response()->json(['message' => 'Student not linked with parent'], 404);
        }

        $parent = $student->parent;

        if (!Hash::check($request->password, $parent->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        return response()->json([
            'message' => 'Login successful',
            'parent'  => $parent,
            'student' => $student,
            'token'   => $parent->createToken('parent-token')->plainTextToken,
        ]);
    }


    public function checkAuth()
    {
        try {
            $parent = Auth::user();

            if (!$parent) {
                return JsonResponse::respondError('Unauthenticated', 401);
            }

            // نجيب الطالب المرتبط
            $student = $parent->student;

            if (!$student) {
                return JsonResponse::respondError('No student linked to this parent', 404);
            }

            $student->load([
                'courses.teacher',
                'courses.stage',
                'courses.subject',
                'courses.country',
                'courses.courseDetail',
                'courses.courseDetail.students',
                'courses.exams.questions.choices',
                'courses.exams.studentExams' => function ($query) use ($student) {
                    $query->where('student_id', $student->id);
                },
            ]);

            return JsonResponse::respondSuccess([
                'message' => 'Authenticated',
                'student' => new StudentResource($student),
            ]);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

}


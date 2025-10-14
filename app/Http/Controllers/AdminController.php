<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Course;
use App\Models\Teacher;
class AdminController extends Controller
{

    public function index()
    {
        try {
            $total_students = Student::count();
            $total_courses = Course::count();
            $total_instructors = Teacher::count();

            $total_revenue = Course::all()->sum(function ($course) {
                $final_price = $course->discount > 0
                    ? $course->original_price - ($course->original_price * $course->discount / 100)
                    : $course->original_price;

                $students_count = DB::table('course_student')
                    ->where('course_id', $course->id)
                    ->count();

                return $final_price * $students_count;
            });

            $current_month = now()->month;
            $previous_month = now()->subMonth()->month;

            $current_students = Student::whereMonth('created_at', $current_month)->count();
            $current_courses = Course::whereMonth('created_at', $current_month)->count();
            $current_instructors = Teacher::whereMonth('created_at', $current_month)->count();

            $current_revenue = Course::whereMonth('created_at', $current_month)
                ->get()
                ->sum(function ($course) {
                    $final_price = $course->discount > 0
                        ? $course->original_price - ($course->original_price * $course->discount / 100)
                        : $course->original_price;
                    $students_count = DB::table('course_student')
                        ->where('course_id', $course->id)
                        ->count();
                    return $final_price * $students_count;
                });

            $previous_students = Student::whereMonth('created_at', $previous_month)->count();
            $previous_courses = Course::whereMonth('created_at', $previous_month)->count();
            $previous_instructors = Teacher::whereMonth('created_at', $previous_month)->count();

            $previous_revenue = Course::whereMonth('created_at', $previous_month)
                ->get()
                ->sum(function ($course) {
                    $final_price = $course->discount > 0
                        ? $course->original_price - ($course->original_price * $course->discount / 100)
                        : $course->original_price;
                    $students_count = DB::table('course_student')
                        ->where('course_id', $course->id)
                        ->count();
                    return $final_price * $students_count;
                });

            $students_change = $previous_students > 0 ? round((($current_students - $previous_students) / $previous_students) * 100, 2) : 0;
            $courses_change = $previous_courses > 0 ? round((($current_courses - $previous_courses) / $previous_courses) * 100, 2) : 0;
            $instructors_change = $previous_instructors > 0 ? round((($current_instructors - $previous_instructors) / $previous_instructors) * 100, 2) : 0;
            $revenue_change = $previous_revenue > 0 ? round((($current_revenue - $previous_revenue) / $previous_revenue) * 100, 2) : 0;

            $monthly_data = [];
            $months_ar = [
                'January' => 'يناير', 'February' => 'فبراير', 'March' => 'مارس',
                'April' => 'أبريل', 'May' => 'مايو', 'June' => 'يونيو',
                'July' => 'يوليو', 'August' => 'أغسطس', 'September' => 'سبتمبر',
                'October' => 'أكتوبر', 'November' => 'نوفمبر', 'December' => 'ديسمبر',
            ];

            $start = now()->subMonths(5);
            for ($i = 0; $i < 6; $i++) {
                $month = $start->copy()->addMonths($i);
                $monthName = $months_ar[$month->format('F')];

                $monthly_courses = Course::whereMonth('created_at', $month->month)->get();
                $monthly_revenue = (int) $monthly_courses->sum(function ($course) {
                    $final_price = $course->discount > 0
                        ? $course->original_price - ($course->original_price * $course->discount / 100)
                        : $course->original_price;
                    $students_count = DB::table('course_student')
                        ->where('course_id', $course->id)
                        ->count();
                    return $final_price * $students_count;
                });

                $monthly_data[] = [
                    'name' => $monthName,
                    'طلاب' => Student::whereMonth('created_at', $month->month)->count(),
                    'إيرادات' => $monthly_revenue,
                    'كورسات' => $monthly_courses->count(),
                ];
            }

            $visitors_by_country = [
                [
                    'name' => 'الكل',
                    'value' => 100,
                    'visitors' => $total_students,
                ]
            ];

            $recent_courses = Course::with('teacher')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($course) {
                    $final_price = $course->discount > 0
                        ? $course->original_price - ($course->original_price * $course->discount / 100)
                        : $course->original_price;

                    $students_count = DB::table('course_student')
                        ->where('course_id', $course->id)
                        ->count();

                    return [
                        'id' => $course->id,
                        'name' => $course->title,
                        'teacher' => $course->teacher->name ?? null,
                        'students' => $students_count,
                        'rating' => $course->average_rating ?? 0,
                        'final_price' => $final_price,
                        'revenue' => $final_price * $students_count,
                        'created_at' => $course->created_at->format('Y-m-d'),
                    ];
                });

            return response()->json([
                'dashboard_stats' => [
                    'total_students' => $total_students,
                    'total_courses' => $total_courses,
                    'total_instructors' => $total_instructors,
                    'total_revenue' => $total_revenue,
                    'students_change' => $students_change . '%',
                    'courses_change' => $courses_change . '%',
                    'instructors_change' => $instructors_change . '%',
                    'revenue_change' => $revenue_change . '%',
                ],
                'monthly_data' => $monthly_data,
                'visitors_by_country' => $visitors_by_country,
                'recent_courses' => $recent_courses,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'الايميل او كلمة المرور غير صحيحة'
            ], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => $admin
        ]);
    }

    public function checkAuth(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    }
}

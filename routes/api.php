<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseDetailController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\IT\BrandController;
use App\Http\Controllers\IT\CategoryController;
use App\Http\Controllers\IT\CompanyController;
use App\Http\Controllers\IT\DepartmentController;
use App\Http\Controllers\IT\DeviceController;
use App\Http\Controllers\IT\DeviceModelController;
use App\Http\Controllers\IT\DeviceStatusController;
use App\Http\Controllers\IT\EquipmentStatusController;
use App\Http\Controllers\IT\GraphicCardController;
use App\Http\Controllers\IT\MemoryController;
use App\Http\Controllers\IT\OrganizationController;
use App\Http\Controllers\IT\PositionController;
use App\Http\Controllers\IT\ProcessorController;
use App\Http\Controllers\IT\ReplyController;
use App\Http\Controllers\IT\ReportController;
use App\Http\Controllers\IT\StorageController;
use App\Http\Controllers\IT\TicketController;
use App\Http\Controllers\IT\TypeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



//                                            ADMIN
Route::post('/admin/login', [AdminController::class, 'login']);

Route::middleware('auth:admins')->group(function () {
    Route::get('/admin/check-auth', [AdminController::class, 'checkAuth']);
    Route::post('/admin/logout', [AdminController::class, 'logout']);
});

//////////////////////////////////////////////////////////Teacher//////////////////////////////////////
Route::post('teachers/register', [TeacherController::class, 'register']);
Route::post('teachers/login', [TeacherController::class, 'login']);
Route::middleware('auth:sanctum')->get('teachers/check-auth', [TeacherController::class, 'checkAuth']);
Route::middleware('auth:sanctum')->post('teachers/update-profile', [TeacherController::class, 'updateProfile']);

Route::post('teacher/index', [TeacherController::class, 'index']);
Route::post('teacher/restore', [TeacherController::class, 'restore']);
Route::delete('teacher/delete', [TeacherController::class, 'destroy']);
Route::delete('teacher/force-delete', [TeacherController::class, 'forceDelete']);
Route::post('teacher/update/{teacher}', [TeacherController::class, 'forceUpdate']);
Route::put('/teacher/{id}/{column}', [TeacherController::class, 'toggle']);
Route::apiResource('teacher', TeacherController::class);

//////////////////////////////////////////////////////////Teacher//////////////////////////////////////



//////////////////////////////////////////////////////////Course//////////////////////////////////////
Route::post('course/index', [CourseController::class, 'index']);
Route::middleware(['auth:teachers'])->group(function () {
    Route::post('course-teacher/index', [CourseController::class, 'indexTeacher']);
    Route::post('course/restore', [CourseController::class, 'restore']);
    Route::delete('course/delete', [CourseController::class, 'destroy']);
    Route::delete('course/force-delete', [CourseController::class, 'forceDelete']);
    Route::post('course/update/{course}', [CourseController::class, 'forceUpdate']);
    Route::put('/course/{id}/{column}', [CourseController::class, 'toggle']);
    Route::apiResource('course', CourseController::class);
});
//////////////////////////////////////////////////////////Course//////////////////////////////////////


//////////////////////////////////////////////////////////Course Details//////////////////////////////////////
    Route::post('course-detail/index', [CourseDetailController::class, 'index']);
    Route::post('course-detail/restore', [CourseDetailController::class, 'restore']);
    Route::delete('course-detail/delete', [CourseDetailController::class, 'destroy']);
    Route::delete('course-detail/force-delete', [CourseDetailController::class, 'forceDelete']);
    Route::post('course-detail/update/{course}', [CourseDetailController::class, 'forceUpdate']);
    Route::put('/course-detail/{id}/{column}', [CourseDetailController::class, 'toggle']);
    Route::apiResource('course-detail', CourseDetailController::class);

//////////////////////////////////////////////////////////Course Details//////////////////////////////////////





Route::middleware('auth:teachers')->group(function () {
    Route::post('exams', [ExamController::class, 'store']); // المدرس يعمل امتحان
    Route::post('exams/{exam}/questions', [ExamController::class, 'addQuestion']); // إضافة سؤال
});
Route::get('exams/{exam}', [ExamController::class, 'show']);
Route::middleware('auth:students')->post('exams/{exam}/submit', [ExamController::class, 'submit']); // الطالب يجاوب







//////////////////////////////////////////////////////////country//////////////////////////////////////

Route::post('/country/index', [CountryController::class, 'index']);
Route::put('/country/{id}/{column}', [CountryController::class, 'toggle']);
Route::apiResource('country', CountryController::class);
//////
Route::get('/country-public-list', [CountryController::class, 'publicIndex']);

//////////////////////////////////////////////////////////country//////////////////////////////////////




//////////////////////////////////////////////////////////Stage//////////////////////////////////////

Route::post('stage/index', [StageController::class, 'index']);
Route::post('stage/restore', [StageController::class, 'restore']);
Route::delete('stage/delete', [StageController::class, 'destroy']);
Route::delete('stage/force-delete', [StageController::class, 'forceDelete']);
Route::post('stage/update/{stage}', [StageController::class, 'forceUpdate']);
Route::put('/stage/{id}/{column}', [StageController::class, 'toggle']);
Route::apiResource('stage', StageController::class);

//////////////////////////////////////////////////////////Stage//////////////////////////////////////


//////////////////////////////////////////////////////////Subject//////////////////////////////////////

Route::post('subject/index', [SubjectController::class, 'index']);
Route::post('subject/restore', [SubjectController::class, 'restore']);
Route::delete('subject/delete', [SubjectController::class, 'destroy']);
Route::delete('subject/force-delete', [SubjectController::class, 'forceDelete']);
Route::post('subject/update/{subject}', [SubjectController::class, 'forceUpdate']);
Route::put('/subject/{id}/{column}', [SubjectController::class, 'toggle']);
Route::apiResource('subject', SubjectController::class);

//////////////////////////////////////////////////////////Subject//////////////////////////////////////




//////////////////////////////////////////////////////////Student//////////////////////////////////////
Route::post('student/register', [StudentController::class, 'register']);
Route::post('student/login', [StudentController::class, 'login']);
Route::middleware('auth:sanctum')->get('student/check-auth', [StudentController::class, 'checkAuth']);
Route::middleware('auth:sanctum')->post('/student/enroll', [StudentController::class, 'enroll']);
Route::middleware('auth:sanctum')->post('/student/unenroll', [StudentController::class, 'unenroll']);

Route::post('student/index', [StudentController::class, 'index']);
Route::post('student/restore', [StudentController::class, 'restore']);
Route::delete('student/delete', [StudentController::class, 'destroy']);
Route::delete('student/force-delete', [StudentController::class, 'forceDelete']);
Route::post('student/update/{student}', [StudentController::class, 'forceUpdate']);
Route::put('/student/{id}/{column}', [StudentController::class, 'toggle']);
Route::apiResource('student', StudentController::class);

//////////////////////////////////////////////////////////Student//////////////////////////////////////


//////////////////////////////////////////////////////////Message//////////////////////////////////////
Route::middleware('auth:sanctum')->group(function () {
    Route::post('chat/send', [MessageController::class, 'sendMessage']);
    Route::get('chat/messages', [MessageController::class, 'getMessages']);
});
//////////////////////////////////////////////////////////Message//////////////////////////////////////

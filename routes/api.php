<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
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
use App\Http\Controllers\StageController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



//                                            ADMIN


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




<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\UpdateProfileTeacherRequest;
use App\Http\Requests\UpdateProfileTeacherTwoRequest;
use App\Http\Requests\UpdateTeacherCommissionRequest;
use App\Http\Resources\TeacherResource;
use App\Interfaces\TeacherRepositoryInterface;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class TeacherController extends BaseController
{
    use HttpResponses;

    protected mixed $crudRepository;

    public function __construct(TeacherRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $brands = TeacherResource::collection($this->crudRepository->all());
            return $brands->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Teacher $teacher): ?\Illuminate\Http\JsonResponse
    {
        try {
            $teacher->load(['comments.student','stages', 'subjects']);

            return JsonResponse::respondSuccess(
                'Item Fetched Successfully',
                new TeacherResource($teacher)
            );
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function forceUpdate(TeacherRequest $request, Teacher $teacher)
    {
        try {
        $data = $request->validated();

        $files = [
            'image'            => 'teachers/profile',
            'certificate_image'=> 'teachers/certificates',
            'experience_image' => 'teachers/experience',
            'id_card_front'  => 'teachers/idCardFront',
            'id_card_back'  => 'teachers/idCardBack',
        ];

        foreach ($files as $field => $folder) {
            if ($request->hasFile($field)) {
                if ($teacher->$field && \Storage::disk('public')->exists($teacher->$field)) {
                    \Storage::disk('public')->delete($teacher->$field);
                }

                $file = $request->file($field);
                $filename = $field . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($folder, $filename, 'public');
                $data[$field] = $path;
            }
        }

        $this->crudRepository->update($data, $teacher->id);

        activity()->performedOn($teacher)
            ->withProperties(['attributes' => $teacher])
            ->log('update');

        return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecords('teachers', $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Teacher::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceDelete(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $exists = Teacher::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Teacher::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function fetchTeacher(Request $request)
    {
        try {
            $TeacherData = Teacher::get();
            return TeacherResource::collection($TeacherData)->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



 public function updateCommission(UpdateTeacherCommissionRequest $request, $id)
    {
        try {
            $teacher = Teacher::findOrFail($id);

            // تحديث العمولة والإيميل الثاني
            $teacher->commission = $request->commission;
            $teacher->secound_email = $request->secound_email;

            // لو الريكوست فيه مكافأة نضيفها
            if ($request->filled('reward')) {
                $teacher->rewards += $request->reward; // نضيف المكافأة على القديمة
            }

            $teacher->save();

            return JsonResponse::respondSuccess([
                'new_commission' => $teacher->commission . '%',
                'secound_email' => $teacher->secound_email,
                'total_rewards' => $teacher->rewards, // نرجع القيمة بعد التحديث
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


/////////////////////////// Front Methods ///////////////////////////

    public function register(TeacherRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            $files = [
                'image'             => 'teachers/profile',
                'certificate_image' => 'teachers/certificates',
                'experience_image'  => 'teachers/experience',
                'id_card_front'  => 'teachers/idCardFront',
                'id_card_back'  => 'teachers/idCardBack',
            ];

            foreach ($files as $field => $folder) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = $field . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs($folder, $filename, 'public');
                    $data[$field] = $path;
                }
            }

            // حذف stage_id و subject_id من الـ data قبل الإنشاء
            $stageIds = $data['stage_id'];
            $subjectIds = $data['subject_id'];
            unset($data['stage_id'], $data['subject_id']);

            $teacher = $this->crudRepository->create($data);

            // ربط العلاقات
            $teacher->stages()->sync($stageIds);
            $teacher->subjects()->sync($subjectIds);

            $token = $teacher->createToken('teacher_token')->plainTextToken;

            return JsonResponse::respondSuccess([
                'message' => 'Teacher registered successfully',
                'teacher' => new TeacherResource($teacher->load(['stages', 'subjects'])),
                'token'   => $token,
            ]);

        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $email = $credentials['email'];

            $teacher = Teacher::where('active', 1)
                ->whereRaw('LOWER(email) = ?', [$email])
                ->first();

            // 2️⃣ لو مش موجود → ابحث بالإيميل التاني
            if (!$teacher) {
                $teacher = Teacher::where('active', 1)
                    ->whereRaw('LOWER(secound_email) = ?', [$email])
                    ->first();
            }

            if (!$teacher || !Hash::check($credentials['password'], $teacher->password)) {
                return JsonResponse::respondError('Invalid email or password', 401);
            }

            $token = $teacher->createToken('teacher_token')->plainTextToken;

            return JsonResponse::respondSuccess([
                'message' => 'Login successful',
                'teacher' => new TeacherResource($teacher),
                'token'   => $token,
            ]);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function checkAuth()
    {
        try {
            $teacher = Auth::user();

            if (!$teacher) {
                return JsonResponse::respondError('Unauthenticated', 401);
            }

            return JsonResponse::respondSuccess([
                'message' => 'Authenticated',
                'teacher' => new TeacherResource($teacher->load(['stages', 'subjects'])),
            ]);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function updateProfile(UpdateProfileTeacherTwoRequest $request)
    {
        try {
            $teacher = Auth::user();

            if (!$teacher) {
                return JsonResponse::respondError('Unauthenticated', 401);
            }

            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $files = [
                'image'             => 'teachers/profile',
                'certificate_image' => 'teachers/certificates',
                'experience_image'  => 'teachers/experience',
                'id_card_front'  => 'teachers/idCardFront',
                'id_card_back'  => 'teachers/idCardBack',
            ];

            foreach ($files as $field => $folder) {
                if ($request->hasFile($field)) {
                    // امسح القديم لو موجود
                    if ($teacher->$field && \Storage::disk('public')->exists($teacher->$field)) {
                        \Storage::disk('public')->delete($teacher->$field);
                    }

                    $file = $request->file($field);
                    $filename = $field . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs($folder, $filename, 'public');
                    $data[$field] = $path;
                }
            }

            $this->crudRepository->update($data, $teacher->id);

            activity()->performedOn($teacher)
                ->withProperties(['attributes' => $teacher])
                ->log('update_profile');

            return JsonResponse::respondSuccess([
                'message' => 'Profile updated successfully',
                'teacher' => new TeacherResource($teacher->fresh()), // عشان نرجع البيانات بعد التحديث
            ]);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




  public function report(): \Illuminate\Http\JsonResponse
    {
        try {
            $totalStudents = Student::count();
            $totalCourses  = Course::count();
            $totalTeachers = Teacher::count();

            $totalVisitors = Cache::get('total_visitors', 0);

            $totalVisitors++;
            Cache::put('total_visitors', $totalVisitors);

            $data = [
                'total_students' => $totalStudents,
                'total_teachers' => $totalTeachers,
                'total_courses'  => $totalCourses,
                'total_visitors' => $totalVisitors,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}


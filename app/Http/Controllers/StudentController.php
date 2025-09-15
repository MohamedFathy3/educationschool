<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Interfaces\StudentRepositoryInterface;
use App\Models\Student;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends BaseController
{
    use HttpResponses;

    protected mixed $crudRepository;

    public function __construct(StudentRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $Students = StudentResource::collection($this->crudRepository->all());
            return $Students->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function store(StudentRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('students', $filename, 'public');
                $data['image'] = $path;
            }

            $this->crudRepository->create($data);

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Student $student): ?\Illuminate\Http\JsonResponse
    {
        try {
            return JsonResponse::respondSuccess('Item Fetched Successfully', new StudentResource($student));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceUpdate(StudentRequest $request, Student $student)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                if ($student->image && \Storage::disk('public')->exists($student->image)) {
                    \Storage::disk('public')->delete($student->image);
                }

                $path = $file->storeAs('students', $filename, 'public');
                $data['image'] = $path;
            }

            $this->crudRepository->update($data, $student->id);
            activity()->performedOn($student)->withProperties(['attributes' => $student])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecords('students', $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Student::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceDelete(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $exists = Student::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Student::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


/////////////////////////// Front Methods ///////////////////////////

    public function register(StudentRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            // رفع الصورة
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = 'student_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('students/profile', $filename, 'public');
                $data['image'] = $path;
            }

            // qr_code random 6 digits
            $data['qr_code'] = rand(100000, 999999);

            $student = Student::create($data);

            $token = $student->createToken('student_token')->plainTextToken;

            return JsonResponse::respondSuccess([
                'message' => 'Student registered successfully',
                'student' => new StudentResource($student),
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
            $student = Student::where('email', $credentials['email'])->first();
            if (!$student || !Hash::check($credentials['password'], $student->password)) {
                return JsonResponse::respondError('Invalid email or password', 401);
            }
            $token = $student->createToken('student_token')->plainTextToken;
            return JsonResponse::respondSuccess([
                'message' => 'Login successful',
                'student' => new StudentResource($student),
                'token'   => $token,
            ]);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function checkAuth()
    {
        try {
            $student = Auth::user();

            if (!$student) {
                return JsonResponse::respondError('Unauthenticated', 401);
            }

            return JsonResponse::respondSuccess([
                'message' => 'Authenticated',
                'student' => new StudentResource($student),
            ]);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


}


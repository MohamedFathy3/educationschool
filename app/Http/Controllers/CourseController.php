<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseResource;
use App\Interfaces\CourseRepositoryInterface;
use App\Models\Course;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends BaseController
{
    use HttpResponses;

    protected mixed $crudRepository;

    public function __construct(CourseRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $courses = CourseResource::collection($this->crudRepository->all(
                ['teacher', 'stage', 'subject', 'country', 'courseDetail'],
                [],
                ['*']
            ));
            return $courses->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexTeacher()
    {
        try {
            $courses = CourseResource::collection($this->crudRepository->all(
                ['teacher', 'stage', 'subject', 'country', 'courseDetail'],
                ['teacher_id' => Auth::user()->id],
                ['*']
            ));
            return $courses->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(CourseRequest $request)
    {
        try {
            $data = $request->validated();
            $data['teacher_id'] = Auth::user()->id;

            $originalPrice = $data['original_price'];
            $discount = $data['discount'] ?? 0;

            if ($discount > 0) {
                $finalPrice = $originalPrice - ($originalPrice * ($discount / 100));
            } else {
                $finalPrice = $originalPrice;
            }

            $data['price'] = $finalPrice;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('courses', $filename, 'public');
                $data['image'] = $path;
            }

            $this->crudRepository->create($data);

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function show(Course $course): ?\Illuminate\Http\JsonResponse
    {
        try {
            return JsonResponse::respondSuccess(
                'Item Fetched Successfully',
                new CourseResource($course->load(['teacher', 'stage', 'subject', 'country', 'courseDetail']))
            );
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function forceUpdate(CourseRequest $request, Course $course)
    {
        try {
            $data = $request->validated();

            $originalPrice = $data['original_price'] ?? $course->original_price;
            $discount = $data['discount'] ?? $course->discount;

            if ($discount > 0) {
                $finalPrice = $originalPrice - ($originalPrice * ($discount / 100));
            } else {
                $finalPrice = $originalPrice;
            }

            $data['price'] = $finalPrice;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                if ($course->image && \Storage::disk('public')->exists($course->image)) {
                    \Storage::disk('public')->delete($course->image);
                }

                $path = $file->storeAs('courses', $filename, 'public');
                $data['image'] = $path;
            }

            $this->crudRepository->update($data, $course->id);

            activity()
                ->performedOn($course)
                ->withProperties(['attributes' => $data])
                ->log('update');

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function destroy(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecords('courses', $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Course::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceDelete(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $exists = Course::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Course::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function fetchCourse(Request $request)
    {
        try {
            $CourseData = Course::get();
            return CourseResource::collection($CourseData)->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    
}


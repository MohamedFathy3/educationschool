<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\CourseDetailRequest;
use App\Http\Resources\CourseDetailResource;
use App\Interfaces\CourseDetailRepositoryInterface;
use App\Models\CourseDetail;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseDetailController extends BaseController
{
    use HttpResponses;

    protected mixed $crudRepository;

    public function __construct(CourseDetailRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $courses = CourseDetailResource::collection($this->crudRepository->all(
                ['course'],
                [],
                ['*']
            ));
            return $courses->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(CourseDetailRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = 'course_detail_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('course_details', $filename, 'public');
                $data['file_path'] = $path;
            }

            $detail = CourseDetail::create($data);

            return response()->json([
                'message' => 'Course detail added successfully',
                'data'    => new CourseDetailResource($detail),
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(CourseDetail $course_detail): ?\Illuminate\Http\JsonResponse
    {
        try {
            return JsonResponse::respondSuccess(
                'Item Fetched Successfully',
                new CourseDetailResource($course_detail->load(['course']))
            );
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function forceUpdate(CourseDetailRequest $request, CourseDetail $course_detail)
    {
      try {
            $data = $request->validated();

            if ($request->hasFile('file')) {
                if ($course_detail->file_path && Storage::disk('public')->exists($course_detail->file_path)) {
                    Storage::disk('public')->delete($course_detail->file_path);
                }

                $file = $request->file('file');
                $filename = 'course_detail_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('course_details', $filename, 'public');
                $data['file_path'] = $path;
            }

            $course_detail->update($data);

            return response()->json([
                'message' => 'Course detail updated successfully',
                'data'    => new CourseDetailResource($course_detail),
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
     }



    public function destroy(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecords('course_details', $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(CourseDetail::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceDelete(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $exists = CourseDetail::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(CourseDetail::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function saveWatchingData(Request $request)
    {
        $student = Auth::user();

        $data = $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'course_detail_id' => 'required|exists:course_details,id',
            'started_at'       => 'nullable|date',
            'watched_duration' => 'nullable|integer',
            'completed'        => 'nullable|boolean',
        ]);

        $student->watchedLectures()->syncWithoutDetaching([
            $data['course_detail_id'] => [
                'course_id'        => $data['course_id'],
                'started_at'       => $data['started_at'] ?? now(),
                'watched_duration' => $data['watched_duration'] ?? 0,
                'completed'        => $data['completed'] ?? false,
            ]
        ]);

        return response()->json(['message' => 'Watching data saved successfully']);
    }

}


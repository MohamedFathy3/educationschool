<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\LibraryRequest;
use App\Http\Resources\LibraryResource;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;

class LibraryController extends Controller
{


     public function studentIndex(Request $request)
    {
        $query = Library::query()->orderBy('created_at', 'desc');

        // جلب العناصر التي نوعها teacher فقط
        $query->where('type', 'student');

        $libraries = $query->paginate(12);

        return LibraryResource::collection($libraries)->response();
    }

    public function teacherIndex(Request $request)
    {
        $query = Library::query()->orderBy('created_at', 'desc');

        // جلب العناصر التي نوعها teacher فقط
        $query->where('type', 'teacher');

        $libraries = $query->paginate(12);

        return LibraryResource::collection($libraries)->response();
    }

    public function store(LibraryRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();
                $type = $this->detectType($ext);
                $filename = 'library_' . time() . '_' . Str::random(8) . '.' . $ext;
                $path = $file->storeAs("libraries/{$type}", $filename, 'public');

                $data['file_path'] = $path;
            }

            $library = Library::create($data);

            return response()->json([
                'message' => 'File uploaded successfully',
                'data' => new LibraryResource($library),
            ], 201);

        } catch (Exception $e) {
            return response()->json(['message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function show(Library $library)
    {
        return new LibraryResource($library);
    }

    public function update(LibraryRequest $request, Library $library)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('file')) {
                if ($library->file_path && \Storage::disk('public')->exists($library->file_path)) {
                    \Storage::disk('public')->delete($library->file_path);
                }
                $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();
                $type = $this->detectType($ext);
                $filename = 'library_' . time() . '_' . Str::random(8) . '.' . $ext;
                $path = $file->storeAs("libraries/{$type}", $filename, 'public');

                $data['file_path'] = $path;
            }


            $library->update($data);

            return response()->json([
                'message' => 'Library updated successfully',
                'data' => new LibraryResource($library),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Library $library)
    {
        // optional: delete files from storage
        if ($library->file_path && \Storage::disk('public')->exists($library->file_path)) {
            \Storage::disk('public')->delete($library->file_path);
        }
        if ($library->thumbnail_path && \Storage::disk('public')->exists($library->thumbnail_path)) {
            \Storage::disk('public')->delete($library->thumbnail_path);
        }

        $library->delete();

        return response()->json(['message' => 'Library item deleted']);
    }

    // helper to detect type folder from extension
    protected function detectType($ext)
    {
        $ext = strtolower($ext);
        $videos = ['mp4','mov','avi','wmv','mkv'];
        $images = ['jpg','jpeg','png','gif','webp'];
        $docs = ['pdf','doc','docx','ppt','pptx'];

        if (in_array($ext, $videos)) return 'videos';
        if (in_array($ext, $images)) return 'images';
        if (in_array($ext, $docs)) return 'docs';
        return 'others';
    }
}

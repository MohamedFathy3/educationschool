<?php

namespace App\Http\Controllers;

use App\Http\Requests\WithdrawRequestStore;
use App\Http\Resources\WithdrawRequestResource;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawRequestController extends Controller
{
    public function store(WithdrawRequestStore $request)
    {
        $teacher = auth('teachers')->user();

        if ($request->amount > $teacher->amount) {
            return response()->json([
                'result' => 'Error',
                'message' => 'الرصيد غير كافي',
            ], 400);
        }

        $withdraw = WithdrawRequest::create([
            'teacher_id' => $teacher->id,
            'transfer_type'     => $request->transfer_type,
            'amount'     => $request->amount,
            'status'     => 'pending',
        ]);

        return response()->json([
            'result' => 'Success',
            'message' => 'تم إرسال الطلب بنجاح',
            'data' => new WithdrawRequestResource($withdraw->load('teacher')),
        ], 201);
    }


    public function indexTeacher()
    {
        $teacher = Auth::user();
        $withdraws = WithdrawRequest::with('teacher')
            ->where('teacher_id', $teacher->id)
            ->get();
        return WithdrawRequestResource::collection($withdraws);
    }



    // الأدمن يغير الحالة
    public function updateStatus(Request $request, WithdrawRequest $withdrawRequest)
    {
        $request->validate([
            'status'  => 'required|in:pending,received,accepted,rejected',
            'comment' => 'nullable|string',
        ]);

        $withdrawRequest->update([
            'status'  => $request->status,
            'comment' => $request->comment,
        ]);

        // في حالة القبول نقص المبلغ من رصيد المدرس
        if ($request->status === 'accepted') {
            $teacher = $withdrawRequest->teacher;
            $teacher->amount -= $withdrawRequest->amount;
            $teacher->save();
        }

        return response()->json([
            'result' => 'Success',
            'message' => 'تم تحديث حالة الطلب',
            'data' => new WithdrawRequestResource($withdrawRequest->load('teacher')),
        ]);
    }

    // الأدمن يشوف كل الطلبات
    public function index()
    {
        $withdraws = WithdrawRequest::with('teacher')->latest()->get();
        return WithdrawRequestResource::collection($withdraws);
    }
}

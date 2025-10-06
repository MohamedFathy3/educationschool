<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\CouponResource;
use App\Interfaces\CouponRepositoryInterface;
use App\Models\Coupon;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;

class CouponController extends BaseController
{
    use HttpResponses;

    protected mixed $crudRepository;

    public function __construct(CouponRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $Coupon = CouponResource::collection($this->crudRepository->all());
            return $Coupon->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function store(CouponRequest $request)
    {
        try {
            $data = $request->validated();

            $this->crudRepository->create($data);

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Coupon $coupon): ?\Illuminate\Http\JsonResponse
    {
        try {
            return JsonResponse::respondSuccess('Item Fetched Successfully', new CouponResource($coupon));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceUpdate(CouponRequest $request, Coupon $coupon)
    {
        try {
            $data = $request->validated();

            $this->crudRepository->update($data, $coupon->id);
            activity()->performedOn($coupon)->withProperties(['attributes' => $coupon])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecords('coupons', $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function apply(Request $request)
        {
            $request->validate([
                'code' => 'required|string',
                'amount' => 'required|numeric|min:0', // المبلغ قبل الخصم
            ]);

            $coupon = Coupon::where('code', $request->code)->first();

            if (!$coupon || !$coupon->isValid()) {
                return response()->json(['message' => 'Invalid or expired coupon'], 400);
            }

            if ($coupon->min_order_amount && $request->amount < $coupon->min_order_amount) {
                return response()->json(['message' => 'Order amount too low for this coupon'], 400);
            }

            // حساب الخصم
            $discount = $coupon->type === 'percentage'
                ? ($request->amount * $coupon->value / 100)
                : $coupon->value;

            $newTotal = max(0, $request->amount - $discount);

            return response()->json([
                'message' => 'Coupon applied successfully',
                'discount' => round($discount, 2),
                'total_after_discount' => round($newTotal, 2),
            ]);
        }
}


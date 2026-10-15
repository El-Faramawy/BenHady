<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Constants\Messages\CouponMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\ApplyCouponRequest;
use App\Responses\ApiResponse;
use App\Services\Coupon\CouponService;
use App\Services\Coupon\DTO\ApplyCouponDTO;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    public function __construct(protected CouponService $couponService)
    {
    }

    public function apply(ApplyCouponRequest $request): JsonResponse
    {
        $userId = (int) auth()->id();
        $dto = ApplyCouponDTO::fromRequest($request, $userId);

        $result = $this->couponService->validateAndCalculate($dto->code, $dto->subtotal, $dto->userId);

        return (new ApiResponse())
            ->setData($result->toArray())
            ->setMessages([CouponMessages::applied()])
            ->create();
    }
}

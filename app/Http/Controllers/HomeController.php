<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Home\HomeRequest;
use App\Responses\ApiResponse;
use App\Services\Home\DTO\HomeFilterDTO;
use App\Services\Home\HomeService;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __construct(protected HomeService $homeService)
    {
    }

    public function __invoke(HomeRequest $request): JsonResponse
    {
        $dto = HomeFilterDTO::fromRequest($request);
        $data = $this->homeService->getHomeData($dto);

        return (new ApiResponse())
            ->setData($data)
            ->create();
    }
}

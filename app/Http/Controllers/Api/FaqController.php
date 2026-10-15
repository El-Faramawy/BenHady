<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Responses\ApiResponse;
use App\Services\Faq\FaqService;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
    public function __construct(protected FaqService $faqService)
    {
    }

    public function index(): JsonResponse
    {
        $faqs = $this->faqService->getFaqs();

        return (new ApiResponse())
            ->setData($faqs)
            ->create();
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Notification\DTO;

use Illuminate\Http\Request;

readonly class NotificationFilterDTO
{
    public function __construct(
        public int $perPage = 15,
        public int $page = 1,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $limit = $request->input('limit');
        $perPage = $request->input('per_page', $limit ?? 15);

        return new self(
            perPage: min(max((int) $perPage, 1), 50),
            page: max((int) $request->input('page', 1), 1),
        );
    }
}

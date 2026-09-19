<?php

declare(strict_types=1);

namespace App\Services\PhoneToken;

use App\Models\PhoneToken;
use App\Repositories\PhoneTokenRepository;
use App\Services\PhoneToken\DTO\DeletePhoneTokenDTO;
use App\Services\PhoneToken\DTO\SavePhoneTokenDTO;

class PhoneTokenService
{
    public function __construct(protected PhoneTokenRepository $phoneTokenRepository)
    {
    }

    public function saveToken(int $userId, SavePhoneTokenDTO $dto): PhoneToken
    {
        return $this->phoneTokenRepository->saveToken($userId, $dto->token);
    }

    public function deleteToken(int $userId, DeletePhoneTokenDTO $dto): int
    {
        return $this->phoneTokenRepository->deleteToken($dto->token, $userId);
    }
}

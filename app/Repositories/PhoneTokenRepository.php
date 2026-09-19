<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PhoneToken;
use Illuminate\Database\Eloquent\Collection;

class PhoneTokenRepository
{
    public function __construct(protected PhoneToken $model)
    {
    }

    public function saveToken(int $userId, string $phoneToken): PhoneToken
    {
        return $this->model->updateOrCreate(
            ['phone_token' => $phoneToken],
            ['user_id' => $userId]
        );
    }

    public function deleteToken(string $phoneToken, ?int $userId = null): int
    {
        $query = $this->model->where('phone_token', $phoneToken);

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        return $query->delete();
    }

    /**
     * @return Collection<int, PhoneToken>
     */
    public function getTokensByUserId(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }
}

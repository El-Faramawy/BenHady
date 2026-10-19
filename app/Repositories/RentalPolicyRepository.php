<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\RentalPolicy;

class RentalPolicyRepository
{
    public function __construct(protected RentalPolicy $model)
    {
    }

    public function getPolicy(): ?RentalPolicy
    {
        return $this->model->first();
    }

    public function getOrCreate(): RentalPolicy
    {
        return $this->model->firstOrCreate(['id' => 1], [
            'insurance_policy_ar'    => 'يشمل التأمين الشامل الحوادث والأضرار غير المتعمدة وفقاً للشروط والأحكام.',
            'cancellation_policy_ar' => 'يمكنك إلغاء الحجز مجاناً حتى 24 ساعة قبل موعد استلام السيارة.',
            'tenant_instructions_ar' => '1. إحضار الهوية الوطنية أو الإقامة سارية المفعول.' . PHP_EOL . '2. رخصة قيادة سارية المفعول.',
        ]);
    }

    public function update(RentalPolicy $policy, array $data): bool
    {
        return $policy->update($data);
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Auth\DTO;

use Illuminate\Http\Request;

class RegisterStepTwoDTO
{
    public function __construct(
        public readonly string $phone,
        public readonly string $type,
        public readonly string $password,
        public readonly string $drivingLicenseNumber,
        public readonly string $licenseExpiryDate,
        public readonly ?string $idNumber,
        public readonly ?string $idNumberEndDate,
        public readonly ?string $versionNumber,
        public readonly ?string $licenseNumber,
        public readonly ?string $borderEntryNumber,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            phone: (string) $request->input('phone'),
            type: (string) $request->input('type'),
            password: (string) $request->input('password'),
            drivingLicenseNumber: (string) $request->input('driving_license_number'),
            licenseExpiryDate: (string) $request->input('license_expiry_date'),
            idNumber: $request->input('id_number'),
            idNumberEndDate: $request->input('id_number_end_date'),
            versionNumber: $request->input('version_number'),
            licenseNumber: $request->input('license_number'),
            borderEntryNumber: $request->input('border_entry_number'),
        );
    }

    /**
     * Convert DTO to array for mass assignment (excludes null values).
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'password' => $this->password,
            'driving_license_number' => $this->drivingLicenseNumber,
            'license_expiry_date' => $this->licenseExpiryDate,
            'id_number' => $this->idNumber,
            'id_number_end_date' => $this->idNumberEndDate,
            'version_number' => $this->versionNumber,
            'license_number' => $this->licenseNumber,
            'border_entry_number' => $this->borderEntryNumber,
        ], fn ($value) => $value !== null);
    }
}

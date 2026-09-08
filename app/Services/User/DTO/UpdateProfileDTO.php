<?php

declare(strict_types=1);

namespace App\Services\User\DTO;

use Illuminate\Http\Request;

class UpdateProfileDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $dateOfBirth = null,
        public readonly ?string $type = null,
        public readonly ?string $password = null,
        public readonly ?string $drivingLicenseNumber = null,
        public readonly ?string $licenseExpiryDate = null,
        public readonly ?string $idNumber = null,
        public readonly ?string $idNumberEndDate = null,
        public readonly ?string $versionNumber = null,
        public readonly ?string $licenseNumber = null,
        public readonly ?string $borderEntryNumber = null,
        public readonly ?string $language = null,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            phone: $request->input('phone'),
            dateOfBirth: $request->input('date_of_birth'),
            type: $request->input('type'),
            password: $request->input('password'),
            drivingLicenseNumber: $request->input('driving_license_number'),
            licenseExpiryDate: $request->input('license_expiry_date'),
            idNumber: $request->input('id_number'),
            idNumberEndDate: $request->input('id_number_end_date'),
            versionNumber: $request->input('version_number'),
            licenseNumber: $request->input('license_number'),
            borderEntryNumber: $request->input('border_entry_number'),
            language: $request->input('language'),
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->dateOfBirth,
            'type' => $this->type,
            'password' => $this->password,
            'driving_license_number' => $this->drivingLicenseNumber,
            'license_expiry_date' => $this->licenseExpiryDate,
            'id_number' => $this->idNumber,
            'id_number_end_date' => $this->idNumberEndDate,
            'version_number' => $this->versionNumber,
            'license_number' => $this->licenseNumber,
            'border_entry_number' => $this->borderEntryNumber,
            'language' => $this->language,
        ], fn ($value) => $value !== null);
    }
}

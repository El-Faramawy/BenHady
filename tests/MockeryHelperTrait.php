<?php

declare(strict_types=1);

namespace Tests;

use Mockery;

trait MockeryHelperTrait
{
    /**
     * Mock a service class and bind it into Laravel's service container.
     *
     * @param class-string $class
     * @param array<string, mixed>|null $parameters Key-value array of [methodName => returnValue]
     * @param bool $ignoreMissing Whether to ignore unexpected method calls
     */
    public function mockService(string $class, ?array $parameters = null, bool $ignoreMissing = false): object
    {
        $service = Mockery::mock($class);

        if ($ignoreMissing) {
            $service->shouldIgnoreMissing();
        }

        $this->addParametersToMock($service, $parameters);

        app()->instance($class, $service);

        return $service;
    }

    /**
     * Add method expectations with return values to a Mockery object.
     *
     * @param array<string, mixed>|null $parameters
     */
    protected function addParametersToMock(object $mock, ?array $parameters): void
    {
        if ($parameters) {
            foreach ($parameters as $method => $returnValue) {
                $mock->shouldReceive($method)->andReturn($returnValue);
            }
        }
    }

    /**
     * Mock the validation database presence verifier to simulate database existence checks without a database.
     *
     * @param int $count Number of matching rows to return (0 for unique passing, >=1 for exists passing)
     */
    public function mockPresenceVerifier(int $count = 1): void
    {
        $verifier = Mockery::mock(\Illuminate\Validation\DatabasePresenceVerifierInterface::class);
        $verifier->shouldReceive('setConnection')->byDefault();
        $verifier->shouldReceive('getCount')->andReturn($count);
        $verifier->shouldReceive('getMultiCount')->andReturn($count);

        app('validator')->setPresenceVerifier($verifier);
    }
}

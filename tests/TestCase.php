<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Validation\DatabasePresenceVerifierInterface;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use MockeryHelperTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $verifier = Mockery::mock(DatabasePresenceVerifierInterface::class);
        $verifier->shouldReceive('setConnection')->byDefault();
        // Return 0 for getCount by default so 'unique' checks pass, or return positive when specified
        $verifier->shouldReceive('getCount')->byDefault()->andReturn(0);
        $verifier->shouldReceive('getMultiCount')->byDefault()->andReturn(0);

        app('validator')->setPresenceVerifier($verifier);
    }
}

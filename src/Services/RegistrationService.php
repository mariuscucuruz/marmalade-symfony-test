<?php

namespace App\Services;

class RegistrationService
{
    /**
     * @param array $payload
     *
     * @return array
     *
     * @throws \JsonException
     */
    public function resolve(array $payload): array
    {
        return [
            'abi_code' => json_encode($payload, JSON_THROW_ON_ERROR)
        ];
    }
}
